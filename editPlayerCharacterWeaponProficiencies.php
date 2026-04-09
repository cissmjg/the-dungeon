
text/x-generic editPlayerCharacterWeaponProficiencies.php ( PHP script, ASCII text, with CRLF line terminators )
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
require_once __DIR__ . '/webio/skillCatalogId.php';
require_once __DIR__ . '/webio/isSkillFocus.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';
require_once __DIR__ . '/webio/weapon2ProficiencyId.php';
require_once __DIR__ . '/dbio/constants/characterClasses.php';
require_once __DIR__ . '/dbio/constants/skills.php';
require_once __DIR__ . '/dbio/constants/weapons.php';

require_once __DIR__ . '/fa/faDeleteIcon.php';

require_once __DIR__ . '/classes/skillCatalog.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/classes/characterDetails.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/playerCharacterWeaponSkillId.php';
require_once __DIR__ . '/webio/playerCharacterSkillId.php';
require_once __DIR__ . '/webio/characterLevel.php';
require_once __DIR__ . '/webio/characterClassId.php';
require_once __DIR__ . '/webio/textInput.php';

require_once __DIR__ . '/classes/skills/brutalThrow.php';
require_once __DIR__ . '/classes/skills/powerAttack.php';
require_once __DIR__ . '/classes/skills/powerThrow.php';
require_once __DIR__ . '/classes/skills/weaponFinesse.php';
require_once __DIR__ . '/classes/skills/zenArchery.php';

const SELECT_PROMPT = '[Select a Weapon]';
const SELECT_WEAPON_OPTION = '<option value="0">' . SELECT_PROMPT . '</option>' . PHP_EOL;
$nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

// Debug section
$debug_output = '';
$debug_skills = true;

$character_details = new CharacterDetails();
$character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}
$primary_class = $character_details->getPrimaryClass();

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->renderCharacterDetails($character_details);

$action_bar = ActionBarHelper::buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$weapon_proficiency_list = getWeaponProficienciesForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$delete_weapon_proficiency_form_id = 'deleteWeaponProficiency';

$delete_attribute_weapon_skill_form_id = 'deleteAttributeWeaponSkill';
$delete_attribute_weapon_skill_id = $delete_attribute_weapon_skill_form_id . '-' . SKILL_CATALOG_ID;

$add_attribute_weapon_skill_form_id = 'addAttributeWeaponSkill';
$add_attribute_weapon_skill_element_id = $add_attribute_weapon_skill_form_id . '-' . SKILL_CATALOG_ID;

$preferred_weapon_proficiency_form_id = 'addPreferredWeaponProficiency';
$preferred_weapon_proficiency_element_id = $preferred_weapon_proficiency_form_id . WEAPON_PROFICIENCY_ID;

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

$weapon_proficiency_skills = $player_character_skill_set->getAllSkillInstances(WEAPON_PROFICIENCY);

$debug_output .= print_r($weapon_proficiency_skills, true) . PHP_EOL;
$debug_output .= 'Proficient with dagger: ' . var_export(isProficientWithWeapon($weapon_proficiency_skills, DAGGER), true) . PHP_EOL;

$cavalier_level = -1;
$cavalier_preferred_level3_weapon_needed = false;
$cavalier_preferred_level5_weapon_needed = false;

$debug_output .= 'Class: ' . $primary_class->getClassId() . PHP_EOL;
$debug_output .= 'Level: ' . $primary_class->getClassLevel() . PHP_EOL;

if ($primary_class->getClassId() == CAVALIER || $primary_class->getClassId() == PALADIN) {
    if ($primary_class->getClassLevel() >= 3) {
        // Check for level 3 preferred weapon
        $cavalier_preferred_level3_weapon_needed = true;
        $cavalier_level = 3;
        $debug_output .= 'Level3 Cavalier: ' . var_export($cavalier_preferred_level3_weapon_needed, true) . PHP_EOL;
        $debug_output .= 'Cavalier Level: ' . $cavalier_level . PHP_EOL;
        foreach($weapon_proficiency_skills AS $cavalier_weapon_proficiency) {
            if ($cavalier_weapon_proficiency->getIsPreferredCavalierLevel3()) {
                $debug_output .= 'Level3 preferred weapon: ' . $cavalier_weapon_proficiency->getWeaponProficiencyId() . PHP_EOL;
                $cavalier_preferred_level3_weapon_needed = false;
                break;
            }
        }

        // If a level 3 preferred weapon is present, check to see if a level 5 preferred weapon is needed
        if (!$cavalier_preferred_level3_weapon_needed && $primary_class->getClassLevel() >= 5) {
            $cavalier_preferred_level5_weapon_needed = true;
            $cavalier_level = 5;
            $debug_output .= 'Level5 Cavalier: ' . var_export($cavalier_preferred_level5_weapon_needed, true) . PHP_EOL;
            $debug_output .= 'Cavalier Level: ' . $cavalier_level . PHP_EOL;
            foreach($weapon_proficiency_skills AS $cavalier_weapon_proficiency) {
                if ($cavalier_weapon_proficiency->getIsPreferredCavalierLevel5()) {
                    $cavalier_preferred_level5_weapon_needed = false;
                    $debug_output .= 'Level5 preferred weapon: ' . $cavalier_weapon_proficiency->getWeaponProficiencyId() . PHP_EOL;
                    break;
                }
            }
        }
    }
}

