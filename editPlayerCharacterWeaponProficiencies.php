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
require_once __DIR__ . '/webio/skillCatalogId.php';
require_once __DIR__ . '/webio/isSkillFocus.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';
require_once __DIR__ . '/webio/weapon2ProficiencyId.php';

require_once __DIR__ . '/fa/faDeleteIcon.php';

require_once __DIR__ . '/classes/skillCatalog.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/classes/characterDetails.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/playerCharacterWeaponSkillId.php';
require_once __DIR__ . '/webio/playerCharacterSkillId.php';

require_once __DIR__ . '/classes/skills/brutalThrow.php';
require_once __DIR__ . '/classes/skills/powerAttack.php';
require_once __DIR__ . '/classes/skills/powerThrow.php';
require_once __DIR__ . '/classes/skills/weaponFinesse.php';
require_once __DIR__ . '/classes/skills/zenArchery.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

// Debug section
$debug_output = '';
$debug_skills = true;

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = ActionBarHelper::buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$weapon_proficiency_list = getWeaponProficienciesForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$delete_weapon_proficiency_form_id = 'deleteWeaponProficiency';

$delete_attribute_weapon_skill_form_id = 'deleteAttributeWeaponSkill';
$delete_attribute_weapon_skill_id = $delete_attribute_weapon_skill_form_id . '-' . SKILL_CATALOG_ID;

$add_attribute_weapon_skill_form_id = 'addAttributeWeaponSkill';
$add_attribute_weapon_skill_element_id = $add_attribute_weapon_skill_form_id . '-' . SKILL_CATALOG_ID;

$select_weapon_form_id = "selectWeapon";
$select_weapon_element_id = $select_weapon_form_id . '-' . WEAPON_PROFICIENCY_ID;

$form_id_lookup = new FormIdLookup($delete_attribute_weapon_skill_form_id, $delete_attribute_weapon_skill_id, $add_attribute_weapon_skill_form_id, $add_attribute_weapon_skill_element_id, WEAPON_PROFICIENCY_ID, WEAPON2_PROFICIENCY_ID);

