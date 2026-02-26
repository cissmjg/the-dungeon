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
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';

require_once __DIR__ . '/fa/faDeleteIcon.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/playerCharacterWeaponSkillId.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = ActionBarHelper::buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$weapon_proficiency_list = getWeaponProficienciesForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$form_id = 'deleteWeaponProficiency';

$page_title = $input[CHARACTER_NAME] . ' Weapon Proficiencies';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editPlayerCharacterWeaponProficiencies.js';
$page_specific_css = 'editPlayerCharacterWeaponProficiencies.css';
$enable_toggle_panels = true;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
    <form name="<?= $form_id ?>" id="<?= $form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_DELETE_PLAYER_CHARACTER_WEAPON_PROFICIENCY ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" id="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" value="">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div class="togglePanel">
        <a href="#">
            <span class="fa fa-plus"></span> Add a weapon proficiency
        </a>
        <div class="togglePanelContent">
            <div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Select Weapon</div>
            <div style="text-align: center;">
                <form name="selectWeapon" id="selectWeapon" method="POST" action="<?= CurlHelper::buildUrl('addWeaponProficiencyToPlayerCharacter') ?>">
                    <label for="weaponNamePattern">Weapon Name</label><br>
                    <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
                    <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
                    <input type="text" id="weaponNamePattern" maxlength="32"><button type="button" onclick="populateWeaponList('<?= WEAPON_PROFICIENCY_ID ?>', '<?= $input[PLAYER_NAME] ?>', '<?= $input[CHARACTER_NAME] ?>', 'weaponNamePattern');"><span class="fa-solid fa-magnifying-glass"></span></button><br>
                    <select name="<?= WEAPON_PROFICIENCY_ID?>" id="<?= WEAPON_PROFICIENCY_ID?>" onchange="weaponListChanged('selectWeaponButton', '<?= WEAPON_PROFICIENCY_ID ?>');" hidden>
                    </select>
                    <br><br>
                    <button id="selectWeaponButton" type="submit" hidden>Add Weapon Proficiency</button>
                </form>
            </div>
        </div>
    </div>
    <h3>Weapon List</h3>
    <?php if (count($weapon_proficiency_list) == 0): ?>
        <span style="font-size: 18px;">No weapons available</span>
    <?php else: ?>
        <table>
            <tr><th>&nbsp;</th><th>Description</th><th>Location</th><th>Craft Status</th></tr>
            <?php
                foreach($weapon_proficiency_list AS $weapon_proficiency) {
                    $weapon_desc = str_replace("'", "", html_entity_decode($weapon_proficiency['weapon_proficiency_description']));
                    $output_row  = '<tr>';
                    $output_row .= '<td>' . buildDeletePlayerCharacterWeaponProficiencyIcon($form_id, $weapon_desc, $weapon_proficiency['player_weapon_proficiency_id']) . '</td>';
                    $output_row .= '<td>' . buildWeaponNameCell($input[PLAYER_NAME], $input[CHARACTER_NAME], $weapon_proficiency) . '</td>';
                    $output_row .= '<td>' . $weapon_proficiency['weapon_location'] . '</td>';
                    $output_row .= '<td>' . getCraftStatusDescription($weapon_proficiency['weapon_craft_status']) . '</td>';
                    $output_row .= '</tr>' . PHP_EOL;
                    echo $output_row;
                }
                //player_weapon_proficiency_id
                //weapon_proficiency_description
                //weapon_proficiency_id
            ?>
        </table>
    <?php endif ?>
<div>
</body>
</html>

<?php
function getWeaponProficienciesForPlayerCharacter(\PDO $pdo, $player_name, $character_name, &$errors) {
    $sql_exec = "CALL getWeaponProficienciesForPlayerCharacter(:playerName, :characterName)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponProficienciesForPlayerCharacter : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildWeaponNameCell($player_name, $character_name, $weapon_proficiency) {
    $weapon_proficiency_desc = $weapon_proficiency['weapon_proficiency_description'];
    $player_character_weapon_proficiency_id = $weapon_proficiency['player_weapon_proficiency_id'];
    $output_html  = $weapon_proficiency_desc;
    $output_html .= '<span style="float: right; margin-left: 15px;">';
    $output_html .= ActionBarHelper::buildEditPlayerCharacterWeaponTalentIcon($player_name, $character_name, $player_character_weapon_proficiency_id);
    $output_html .= '</span>';

    return $output_html;
}

function buildDeletePlayerCharacterWeaponProficiencyIcon($form_id, $weapon_desc, $player_character_weapon_proficiency_id) {
    $delete_icon = new FaDeleteIcon();
    $delete_icon->setOnClickJsFunction('confirmPlayerCharacterWeaponProficiencyDelete');
    $delete_icon->addOnclickJsParameter($form_id);
    $delete_icon->addOnclickJsParameter(PLAYER_CHARACTER_WEAPON_SKILL_ID);
    $delete_icon->addOnclickJsParameter($player_character_weapon_proficiency_id);
    $delete_icon->addOnclickJsParameter($weapon_desc);
    $delete_icon->setHoverText('Delete ' . $weapon_desc);

    return $delete_icon->build();
}

?>
