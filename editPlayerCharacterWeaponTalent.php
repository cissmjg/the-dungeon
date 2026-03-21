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
require_once __DIR__ . '/classes/skillCatalog.php';
require_once __DIR__ . '/classes/characterDetails.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/classes/skills/formIdLookup.php';
require_once __DIR__ . '/rules/clericsPreferredWeapons.php';
require_once __DIR__ . '/classes/weaponDetail.php';

require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/dbio/constants/weapons.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/playerCharacterWeaponSkillId.php';
require_once __DIR__ . '/webio/playerCharacterSkillId.php';
require_once __DIR__ . '/webio/playerCharacterWeaponTalentId.php';
require_once __DIR__ . '/webio/skillCatalogId.php';
require_once __DIR__ . '/webio/isSkillFocus.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';
require_once __DIR__ . '/webio/weapon2ProficiencyId.php';

require_once __DIR__ . '/classes/skills/dirtyFighting.php';
require_once __DIR__ . '/classes/skills/fistOfIron.php';
require_once __DIR__ . '/classes/skills/improvedUnarmedStrike.php';
require_once __DIR__ . '/classes/skills/jump.php';
require_once __DIR__ . '/classes/skills/mantisLeap.php';
require_once __DIR__ . '/classes/skills/clericsPreferredWeapon.php';
require_once __DIR__ . '/classes/skills/improvedCritical.php';
require_once __DIR__ . '/classes/skills/weaponFocusAccuracy.php';
require_once __DIR__ . '/classes/skills/weaponFocusGreaterAccuracy.php';
require_once __DIR__ . '/classes/skills/weaponFocusTechnique.php';
require_once __DIR__ . '/classes/skills/weaponFocusGreaterTechnique.php';
require_once __DIR__ . '/classes/skills/weaponSpecialization.php';
require_once __DIR__ . '/classes/skills/weaponDoubleSpecialization.php';
require_once __DIR__ . '/classes/skills/quickDraw.php';
require_once __DIR__ . '/classes/skills/twoWeaponFighting.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponSkillId($errors, $input);

// DEBUG SECTION
$debug_skills = true;
$debug_output = '';

$delete_form_id = 'deleteWeaponTalent';
$delete_weapon_talent_id = $delete_form_id . '-' . PLAYER_CHARACTER_WEAPON_SKILL_ID;

$add_form_id = 'addWeaponTalent';
$add_weapon_talent_form_id = $add_form_id . '-' . PLAYER_CHARACTER_WEAPON_SKILL_ID;

$form_id_lookup = new FormIdLookup($delete_form_id, $delete_weapon_talent_id, $add_form_id, $add_weapon_talent_form_id, WEAPON_PROFICIENCY_ID, WEAPON2_PROFICIENCY_ID);

$weapon_proficiency = getWeaponProficiencyName($pdo, $input[PLAYER_CHARACTER_WEAPON_SKILL_ID], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}
$current_weapon_proficiency_id = $weapon_proficiency['weapon_proficiency_id'];

$one_handed_weapon_proficiencies = getWeaponProficienciesOneHandedForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$offhand_weapon_id = '';
if (count($one_handed_weapon_proficiencies) == 1) {
    $offhand_weapon_id = $one_handed_weapon_proficiencies[0]['weapon_proficiency_id'];
}
 
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

$weapon_detail = new WeaponDetail();
$weapon_detail->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $current_weapon_proficiency_id, $errors);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->renderCharacterDetails($character_details);

$page_title = $input[CHARACTER_NAME] . ' Weapon Talents';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editPlayerCharacterWeaponTalent.js';
$page_specific_css = 'editPlayerCharacterWeaponTalent.css';
$enable_toggle_panels = true;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