$the_skill_catalog = new SkillCatalog();
$the_skill_catalog->init($pdo, $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$character_details = new CharacterDetails();
$character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$page_title = $input[CHARACTER_NAME] . ' Weapon Proficiencies';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editPlayerCharacterWeaponProficiencies.js';
$page_specific_css = 'editPlayerCharacterWeaponProficiencies.css';
$enable_toggle_panels = true;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
    <form name="<?= $delete_weapon_proficiency_form_id ?>" id="<?= $delete_weapon_proficiency_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_DELETE_PLAYER_CHARACTER_WEAPON_PROFICIENCY ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" id="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" value="">
    </form>
    <form name="<?= $delete_attribute_weapon_skill_form_id ?>" id="<?= $delete_attribute_weapon_skill_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_DELETE_ATTRIBUTE_WEAPON_SKILL ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" id="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" value="">
        <input type="hidden" name="<?= PLAYER_CHARACTER_SKILL_ID ?>" id="<?= $delete_attribute_weapon_skill_id ?>" value="">
    </form>
    <form name="<?= $add_attribute_weapon_skill_form_id ?>" id="<?= $add_attribute_weapon_skill_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_ADD_ATTRIBUTE_WEAPON_SKILL ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" id="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" value="">
        <input type="hidden" name="<?= SKILL_CATALOG_ID ?>" id="<?= $add_attribute_weapon_skill_element_id ?>" value="">
        <input type="hidden" name="<?= IS_SKILL_FOCUS ?>" id="<?= IS_SKILL_FOCUS ?>" value="No">
        <input type="hidden" name="<?= WEAPON_PROFICIENCY_ID ?>" id="<?= WEAPON_PROFICIENCY_ID ?>" value="">
        <input type="hidden" name="<?= WEAPON2_PROFICIENCY_ID ?>" id="<?= WEAPON2_PROFICIENCY_ID ?>" value="">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div class="togglePanel">
        <a href="#">
            <span class="fa fa-plus"></span> Add a weapon proficiency
        </a>
        <div class="togglePanelContent">
            <div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Select Weapon</div>
            <div style="text-align: center;">
                <form name="<?= $select_weapon_form_id ?>" id="<?= $select_weapon_form_id ?>" method="POST" action="<?= CurlHelper::buildUrlDbioDirectory('addWeaponProficiencyToPlayerCharacter') ?>">
                    <label for="weaponNamePattern">Weapon Name</label><br>
                    <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
                    <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
                    <input type="text" id="weaponNamePattern" maxlength="32"><button type="button" onclick="populateWeaponList('<?= $select_weapon_element_id ?>', '<?= $input[PLAYER_NAME] ?>', '<?= $input[CHARACTER_NAME] ?>', 'weaponNamePattern');"><span class="fa-solid fa-magnifying-glass"></span></button><br>
                    <select name="<?= WEAPON_PROFICIENCY_ID ?>" id="<?= $select_weapon_element_id ?>" onchange="weaponListChanged('selectWeaponButton', '<?= $select_weapon_element_id ?>');" hidden>
                    </select>
                    <br><br>
                    <button id="selectWeaponButton" type="submit" hidden>Add Weapon Proficiency</button>
                </form>
            </div>
        </div>
    </div>
    <h3>Weapon Proficiencies</h3>
    <?php if (count($weapon_proficiency_list) == 0): ?>
        <span style="font-size: 18px;">No weapons available</span>
    <?php else: ?>
        <table>
            <tr><th>&nbsp;</th><th>Description</th></tr>
            <?php
                foreach($weapon_proficiency_list AS $weapon_proficiency) {
                    $weapon_desc = str_replace("'", "", html_entity_decode($weapon_proficiency['weapon_proficiency_description']));
                    $output_row  = '<tr>';
                    $output_row .= '<td>' . buildDeletePlayerCharacterWeaponProficiencyIcon($delete_weapon_proficiency_form_id, $weapon_desc, $weapon_proficiency['player_weapon_proficiency_id']) . '</td>';
                    $output_row .= '<td>' . buildWeaponNameCell($input[PLAYER_NAME], $input[CHARACTER_NAME], $weapon_proficiency) . '</td>';
                    $output_row .= '</tr>' . PHP_EOL;
                    echo $output_row;
                }
            ?>
        </table>
    <?php endif ?>
<div>&nbsp;</div>
    <h3>Attribute-based weapon talents</h3>
    <?php
    $brutal_throw = new BrutalThrow($the_skill_catalog, $form_id_lookup);
    echo $brutal_throw->render($character_details, $player_character_skill_set);

    $power_attack = new PowerAttack($the_skill_catalog, $form_id_lookup);
    echo $power_attack->render($character_details, $player_character_skill_set);

    $power_throw = new PowerThrow($the_skill_catalog, $form_id_lookup);
    echo $power_throw->render($character_details, $player_character_skill_set);

    $weapon_finesse = new WeaponFinesse($the_skill_catalog, $form_id_lookup);
    echo $weapon_finesse->render($character_details, $player_character_skill_set);

    $zen_archery = new ZenArchery($the_skill_catalog, $form_id_lookup);
    echo $zen_archery->render($character_details, $player_character_skill_set);

    if ($debug_skills) {
        $debug_output .= $brutal_throw->dump();
        $debug_output .= $power_attack->dump();
        $debug_output .= $power_throw->dump();
        $debug_output .= $weapon_finesse->dump();
        $debug_output .= $zen_archery->dump();
    }
    ?>
<?php if ($debug_skills): ?>
<div>&nbsp;</div>
<div class="togglePanel">
    <a href="#"><span class="fa fa-plus" style="padding-right: 5px;"></span></a><span class="toggleHeader">Qualifications</span>
    <div class="togglePanelContent tableHeader">
    <pre>
        <?= $debug_output ?>
    </pre>
    </div>
</div>
<?php endif ?>
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
