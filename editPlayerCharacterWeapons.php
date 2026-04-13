<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/classes/characterSummary.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/webio/craftStatus.php';
require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/dbio/constants/skills.php';

require_once __DIR__ . '/fa/faDeleteIcon.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/playerCharacterWeaponId.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = ActionBarHelper::buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$weapon_list = getWeaponSummaryForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$form_id = 'deleteWeapon';

$page_title = $input[CHARACTER_NAME] . ' Weapons';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editPlayerCharacterWeapons.js';
$page_specific_css = 'editPlayerCharacterWeapons.css';
$enable_toggle_panels = true;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
    <form name="<?= $form_id ?>" id="<?= $form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_DELETE_PLAYER_CHARACTER_WEAPON ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_ID ?>" id="<?= PLAYER_CHARACTER_WEAPON_ID ?>" value="">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div>&nbsp;</div>
    <div class="togglePanel">
        <a href="#">
            <span class="fa fa-plus"></span> Add a weapon
        </a>
        <div class="togglePanelContent">
            <div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Select Weapon</div>
            <div style="text-align: center;">
                <form name="selectWeapon" id="selectWeapon" method="POST" action="<?= CurlHelper::buildUrl('addPlayerCharacterWeapon') ?>">
                    <label for="weaponNamePattern">Weapon Name</label><br>
                    <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
                    <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
                    <input type="text" id="weaponNamePattern" maxlength="32"><button type="button" onclick="populateWeaponList('<?= WEAPON_PROFICIENCY_ID ?>', 'weaponNamePattern');"><span class="fa-solid fa-magnifying-glass"></span></button><br>
                    <select name="<?= WEAPON_PROFICIENCY_ID?>" id="<?= WEAPON_PROFICIENCY_ID?>" onchange="weaponListChanged('selectWeaponButton', '<?= WEAPON_PROFICIENCY_ID ?>');" hidden>
                    </select>
                    <br><br>
                    <button id="selectWeaponButton" type="submit" hidden>Weapon Details &gt; &gt;</button>
                </form>
            </div>
        </div>
    </div>
    <h3>Weapon List</h3>
    <?php if (count($weapon_list) == 0): ?>
        <span style="font-size: 18px;">No weapons available</span>
    <?php else: ?>
        <table>
            <tr><th>&nbsp;</th><th>Description</th><th>Location</th><th>Proficient</th></th><th>Craft Status</th></tr>
            <?php
                foreach($weapon_list AS $weapon) {
                    $weapon_desc = str_replace("'", "", html_entity_decode($weapon['weapon_description']));
                    $weapon_proficiency_id = $weapon['weapon_proficiency_id'];
                    $is_proficient = $player_character_skill_set->isProficientWithWeapon($weapon_proficiency_id) ? "Yes" : "No";
                    $output_row  = '<tr>';
                    $output_row .= '<td>' . buildDeletePlayerCharacterWeaponIcon($form_id, $weapon_desc, $weapon['player_character_weapon_id']) . '</td>';
                    $output_row .= '<td>' . buildWeaponNameCell($input[PLAYER_NAME], $input[CHARACTER_NAME], $weapon) . '</td>';
                    $output_row .= '<td>' . $weapon['weapon_location'] . '</td>';
                    $output_row .= '<td>' . $is_proficient . '</td>';
                    $output_row .= '<td>' . getCraftStatusDescription($weapon['weapon_craft_status']) . '</td>';
                    $output_row .= '</tr>' . PHP_EOL;
                    echo $output_row;
                }
            ?>
        </table>
    <?php endif ?>
    <?php
        echo '<div>&nbsp;</div>' . PHP_EOL;
        if (count($player_character_skill_set->getAllSkillInstances(TWO_WEAPON_FIGHTING)) > 0) {
            $url = CurlHelper::buildUrl('editPlayerCharacterTwoWeaponConfigurations');
            $url = CurlHelper::addParameter($url, PLAYER_NAME, $input[PLAYER_NAME]);
            $url = CurlHelper::addParameter($url, CHARACTER_NAME, $input[CHARACTER_NAME]);
            echo '<div>' . PHP_EOL;
            echo '    <a style="padding-left: 5px;" href="' . $url . '"><span class="fa-solid fa-gear" style="cursor: pointer; color: SeaGreen;"></span> Set up two weapon fighting configurations</a>' . PHP_EOL; 
            echo '</div>' . PHP_EOL;
        }
    ?>
</body>
</html>

<?php
function getWeaponSummaryForPlayerCharacter(\PDO $pdo, $player_name, $character_name, &$errors) {
    $sql_exec = "CALL getWeaponSummaryForPlayerCharacter(:playerName, :characterName)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponSummaryForPlayerCharacter : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildWeaponNameCell($player_name, $character_name, $weapon) {
    $weapon_desc = $weapon['weapon_description'];
    $player_character_weapon_id = $weapon['player_character_weapon_id'];
    $output_html  = $weapon_desc;
    $output_html .= '<span style="float: right; margin-left: 15px;">';
    $output_html .= ActionBarHelper::buildEditPlayerCharacterWeapon($player_name, $character_name, $player_character_weapon_id);
    $output_html .= '</span>';

    return $output_html;
}

function buildDeletePlayerCharacterWeaponIcon($form_id, $weapon_desc, $player_character_weapon_id) {
    $delete_icon = new FaDeleteIcon();
    $delete_icon->setOnClickJsFunction('confirmPlayerCharacterWeaponDelete');
    $delete_icon->addOnclickJsParameter($form_id);
    $delete_icon->addOnclickJsParameter(PLAYER_CHARACTER_WEAPON_ID);
    $delete_icon->addOnclickJsParameter($player_character_weapon_id);
    $delete_icon->addOnclickJsParameter(str_replace("'", "", $weapon_desc));
    $delete_icon->setHoverText('Delete ' . $weapon_desc);

    return $delete_icon->build();
}

?>