$elven_cavalier_preferred_level4_weapon_needed = false;
$elven_cavalier_preferred_level6_weapon_needed = false;
if ($primary_class->getClassId() == ELVEN_CAVALIER) {
    if ($primary_class->getClassLevel() >= 4) {
        // Check for level 4 preferred weapon
        $elven_cavalier_preferred_level4_weapon_needed = true;
        $cavalier_level = 4;
        foreach($weapon_proficiency_skills AS $cavalier_weapon_proficiency) {
            if ($cavalier_weapon_proficiency->getIsPreferredElvenCavalierLevel4()) {
                $elven_cavalier_preferred_level4_weapon_needed = false;
                break;
            }
        }

        // If a level 4 preferred weapon is present, check to see if a level 6 preferred weapon is needed
        if (!$elven_cavalier_preferred_level4_weapon_needed && $primary_class->getClassLevel() >= 6) {
            $elven_cavalier_preferred_level6_weapon_needed = true;
            $cavalier_level = 6;
            foreach($weapon_proficiency_skills AS $cavalier_weapon_proficiency) {
                if ($cavalier_weapon_proficiency->getIsPreferredElvenCavalierLevel6()) {
                    $elven_cavalier_preferred_level6_weapon_needed = false;
                    break;
                }
            }
        }
    }
}

$preferred_weapon_list = [];
if ($cavalier_preferred_level3_weapon_needed) {
    $preferred_weapon_list = buildCavalierLevel3PreferredWeaponList($weapon_proficiency_skills);
} else if ($cavalier_preferred_level5_weapon_needed) {
    $preferred_weapon_list = buildCavalierLevel5PreferredWeaponList($weapon_proficiency_skills);
} else if ($elven_cavalier_preferred_level4_weapon_needed) {
    $preferred_weapon_list = buildElvenCavalierPreferredWeaponList($weapon_proficiency_skills);
} else if($elven_cavalier_preferred_level6_weapon_needed) {
    $preferred_weapon_list = buildElvenCavalierPreferredWeaponList($weapon_proficiency_skills);
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
    <?php if ($primary_class->getClassId() == CAVALIER || $primary_class->getClassId() == PALADIN || $primary_class->getClassId() == ELVEN_CAVALIER): ?>
    <form name="<?= $preferred_weapon_proficiency_form_id ?>" id="<?= $preferred_weapon_proficiency_form_id ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_ADD_PREFERRED_WEAPON_PROFICIENCY ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_LEVEL ?>" value="<?= $cavalier_level ?>">
        <input type="hidden" name="<?= CHARACTER_CLASS_ID ?>" value="<?= $primary_class->getClassId() ?>">
        <input type="hidden" name="<?= WEAPON_PROFICIENCY_ID ?>" id="<?= $preferred_weapon_proficiency_element_id ?>" value="">
    </form>
    <?php endif ?>
    <div style="width: 100%; margin-bottom: 3px;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div>&nbsp;</div>
    <div class="togglePanel">
        <a href="#">
            <span class="fa fa-plus"></span> Add a weapon proficiency
        </a>
        <div class="togglePanelContent">
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
        </div>
    </div>
    <?php if ($cavalier_preferred_level3_weapon_needed || $cavalier_preferred_level5_weapon_needed || $elven_cavalier_preferred_level4_weapon_needed || $elven_cavalier_preferred_level6_weapon_needed): ?>
    <div>&nbsp;</div>
    <div class="preferredWeaponContainer">
        <h3>Preferred Weapon (<?= $nf->format($cavalier_level) ?> level)</h3>
        <select id="preferredWeaponList" onchange="preferredWeaponChange('preferredWeaponList', 'addPreferredWeapon', '<?= $preferred_weapon_proficiency_element_id ?>');">
            <?= $preferred_weapon_list ?>
        </select>
        <button id="addPreferredWeapon" onclick="submitAddPreferredWeaponProficiencyForm('<?= $preferred_weapon_proficiency_form_id ?>', 'preferredWeaponList', '<?= $preferred_weapon_proficiency_element_id ?>');" hidden>Go!</button>
    </div>
    <div>&nbsp;</div>
    <?php endif ?>
    <h3>Current Weapon Proficiencies for <?= $input[CHARACTER_NAME] ?></h3>
    <?php if (count($weapon_proficiency_list) == 0): ?>
        <span style="font-size: 18px;">No weapons available</span>
    <?php else: ?>
        <table>
            <?php if ($primary_class->getClassId() == CAVALIER || $primary_class->getClassId() == ELVEN_CAVALIER || $primary_class->getClassId() == PALADIN): ?>
            <tr><th>&nbsp;</th><th>Description</th><th>Preferred</th></tr>
            <?php else: ?>
            <tr><th>&nbsp;</th><th>Description</th></tr>
            <?php endif ?>
            <?php
                foreach($weapon_proficiency_list AS $weapon_proficiency) {
                    $preferred_weapon_text = "&nbsp;";
                    if (!empty($weapon_proficiency['is_preferred_cavalier_level3'])) {
                        $preferred_weapon_text = "Preferred (3rd)";
                    } else if (!empty($weapon_proficiency['is_preferred_cavalier_level5'])) {
                        $preferred_weapon_text = "Preferred (5th)";
                    } else if (!empty($weapon_proficiency['is_preferred_elven_cavalier_level4'])) {
                        $preferred_weapon_text = "Preferred (4th)";
                    } else if ($weapon_proficiency['is_preferred_elven_cavalier_level6']) {
                        $preferred_weapon_text = "Preferred (6th)";
                    }
                    $weapon_desc = str_replace("'", "", html_entity_decode($weapon_proficiency['weapon_proficiency_description']));
                    $output_row  = '<tr>';
                    $output_row .= '<td>' . buildDeletePlayerCharacterWeaponProficiencyIcon($delete_weapon_proficiency_form_id, $weapon_desc, $weapon_proficiency['player_weapon_proficiency_id']) . '</td>';
                    $output_row .= '<td>' . buildWeaponNameCell($input[PLAYER_NAME], $input[CHARACTER_NAME], $weapon_proficiency) . '</td>';
                    if ($primary_class->getClassId() == CAVALIER || $primary_class->getClassId() == ELVEN_CAVALIER || $primary_class->getClassId() == PALADIN) {
                        $output_row .= '<td>' . $preferred_weapon_text . '</td>';
                    }
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

function buildCavalierLevel3PreferredWeaponList($weapon_proficiency_skills) {
    $output_html  = SELECT_WEAPON_OPTION;
    if (!isProficientWithWeapon($weapon_proficiency_skills, BROAD_SWORD)) {
        $output_html .= '<option value="' . BROAD_SWORD . '">' . getWeaponDescriptionFromProficiencyId(BROAD_SWORD) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, LONG_SWORD)) {
        $output_html .= '<option value="' . LONG_SWORD . '">' . getWeaponDescriptionFromProficiencyId(LONG_SWORD) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, SCIMITAR)) {
        $output_html .= '<option value="' . SCIMITAR . '">' . getWeaponDescriptionFromProficiencyId(SCIMITAR) . '</option>' . PHP_EOL;
    }

    return $output_html;
}

