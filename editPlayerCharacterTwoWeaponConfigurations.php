<?php

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';

require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/characterActionRoutes.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/classes/characterDetails.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/dbio/constants/skills.php';
require_once __DIR__ . '/webio/craftStatus.php';

require_once __DIR__ . '/fa/faDeleteIcon.php';
require_once __DIR__ . '/fa/faAddIcon.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/twoWeaponConfigurationId.php';
require_once __DIR__ . '/webio/playerCharacterWeaponId.php';
require_once __DIR__ . '/webio/playerCharacterWeapon2Id.php';

require_once __DIR__ . '/classes/twoWeaponFightingConfiguration.php';
require_once __DIR__ . '/classes/twoWeaponFightingConfigurationSet.php';

$errors = [];
$input = [];
$log = [];

const SELECT_WEAPON_PROMPT = "[Select a weapon]";

$delete_form_id = "deleteTwoWeaponConfig";
$delete_two_weapon_config_element_id = $delete_form_id . '-' . TWO_WEAPON_CONFIGURATION_ID;

$add_form_id = "addTwoWeaponConfig";
$add_weapon1_element_id = $add_form_id . '-' . PLAYER_CHARACTER_WEAPON_ID;
$add_weapon2_element_id = $add_form_id . '-' . PLAYER_CHARACTER_WEAPON2_ID;

getPlayerName($errors, $input);
getCharacterName($errors, $input);

