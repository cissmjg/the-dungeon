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

require_once __DIR__ . '/classes/characterDetails.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/characterActionRoutes.php';
require_once __DIR__ . '/helper/SqlExecHelper.php';

require_once __DIR__ . '/classes/characterDetails.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';

require_once __DIR__ . '/fa/faNewIcon.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/textInput.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';

getPlayerName($errors, $input);
getCharacterName($errors, $input);

// Get raw text from web page
getTextInput($errors, $input);

$character_details = new CharacterDetails();
$character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->renderCharacterDetails($character_details);

$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$filtered_text = SqlExecHelper::filterSqlVerbs($input[TEXT_INPUT]);

// Get Weapons matching the pattern
$available_weapon_proficiencies = getWeaponProficienciesAvailableForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $filtered_text, $errors);

$form_id = "addWeaponProficiency";
$weapon_proficiency_element_id = $form_id . '-' . WEAPON_PROFICIENCY_ID;


$page_title = 'Browse Weapon Proficiencies';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'browsePlayerCharacterWeaponProficiencies.js';
$page_specific_css = 'browsePlayerCharacterWeaponProficiencies.css';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
    <form id="<?= $form_id ?>" name="<?= $form_id ?>" method="POST" action="<?= CurlHelper::buildUrlDbioDirectory('addWeaponProficiencyToPlayerCharacter') ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= WEAPON_PROFICIENCY_ID ?>" id="<?= $weapon_proficiency_element_id ?>" value="">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Select Weapon</div>
    <div style="text-align: center;">
        <form name="selectWeapon" id="selectWeapon" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
            <label for="weaponNamePattern">Weapon Name</label><br>
            <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_BROWSE_PLAYER_CHARACTER_WEAPON_PROFICIENCIES ?>"> 
            <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
            <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
            <input type="text" name="<?= TEXT_INPUT ?>" maxlength="32"><button type="submit"><span class="fa-solid fa-magnifying-glass"></span></button><br>
        </form>
    </div>
    <?php if (empty($available_weapon_proficiencies)): ?>
    <h3 style="text-align:center;">Please enter a weapon name to begin</h3>
    <?php else: ?>
        <h3>Weapon proficiencies available for <?= $input[CHARACTER_NAME] ?></h3>
    <table>
        <tr><th>&nbsp;</th><th>Weapon Name</th></tr>
    <?php
        foreach($available_weapon_proficiencies AS $available_weapon_proficiency) {
            $weapon_proficiency_id = $available_weapon_proficiency['weapon_proficiency_id'];
            $weapon_proficiency_name = $available_weapon_proficiency['weapon_proficiency_name'];
            echo '<tr>';
            $add_weapon_proficiency_icon =  buildAddPlayerCharacterWeaponProficiencyIcon($form_id, $weapon_proficiency_element_id, $weapon_proficiency_id);
            echo '<td>' . $add_weapon_proficiency_icon . '</td>';
            echo '<td>' . $weapon_proficiency_name . '</td>';
            echo '</tr>' . PHP_EOL;
        }
    ?>
    </table>
    <?php endif ?>
</body>
</html>

<?php
function getWeaponProficienciesAvailableForPlayerCharacter(\PDO $pdo, $player_name, $character_name, $input_text, &$errors) {
	$sql_exec = "CALL getWeaponProficienciesAvailableForPlayerCharacter(:playerName, :characterName, :weaponPatternName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':weaponPatternName', $input_text, PDO::PARAM_STR);

	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponProficienciesAvailableForPlayerCharacter : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildActionBar($player_name, $character_name) {
    $output_html  = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
    $output_html .= "&nbsp;";
    $output_html .= ActionBarHelper::buildEditPlayerCharacterWeaponProficienciesIcon($player_name, $character_name);
    $output_html .= "&nbsp;";

    return $output_html;
}

function buildAddPlayerCharacterWeaponProficiencyIcon($form_id, $weapon_proficiency_element_Id, $weapon_proficiency_Id) {
    $new_icon = new FaNewIcon();
    $new_icon->setOnClickJsFunction('submitAddWeaponProficiencyForm');
    $new_icon->addOnclickJsParameter($form_id);
    $new_icon->addOnclickJsParameter($weapon_proficiency_element_Id);
    $new_icon->addOnclickJsParameter($weapon_proficiency_Id);
    $new_icon->addStyle("padding-right: 10px;");
    $new_icon->addStyle("padding-left: 5px;");

    return $new_icon->build();
}

?>