?>
<body>
    <form name="<?= $delete_form_id ?>" id="<?= $delete_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_DELETE_WEAPON_TALENT ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" id="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" value="<?= $input[PLAYER_CHARACTER_WEAPON_SKILL_ID] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_SKILL_ID ?>" id="<?= $delete_weapon_talent_id ?>" value="">
    </form>
    <form name="<?= $add_form_id ?>" id="<?= $add_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_ADD_WEAPON_TALENT ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" id="<?= PLAYER_CHARACTER_WEAPON_SKILL_ID ?>" value="<?= $input[PLAYER_CHARACTER_WEAPON_SKILL_ID] ?>">
        <input type="hidden" name="<?= SKILL_CATALOG_ID ?>" id="<?= $add_weapon_talent_form_id ?>" value="">
        <input type="hidden" name="<?= IS_SKILL_FOCUS ?>" id="<?= IS_SKILL_FOCUS ?>" value="No">
        <input type="hidden" name="<?= WEAPON_PROFICIENCY_ID ?>" id="<?= WEAPON_PROFICIENCY_ID ?>" value="<?= $current_weapon_proficiency_id ?>">
        <input type="hidden" name="<?= WEAPON2_PROFICIENCY_ID ?>" id="<?= WEAPON2_PROFICIENCY_ID ?>" value="<?= $offhand_weapon_id ?>">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <h3>Skills for <?= $weapon_proficiency['weapon_proficiency_name'] ?></h3>
    <?php
        if ($current_weapon_proficiency_id == FIST) {
            $dirty_fighting = new DirtyFighting($the_skill_catalog, $form_id_lookup);
            $dirty_fighting->setWeaponProficiencyValue($current_weapon_proficiency_id);

            $fist_of_iron = new FistOfIron($the_skill_catalog, $form_id_lookup);
            $fist_of_iron->setWeaponProficiencyValue($current_weapon_proficiency_id);

            $mantis_leap = new MantisLeap($the_skill_catalog, $form_id_lookup);
            $mantis_leap->setWeaponProficiencyValue($current_weapon_proficiency_id);

            echo $dirty_fighting->render($character_details, $player_character_skill_set);
            echo $fist_of_iron->render($character_details, $player_character_skill_set);
            echo $mantis_leap->render($character_details, $player_character_skill_set);

            if ($debug_skills) {
                $debug_output .= $dirty_fighting->dump();
                $debug_output .= $fist_of_iron->dump();
                $debug_output .= $mantis_leap->dump();
            }
        } else {

            $quick_draw = new QuickDraw($the_skill_catalog, $form_id_lookup);
            $quick_draw->setWeaponProficiencyValue($current_weapon_proficiency_id);
            echo $quick_draw->render($character_details, $player_character_skill_set);

            $weapon_focus_accuracy = new WeaponFocusAccuracy($the_skill_catalog, $form_id_lookup);
            $weapon_focus_accuracy->setWeaponProficiencyValue($current_weapon_proficiency_id);
            echo $weapon_focus_accuracy->render($character_details, $player_character_skill_set);

            $weapon_focus_greater_accuracy = new WeaponFocusGreaterAccuracy($the_skill_catalog, $form_id_lookup);
            $weapon_focus_greater_accuracy->setWeaponProficiencyValue($current_weapon_proficiency_id);
            $weapon_focus_greater_accuracy->setWeaponDetail($weapon_detail);
            echo $weapon_focus_greater_accuracy->render($character_details, $player_character_skill_set);

            $weapon_focus_technique = new WeaponFocusTechnique($the_skill_catalog, $form_id_lookup);
            $weapon_focus_technique->setWeaponProficiencyValue($current_weapon_proficiency_id);
            echo $weapon_focus_technique->render($character_details, $player_character_skill_set);

            $weapon_focus_greater_technique = new WeaponFocusGreaterTechnique($the_skill_catalog, $form_id_lookup);
            $weapon_focus_greater_technique->setWeaponProficiencyValue($current_weapon_proficiency_id);
            $weapon_focus_greater_technique->setWeaponDetail($weapon_detail);
            echo $weapon_focus_greater_technique->render($character_details, $player_character_skill_set);

            $weapon_specialization = new WeaponSpecialization($the_skill_catalog, $form_id_lookup);
            $weapon_specialization->setWeaponProficiencyValue($current_weapon_proficiency_id);
            $weapon_specialization->setWeaponDetail($weapon_detail);
            echo $weapon_specialization->render($character_details, $player_character_skill_set);

            $double_weapon_specialization = new WeaponDoubleSpecialization($the_skill_catalog, $form_id_lookup);
            $double_weapon_specialization->setWeaponProficiencyValue($current_weapon_proficiency_id);
            $double_weapon_specialization->setWeaponDetail($weapon_detail);
            echo $double_weapon_specialization->render($character_details, $player_character_skill_set);

            $improved_critical = new ImprovedCritical($the_skill_catalog, $form_id_lookup);
            $improved_critical->setWeaponProficiencyValue($current_weapon_proficiency_id);
            $improved_critical->setWeaponDetail($weapon_detail);
            echo $improved_critical->render($character_details, $player_character_skill_set);

            $two_weapon_fighting = new TwoWeaponFighting($the_skill_catalog, $form_id_lookup);
            $two_weapon_fighting->setWeaponProficiencyValue($current_weapon_proficiency_id);
            $two_weapon_fighting->setWeaponDetail($weapon_detail);
            $two_weapon_fighting->setOneHandedWeapons($one_handed_weapon_proficiencies);
            echo $two_weapon_fighting->render($character_details, $player_character_skill_set);

            $cleric_preferred_weapon = new ClericsPreferredWeapon($the_skill_catalog, $form_id_lookup);
            $cleric_preferred_weapon->setWeaponProficiencyValue($current_weapon_proficiency_id);
            $cleric_preferred_weapon->setClericsPreferredWeapon($clerics_preferred_weapons);
            echo $cleric_preferred_weapon->render($character_details, $player_character_skill_set);

            if ($debug_skills) {
                $debug_output .= $quick_draw->dump();
                $debug_output .= $weapon_focus_accuracy->dump();
                $debug_output .= $weapon_focus_greater_accuracy->dump();
                $debug_output .= $weapon_focus_technique->dump();
                $debug_output .= $weapon_focus_greater_technique->dump();
                $debug_output .= $weapon_specialization->dump();
                $debug_output .= $double_weapon_specialization->dump();
                $debug_output .= $improved_critical->dump();
                $debug_output .= $two_weapon_fighting->dump();
                $debug_output .= $cleric_preferred_weapon->dump();
            }
        }
    ?>
    <?php
        if ($current_weapon_proficiency_id == FIST) {
            echo '<h3>Prerequisite Skills</h3>' . PHP_EOL;
            $improved_unarmed_strike = new ImprovedUnarmedStrike($the_skill_catalog, $form_id_lookup);
            echo $improved_unarmed_strike->render($character_details, $player_character_skill_set);
            $jump = new Jump($the_skill_catalog, $form_id_lookup);
            echo $jump->render($character_details, $player_character_skill_set);

            if ($debug_skills) {
                $debug_output .= $improved_unarmed_strike->dump();
                $debug_output .= $jump->dump();
            }
        }
    ?>
