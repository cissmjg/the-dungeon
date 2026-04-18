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
require_once __DIR__ . '/helper/SqlExecHelper.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/characterActionRoutes.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/textInput.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';
require_once __DIR__ . '/webio/characterAction.php';

require_once __DIR__ . '/fa/faNewIcon.php';

require_once __DIR__ . '/classes/characterSummary.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';

getPlayerName($errors, $input);
getCharacterName($errors, $input);
getTextInput($errors, $input);

$filtered_weapon_pattern = SqlExecHelper::filterSqlVerbs($input[TEXT_INPUT]);

$available_weapons = getWeaponProficiencyByPattern($pdo, $filtered_weapon_pattern, $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

//		name AS weapon_proficiency_name, 
//		id AS weapon_proficiency_id

$form_id = "addWeapon";
$weapon_element_id = $form_id . '-' . WEAPON_PROFICIENCY_ID;


$page_title = 'Browse Weapons';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'browsePlayerCharacterWeapons.js';
$page_specific_css = 'browsePlayerCharacterWeapons.css';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;
?>
<body>
    <form id="<?= $form_id ?>" name="<?= $form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_ADD_PLAYER_CHARACTER_WEAPON ?>"> 
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= WEAPON_PROFICIENCY_ID ?>" id="<?= $weapon_element_id ?>" value="">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Select Weapon</div>
    <div style="text-align: center;">
        <form name="selectWeapon" id="selectWeapon" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
            <label for="weaponNamePattern">Weapon Name</label><br>
            <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_BROWSE_PLAYER_CHARACTER_WEAPONS ?>"> 
            <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
            <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
            <input type="text" name="<?= TEXT_INPUT ?>" maxlength="32"><button type="submit"><span class="fa-solid fa-magnifying-glass"></span></button><br>
        </form>
    </div>
    <?php if (empty($available_weapons)): ?>
    <h3 style="text-align:center;">Please enter a weapon name to begin</h3>
    <?php else: ?>
        <h3>Weapons available for <?= $input[CHARACTER_NAME] ?></h3>
    <table>
        <tr><th>&nbsp;</th><th>Weapon Name</th></tr>
        <?php
            foreach($available_weapons AS $available_weapon) {
                echo '<tr>';
                echo '<td>' . buildAddWeaponIcon($form_id, $weapon_element_id, $available_weapon['weapon_proficiency_id']) . '</td>';
                echo '<td>' . $available_weapon['weapon_proficiency_name'] . '</td>';
                echo '<tr>' . PHP_EOL;
            }
        ?>
    </table>
    <?php endif ?>
</body>
</html>

<?php
function getWeaponProficiencyByPattern(\PDO $pdo, $input_text, &$errors) {
	$sql_exec = "CALL getWeaponProficiencyByPattern(:weaponPatternName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':weaponPatternName', $input_text, PDO::PARAM_STR);

	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponProficiencyByPattern : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildActionBar($player_name, $character_name) {
    $output_html  = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
    $output_html .= '&nbsp;';
    $output_html .= ActionBarHelper::buildEditWeaponsIcon($player_name, $character_name);
    $output_html .= '&nbsp;';

    return $output_html;
}

function buildAddWeaponIcon($form_id, $weapon_proficiency_element_id, $weapon_proficiency_id) {
    $new_icon = new FaNewIcon();
    $new_icon->setOnClickJsFunction('submitAddWeaponForm');
    $new_icon->addOnclickJsParameter($form_id);
    $new_icon->addOnclickJsParameter($weapon_proficiency_element_id);
    $new_icon->addOnclickJsParameter($weapon_proficiency_id);

    return $new_icon->build();
}
?>