function buildCavalierLevel5PreferredWeaponList($weapon_proficiency_skills) {
    $output_html  = SELECT_WEAPON_OPTION;
    if (!isProficientWithWeapon($weapon_proficiency_skills, HORSEMANS_MACE)) {
        $output_html .= '<option value="' . HORSEMANS_MACE . '">' . getWeaponDescriptionFromProficiencyId(HORSEMANS_MACE) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, HORSEMANS_FLAIL)) {
        $output_html .= '<option value="' . HORSEMANS_FLAIL . '">' . getWeaponDescriptionFromProficiencyId(HORSEMANS_FLAIL) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, MILITARY_HORSEMANS_PICK)) {
        $output_html .= '<option value="' . MILITARY_HORSEMANS_PICK . '">' . getWeaponDescriptionFromProficiencyId(MILITARY_HORSEMANS_PICK) . '</option>' . PHP_EOL;
    }

    return $output_html;
}

function buildElvenCavalierPreferredWeaponList($weapon_proficiency_skills) {
    $output_html  = SELECT_WEAPON_OPTION;
    if (!isProficientWithWeapon($weapon_proficiency_skills, DAGGER)) {
        $output_html .= '<option value="' . DAGGER . '">' . getWeaponDescriptionFromProficiencyId(DAGGER) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, KNIFE)) {
        $output_html .= '<option value="' . KNIFE . '">' . getWeaponDescriptionFromProficiencyId(KNIFE) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, SHORT_SWORD)) {
        $output_html .= '<option value="' . SHORT_SWORD . '">' . getWeaponDescriptionFromProficiencyId(SHORT_SWORD) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, SPEAR)) {
        $output_html .= '<option value="' . SPEAR . '">' . getWeaponDescriptionFromProficiencyId(SPEAR) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, RANSEUR)) {
        $output_html .= '<option value="' . RANSEUR . '">' . getWeaponDescriptionFromProficiencyId(RANSEUR) . '</option>' . PHP_EOL;
    }

    if (!isProficientWithWeapon($weapon_proficiency_skills, JAVELIN)) {
        $output_html .= '<option value="' . JAVELIN . '">' . getWeaponDescriptionFromProficiencyId(JAVELIN) . '</option>' . PHP_EOL;
    }

    return $output_html;
}

function isProficientWithWeapon($weapon_proficiency_skills, $weapon_proficiency_id) {
    foreach($weapon_proficiency_skills AS $weapon_proficiency_skill) {
        if ($weapon_proficiency_skill->getWeaponProficiencyId() == $weapon_proficiency_id) {
            return true;
        }
    }

    return false;
}

?>