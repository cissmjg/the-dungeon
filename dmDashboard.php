<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/webio/playerName.php';

const STARTNEWFIGHT = "StartNewFight";
const ENDOFROUND = "EndOfRound";
const DAILYRESET = "DailyReset";
const CURRENTCOMBATROUND = "CurrentCombatRound";
const REFRESHSPELLLIST = "RefreshSpellList";
const DMACTION = 'dmAction';

// Populate player and character names in $input
getPlayerName($errors, $input);

$player_permissions = getPlayerPermissions($pdo, $input[PLAYER_NAME], $errors);
if (count($errors) > 0) {
    RestHeaderHelper::emitRestHeaders();
    die(json_encode($errors));
}

if (!$player_permissions['is_dm']) {
    die();
}

$url = CurlHelper::buildUrl('dmDashboard');

$page_title = 'DM Dashboard';
$site_css_file = 'dnd-default.css';
$page_specific_js = '';
$page_specific_css = '';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
    <?php
    $locale = 'en_US';
    $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
    $current_combat_round = 0;
    if(!empty($_POST[CURRENTCOMBATROUND])) {
        $current_combat_round = $_POST[CURRENTCOMBATROUND];
    }

    if(!empty($_POST[DMACTION])) {
        $dmAction = $_POST[DMACTION];
        if($dmAction == STARTNEWFIGHT) {
            $current_combat_round = 1;
        } else if($dmAction == ENDOFROUND) {
            // End Of Round
            updateForEndOfRoundForSlots($pdo, $errors);
            $current_combat_round++;
        } else if($dmAction == DAILYRESET) {
            // dailyReset
            resetDailyForSlots($pdo, $errors);
            $current_combat_round = 0;    
        } else if($dmAction == REFRESHSPELLLIST) {
            // Do Nothing ... just get the active spell list
        }
    }

    $all_active_spells = getActiveSpells($pdo, $errors);
    $current_round_desc = $nf->format($current_combat_round) . ' round';

    ?>
    <form action="<?= $url ?>" method="POST">
        <?php
            echo HtmlHelper::buildHiddenTag(CURRENTCOMBATROUND, $current_combat_round) . PHP_EOL;
            echo HtmlHelper::buildHiddenTag(PLAYER_NAME, $input[PLAYER_NAME]) . PHP_EOL;
        ?>

        <table>
            <tr><td colspan="4" style="text-align:center;"><button name="dmAction" value="<?= STARTNEWFIGHT ?>" type="submit">Start New Fight</button></td></tr>
            <tr>
                    <td colspan="2" style="text-align: center;"><button name="dmAction" value="<?= ENDOFROUND ?>" type="submit">End of Round</button></td>
                    <td colspan="2" style="text-weight: bold;">Current round : <?= $current_round_desc ?></td>
            </tr>
            <tr><th>Character</th><th>Spell</th><th>Casting Time</th><th>Running Time</th></tr>
            <?php
                foreach($all_active_spells AS $active_spell) {
                    if($active_spell['casting_time_remaining'] > 0) {
                        $spell_css_class = 'spell_slot_casting';
                    } else {
                        $spell_css_class = 'spell_slot_running';
                    }
                    echo '<tr><td>' . $active_spell['character_name'] . '</td><td class="' . $spell_css_class . '">' . $active_spell['spell_name'] . '</td><td class="' . $spell_css_class . '">' . $active_spell['casting_time_remaining'] . '</td><td class="' . $spell_css_class . '">' . $active_spell['running_time_remaining'] . '</td></tr>' . PHP_EOL;
                }
            ?>
            <tr><td colspan="4" style="text-align: center;"><button name="dmAction" value="<?= REFRESHSPELLLIST ?>" type="submit">Refresh List</button></td></tr>
        </table>
        <div style="padding-top: 10px;"><button name="dmAction" value="<?= DAILYRESET ?>" type="submit">Daily Reset</button></div>
    </form>
</body>
</html>

<?php

function getPlayerPermissions(\PDO $pdo, $player_name, &$errors) {
	$sql_exec = "CALL getPlayerPermissions(:playerName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getPlayerPermissions : " . $e->getMessage();
	}

	return $statement->fetch(PDO::FETCH_ASSOC);
}

function getActiveSpells($pdo, &$errors) {
	$sql_exec = "CALL getActiveSpells()";

	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getActiveSpells : " . $e->getMessage();
	}

	return $statement->fetchALL(PDO::FETCH_ASSOC);
}

function resetDailyForSlots($pdo, &$errors) {
	$sql_exec = "CALL resetDailyForSlots()";

	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in resetDailyForSlots : " . $e->getMessage();
	}
}

function updateForEndOfRoundForSlots($pdo, &$errors) {
	$sql_exec = "CALL updateForEndOfRoundForSlots()";

	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in updateForEndOfRoundForSlots : " . $e->getMessage();
	}
}
?>