<div>&nbsp;</div>
<?php if ($debug_skills): ?>
<div class="togglePanel">
    <a href="#"><span class="fa fa-plus" style="padding-right: 5px;"></span></a><span class="toggleHeader">Qualifications</span>
    <div class="togglePanelContent tableHeader">
    <pre>
        <?php 
            $debug_output .= PHP_EOL . 'Count 1 handed proficiencies : ' . count($one_handed_weapon_proficiencies) . PHP_EOL;
echo $debug_output 
        ?>

    </pre>
    </div>
</div>
<?php endif ?>
</body>
</html>

<?php

function getWeaponProficiencyName(\PDO $pdo, $player_character_weapon_proficiency_id, &$errors) {
		$sql_exec = "CALL getWeaponProficiencyNameFromPlayerCharacterSkillId(:playerCharacterWeaponSkillId)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerCharacterWeaponSkillId', $player_character_weapon_proficiency_id, PDO::PARAM_INT);
        try {
    		$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getWeaponProficiencyName : " . $e->getMessage();
        } 

		return $statement->fetch(PDO::FETCH_ASSOC);
}

function getWeaponProficienciesOneHandedForPlayerCharacter(\PDO $pdo, $player_name, $character_name, &$errors) {
    $sql_exec = "CALL getWeaponProficienciesOneHandedForPlayerCharacter(:playerName, :characterName)";
    
    $statement = $pdo->prepare($sql_exec);
    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    try {
        $statement->execute();
    } catch(Exception $e) {
        $errors[] = "Exception in TwoWeaponFighting.getWeaponProficienciesOneHandedForPlayerCharacter() : " . $e->getMessage();
    }

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildActionBar($player_name, $character_name) {
    $output_html  = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
    $output_html .= '&nbsp;';
    $output_html .= ActionBarHelper::buildEditPlayerCharacterWeaponProficienciesIcon($player_name, $character_name);
    $output_html .= '&nbsp;';

    return $output_html;
}
?>
