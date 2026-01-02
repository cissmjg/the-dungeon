<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once 'characterName.php';
require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'craftStatus.php';
require_once 'faDeleteIcon.php';

require_once 'playerName.php';
require_once 'characterName.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input['playerName'], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = ActionBarHelper::buildActionBar($input['playerName'], $input[CHARACTER_NAME]);

$weapon_list = getWeaponSummaryForPlayerCharacter($pdo, $input['playerName'], $input[CHARACTER_NAME], $errors);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $input[CHARACTER_NAME] ?> Weapons</title>
	<link rel="stylesheet" href="dnd-default.css">
    <link rel="stylesheet" href="characterSheet.css">
    <script src="../js/jquery-1.12.4.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="playerCharacterWeaponMain.js" type="module"></script>
    <script src="characterSheetContainer.js"></script>
    <script type="module">
        import { populateWeaponList, weaponListChanged, confirmPlayerCharacterWeaponDelete } from './playerCharacterWeaponMain.js';

        // Attach to global scope
        window.populateWeaponList = populateWeaponList;
        window.weaponListChanged = weaponListChanged;
        window.confirmPlayerCharacterWeaponDelete = confirmPlayerCharacterWeaponDelete;
    </script>
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
    <script src="submitTheForm.js"></script>
    <style>
        label {
            color: lightgray;
            font-size: 14px;
            vertical-align: sub;
        }
    </style>
</head>
<body>
    <form name="deleteWeapon" id="deleteWeapon" method="POST" action="<?= CurlHelper::buildUrl('characterActionRouter') ?>">
        <input type="hidden" name="characterAction" value="deletePlayerCharacterWeapon">
        <input type="hidden" name="playerName" value="<?= $input['playerName'] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="playerCharacterWeaponId" id="playerCharacterWeaponId" value="">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div class="characterSheetFeature">
        <a href="#">
            <i class="fa fa-plus"></i> Add a weapon
        </a>
        <div class="characterSheetFeatureContent">
            <div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Select Weapon</div>
            <div style="text-align: center;">
                <form name="selectWeapon" id="selectWeapon" method="POST" action="<?= CurlHelper::buildUrl('addPlayerCharacterWeapon') ?>">
                    <label for="weaponNamePattern">Weapon Name</label><br>
                    <input type="hidden" name="playerName" value="<?= $input['playerName'] ?>">
                    <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
                    <input type="text" id="weaponNamePattern" maxlength="32"><button type="button" onclick="populateWeaponList('weaponProficiencyId', 'weaponNamePattern');"><span class="fa-solid fa-magnifying-glass"></span></button><br>
                    <select name="weaponProficiencyId" id="weaponProficiencyId" onchange="weaponListChanged('selectWeaponButton', 'weaponProficiencyId');" hidden>
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
            <tr><th>&nbsp;</th><th>Description</th><th>Location</th><th>Craft Status</th></tr>
            <?php
                foreach($weapon_list AS $weapon) {
                    $output_row  = '<tr>';
                    $output_row .= '<td>' . buildDeletePlayerCharacterWeaponIcon($weapon['weapon_description'], $weapon['player_character_weapon_id']) . '</td>';
                    $output_row .= '<td>' . buildWeaponNameCell($input['playerName'], $input[CHARACTER_NAME], $weapon) . '</td>';
                    $output_row .= '<td>' . $weapon['weapon_location'] . '</td>';
                    $output_row .= '<td>' . getCraftStatusDescription($weapon['weapon_craft_status']) . '</td>';
                    $output_row .= '</tr>' . PHP_EOL;
                    echo $output_row;
                }
            ?>
        </table>
    <?php endif ?>
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

function buildDeletePlayerCharacterWeaponIcon($weapon_desc, $player_character_weapon_id) {
    $delete_icon = new FaDeleteIcon();
    $delete_icon->setOnClickJsFunction('confirmPlayerCharacterWeaponDelete');
    $delete_icon->addOnclickJsParameter('deleteWeapon');
    $delete_icon->addOnclickJsParameter('playerCharacterWeaponId');
    $delete_icon->addOnclickJsParameter($player_character_weapon_id);
    $delete_icon->addOnclickJsParameter($weapon_desc);
    $delete_icon->setHoverText('Delete ' . $weapon_desc);

    return $delete_icon->build();
}

?>