$existing_two_weapon_configs = getPlayerCharacterTwoWeaponConfigurations($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$character_details = new CharacterDetails();
$character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

// Since a character can only take Two Weapon Fighting once, it is safe to look at the first element in the array
$two_weapon_fighting = $player_character_skill_set->getAllSkillInstances(TWO_WEAPON_FIGHTING)[0];

$mainhand_weapon_list = buildWeaponListOptions($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $two_weapon_fighting->getWeaponProficiencyId(), $errors);
$offhand_weapon_list =  buildWeaponListOptions($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $two_weapon_fighting->getWeapon2ProficiencyId(), $errors);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->renderCharacterDetails($character_details);

$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$page_title = 'Setup 2 weapon fighting';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editPlayerCharacterTwoWeaponConfigurations.js';
$page_specific_css = '';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;
?>
<body>
<form name="<?= $delete_form_id ?>" id="<?= $delete_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
    <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_DELETE_TWO_WEAPON_CONFIG ?>">
    <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
    <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
    <input type="hidden" name="<?= TWO_WEAPON_CONFIGURATION_ID ?>" id="<?= $delete_two_weapon_config_element_id ?>" value="">
</form>
<form id="<?= $add_form_id ?>" name="<?= $add_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
    <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_ADD_TWO_WEAPON_CONFIG ?>">
    <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
    <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
    <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_ID ?>" id="<?= $add_weapon1_element_id ?>" value="0">
    <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON2_ID ?>" id="<?= $add_weapon2_element_id ?>" value="0">
</form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
<?php if (!empty($existing_two_weapon_configs)): ?>
    <h3>Existing Two Weapon Configurations</h3>
    <table>
        <tr><th>&nbsp;</th><th>Mainhand Weapon</th><th>Offhand Weapon</th></tr>
        <?php
        foreach($existing_two_weapon_configs AS $existing_two_weapon_config) {
            echo '<tr>';
            echo '<td>' . buildDeleteTwoWeaponConfigIcon($delete_form_id, $delete_two_weapon_config_element_id, $existing_two_weapon_config->getTwoWeaponConfigurationId()) . '</td>';
            echo '<td style="text-align:center;">' . $existing_two_weapon_config->getWeapon1Description() . ' ' . $existing_two_weapon_config->getWeapon1Location() . '</td>';
            echo '<td style="text-align:center;">' . $existing_two_weapon_config->getWeapon2Description() . ' ' . $existing_two_weapon_config->getWeapon2Location() . '</td>';
            echo '</tr>' . PHP_EOL;
        }
        ?>
    </table>
<?php else: ?>
    <h3>No existing Two Weapon Configurations available</h3>
<?php endif ?>
    <h3>Add new Two Weapon Configurations</h3>
    <table>
        <tr><th>&nbsp;</th><th>Mainhand weapon</th><th>Offhand weapon</th></tr>
        <tr>
            <td><?= buildAddTwoWeaponConfigIcon($add_form_id, $add_weapon1_element_id, $add_weapon2_element_id); ?> </td>
            <td>
            <select id="mainhand-weaponlist" onchange="updateWeaponId('mainhand-weaponlist', '<?= $add_weapon1_element_id ?>');">
                <?= $mainhand_weapon_list ?>
            </select>
            </td>
            <td>
            <select id="offhand-weaponlist" onchange="updateWeaponId('offhand-weaponlist', '<?= $add_weapon2_element_id ?>');">
                <?= $offhand_weapon_list ?>
            </select>
            </td>
        </tr>
    </table>
</body>
</html>

<?php
function getPlayerCharacterTwoWeaponConfigurations(\PDO $pdo, $player_name, $character_name, &$errors) {
    $two_weapon_configurations = new TwoWeaponFightingConfigurationSet();
    $two_weapon_configurations->init($pdo, $player_name, $character_name, $errors);

    return $two_weapon_configurations->getAll();
}

function buildWeaponListOptions(\PDO $pdo, $player_name, $chracter_name, $weapon_proficiency_id, &$errors) {
    $weapon_list = getWeaponsForPlayerCharacterByProficiency($pdo, $player_name, $chracter_name, $weapon_proficiency_id, $errors);
    if (count($errors) > 0) {
        die(json_encode($errors));
    }

    $weapon_list_options = '<option value="0">' . SELECT_WEAPON_PROMPT . '</option>' . PHP_EOL;
    foreach($weapon_list AS $weapon) {
        $desc = $weapon['player_character_weapon_description'];
        if (!empty($weapon['player_character_weapon_location'])) {
            $desc .= '-' . $weapon['player_character_weapon_location'];
        }

        if (!empty($weapon['player_character_weapon_craft_status'])) {
            if ($weapon['player_character_weapon_craft_status'] != CRAFT_STATUS_ARTISAN) {
                $desc .= '-(' . getCraftStatusDescription($weapon['player_character_weapon_craft_status']) . ')';
            }
        }
        
        $weapon_list_options .= '<option value="' . $weapon['player_character_weapon_id'] . '">' . $desc . '</option>' . PHP_EOL;
    }

    return $weapon_list_options;
}

function getWeaponsForPlayerCharacterByProficiency(\PDO $pdo, $player_name, $character_name, $weapon_proficiency_id, &$errors) {
	$sql_exec = "CALL getWeaponsForPlayerCharacterByProficiency(:playerName, :characterName, :weaponProficiencyId)";

    $statement = $pdo->prepare($sql_exec);
    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    $statement->bindParam(':weaponProficiencyId', $weapon_proficiency_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponsForPlayerCharacterByProficiency : " . $e->getMessage();
	}

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildDeleteTwoWeaponConfigIcon($delete_form_id, $delete_two_weapon_config_element_id, $two_weapon_config_id) {
    $delete_icon = new FaDeleteIcon();
    $delete_icon->setOnClickJsFunction('submitDeleteTwoWeaponConfigForm');
    $delete_icon->addOnclickJsParameter($delete_form_id);
    $delete_icon->addOnclickJsParameter($delete_two_weapon_config_element_id);
    $delete_icon->addOnclickJsParameter($two_weapon_config_id);
    $delete_icon->setHoverText("Delete Config");

    return $delete_icon->build();
}

function buildAddTwoWeaponConfigIcon($add_form_id, $weapon1_element_id, $weapon2_element_id) {
    $add_icon = new FaAddIcon();
    $add_icon->setOnClickJsFunction('submitAddTwoWeaponConfigForm');
    $add_icon->addOnclickJsParameter($add_form_id);
    $add_icon->addOnclickJsParameter($weapon1_element_id);
    $add_icon->addOnclickJsParameter($weapon2_element_id);

    return $add_icon->build();
}

function buildActionBar($player_name, $character_name) {
    $output_html  = '';
    $output_html .= ActionBarHelper::buildUserViewIcon($player_name, $character_name);
    $output_html .= '&nbsp;';
    $output_html .= ActionBarHelper::buildEditWeaponsIcon($player_name, $character_name);
    $output_html .= '&nbsp;';

    return $output_html;
}
?>