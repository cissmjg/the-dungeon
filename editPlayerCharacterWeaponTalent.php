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

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponSkillId($errors, $input);

$delete_form_id = 'deleteWeaponProficiency';
$delete_weapon_talent_id = $delete_form_id . '-' . PLAYER_CHARACTER_WEAPON_SKILL_ID;

$add_form_id = 'addWeaponProficiency';
$add_weapon_talent_form_id = $add_form_id . '-' . PLAYER_CHARACTER_WEAPON_SKILL_ID;

$form_id_lookup = new FormIdLookup($delete_form_id, $delete_weapon_talent_id, $add_form_id, $add_weapon_talent_form_id, WEAPON_PROFICIENCY_ID, WEAPON2_PROFICIENCY_ID);

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

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->renderCharacterDetails($character_details);

$page_title = $input[CHARACTER_NAME] . ' Weapon Talents';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editPlayerCharacterWeaponTalent.js';
$page_specific_css = 'editPlayerCharacterWeaponTalent.css';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

$weapon_proficiency = getWeaponProficiencyName($pdo, $input[PLAYER_CHARACTER_WEAPON_SKILL_ID], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$debug_skills = false;
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
        <input type="hidden" name="<?= WEAPON_PROFICIENCY_ID ?>" id="<?= WEAPON_PROFICIENCY_ID ?>" value="">
        <input type="hidden" name="<?= WEAPON2_PROFICIENCY_ID ?>" id="<?= WEAPON2_PROFICIENCY_ID ?>" value="">
    </form>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <h3>Skills for <?= $weapon_proficiency['weapon_proficiency_name'] ?></h3>
    <?php
        if ($weapon_proficiency['weapon_proficiency_id'] == FIST) {
            $dirty_fighting = new DirtyFighting($the_skill_catalog, $form_id_lookup);
            $dirty_fighting->setWeaponProficiencyValue($weapon_proficiency['weapon_proficiency_id']);

            $fist_of_iron = new FistOfIron($the_skill_catalog, $form_id_lookup);
            $fist_of_iron->setWeaponProficiencyValue($weapon_proficiency['weapon_proficiency_id']);

            $mantis_leap = new MantisLeap($the_skill_catalog, $form_id_lookup);
            $mantis_leap->setWeaponProficiencyValue($weapon_proficiency['weapon_proficiency_id']);

            echo $dirty_fighting->render($character_details, $player_character_skill_set);
            echo $fist_of_iron->render($character_details, $player_character_skill_set);
            echo $mantis_leap->render($character_details, $player_character_skill_set);
        }
    ?>
    <h3>Prerequisite Skills</h3>
    <?php
        if ($weapon_proficiency['weapon_proficiency_id'] == FIST) {
            $improved_unarmed_strike = new ImprovedUnarmedStrike($the_skill_catalog, $form_id_lookup);
            echo $improved_unarmed_strike->render($character_details, $player_character_skill_set);
            $jump = new Jump($the_skill_catalog, $form_id_lookup);
            echo $jump->render($character_details, $player_character_skill_set);
        }
    ?>
<?php if ($debug_skills): ?>
<div>
    <pre>
        <?= $dirty_fighting->dump() ?>
        <?= $fist_of_iron->dump() ?>
        <?= $mantis_leap->dump() ?>
        <?= $improved_unarmed_strike->dump(); ?>
        <?= $jump->dump(); ?>
    </pre>
</div>
<?php endif ?>
</body>
</html>

<?php

function buildWeaponNameCell($player_name, $character_name, $weapon_proficiency) {
    $weapon_proficiency_desc = $weapon_proficiency['weapon_proficiency_description'];
    $player_character_weapon_proficiency_id = $weapon_proficiency['player_weapon_proficiency_id'];
    $output_html  = $weapon_proficiency_desc;
    $output_html .= '<span style="float: right; margin-left: 15px;">';
    $output_html .= ActionBarHelper::buildEditPlayerCharacterWeaponTalentIcon($player_name, $character_name, $player_character_weapon_proficiency_id);
    $output_html .= '</span>';

    return $output_html;
}

function buildDeletePlayerCharacterWeaponProficiencyIcon($delete_form_id, $weapon_desc, $player_character_weapon_proficiency_id) {
    $delete_icon = new FaDeleteIcon();
    $delete_icon->setOnClickJsFunction('confirmPlayerCharacterWeaponProficiencyDelete');
    $delete_icon->addOnclickJsParameter($delete_form_id);
    $delete_icon->addOnclickJsParameter(PLAYER_CHARACTER_WEAPON_SKILL_ID);
    $delete_icon->addOnclickJsParameter($player_character_weapon_proficiency_id);
    $delete_icon->addOnclickJsParameter($weapon_desc);
    $delete_icon->setHoverText('Delete ' . $weapon_desc);

    return $delete_icon->build();
}

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

function buildActionBar($player_name, $character_name) {
    $output_html  = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
    $output_html .= '&nbsp;';
    $output_html .= ActionBarHelper::buildEditPlayerCharacterWeaponProficienciesIcon($player_name, $character_name);
    $output_html .= '&nbsp;';

    return $output_html;
}
?>
