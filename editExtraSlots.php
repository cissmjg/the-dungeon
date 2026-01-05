<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/playerCharacterClassId.php';
require_once __DIR__ . '/webio/spellTypeId.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'hiddenTag.php';

require_once 'faAddIcon.php';
require_once 'faCancelIcon.php';
require_once 'spellTypes.php';

require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';
require_once 'characterClasses.php';

const DEALLOCATE_CHARACTER_ACTION = "deallocateExtraSlot";
const DEALLOCATE_CHARACTER_ACTION_ID = "xs-deallocate-character-action";
const ALLOCATE_CHARACTER_ACTION = "allocateExtraSlot";

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$params = [];
$params[PLAYER_NAME] = $input[PLAYER_NAME];
$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

$action_bar = ActionBarHelper::buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$extra_slot_pc_id_by_type = [];
$extra_slot_max_for_types = getExtraSlotMaxForTypes($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $character_summary->getCharacterClasses(), $extra_slot_pc_id_by_type, $errors);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $input[CHARACTER_NAME] ?> Extra Slots</title>
	<link rel="stylesheet" href="dnd-default.css">
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
    <script src="editExtraSlots.js" type="text/javascript"></script>
    <script src="submitTheForm.js" type="text/javascript"></script>
</head>
<body>
    <form name="xsDeallocate" id="xsDeallocate" method="POST" action="<?= CurlHelper::buildUrl('characterActionRouter')?>">
        <input type="hidden" name="playerName" id="playerName" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="characterName" id="characterName" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="characterAction" id="<?= DEALLOCATE_CHARACTER_ACTION_ID ?>" value="<?= DEALLOCATE_CHARACTER_ACTION?>">
        <input type="hidden" name="spellSlotId" id="spellSlotId" value="">
    </form>
<?php
    echo '<div style="width: 100%;"><span class="character_summary">' . $character_summary_stats . '</span><span class="action_bar">' . $action_bar . '</span></div>';

    $extra_slot_types = array_keys($extra_slot_max_for_types);
    if (empty($extra_slot_types)) {
        echo '<h3>No slots available</h3>' . PHP_EOL;
    } else {
        foreach($extra_slot_types AS $extra_slot_type) {
            $locale = 'en_US';
            $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
            $spell_type_desc = getSpellTypeDesc($extra_slot_type);
            $max_for_spell_type = $extra_slot_max_for_types[$extra_slot_type];
            $player_character_class_id = $extra_slot_pc_id_by_type[$extra_slot_type];
            $existing_spells_for_extra_slots = getSpellsForExtraSlotsBySpellType($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $extra_slot_type, $errors);
            echo '<table>' . PHP_EOL;
            $character_classes = (object) $character_summary->getCharacterClasses();
            $character_class_name_id = 0;
            // Capture the first character class name
            foreach($character_classes AS $character_class) {
                $character_class_name_id = getClassID($character_class['class_name']);
                break;
            }

            $colspan = 2;
            if ($character_class_name_id == GREATER_MAGE) {
                $colspan = 1;
                echo '<tr><th>&nbsp;</th><th>Spell Points</th></tr>' . PHP_EOL;
                foreach($existing_spells_for_extra_slots AS $existing_spell_for_extra_slot) {
                    $obj_existing_spell_for_extra_slot = (object) $existing_spell_for_extra_slot;
                    $del_extra_slot_icon = buildDeleteExtraSlotIcon($obj_existing_spell_for_extra_slot->player_spell_slot_id);
                    $points_desc = $existing_spell_for_extra_slot['player_slot_level'] > 1 ? " points" : " point";
                    echo '<tr><td>' . $del_extra_slot_icon . '</td><td style="text-align: center;">' . $existing_spell_for_extra_slot['player_slot_level'] . $points_desc . '</td></tr>' . PHP_EOL;
                }
            } else {
                echo '<tr><th colspan="3">' . $spell_type_desc . '</th></tr>' . PHP_EOL;
                echo '<tr><th>&nbsp;</th><th>Spell Name</th><th>Spell Level</th></tr>' . PHP_EOL;
                foreach($existing_spells_for_extra_slots AS $existing_spell_for_extra_slot) {
                    $obj_existing_spell_for_extra_slot = (object) $existing_spell_for_extra_slot;
                    $del_extra_slot_icon = buildDeleteExtraSlotIcon($obj_existing_spell_for_extra_slot->player_spell_slot_id);
                    echo '<tr><td>' . $del_extra_slot_icon . '</td><td style="text-align: center;">' . $existing_spell_for_extra_slot['spell_name'] . '</td><td style="text-align: center;">' . $existing_spell_for_extra_slot['player_slot_level'] . '</td></tr>' . PHP_EOL;
                }
            }
            $form_id = buildFormId($extra_slot_type);
            $character_action_id = buildCharacterActionId($extra_slot_type);
            $add_extra_slot_icon = buildAddExtraSlotIcon($form_id, $character_action_id);
            $add_extra_slot_form =  buildAddExtraSlotForm($form_id, $input[PLAYER_NAME], $input[CHARACTER_NAME], $character_action_id, $player_character_class_id, $extra_slot_type, $max_for_spell_type, $nf);
            echo '<tr><td>' . $add_extra_slot_icon . '</td><td colspan="'. $colspan .'">' . $add_extra_slot_form . '</td></tr>' . PHP_EOL;
            echo '</table>' . PHP_EOL;
        }
    }
?>
</body>
</html>
<?php
function getExtraSlotMaxForTypes(\PDO $pdo, $player_name, $character_name, $character_classes, &$extra_slot_pc_id_by_type, &$errors) {
    $extra_slot_max_by_spell_type = [];
    foreach($character_classes AS $character_class) {
        $obj_character_class = (object) $character_class;
        $results = getMaxExtraSlotLevelBySpellType($pdo, $player_name, $character_name, $obj_character_class->class_name, $errors);
        if(!empty($results)) {
            foreach($results AS $extraSlotLevelByType) {
                if ($extraSlotLevelByType['spell_level'] > 0) {
                    $spell_type_id = $extraSlotLevelByType['spell_type_id'];
                    $extra_slot_max_by_spell_type[$spell_type_id] = $extraSlotLevelByType['spell_level'];
                    $extra_slot_pc_id_by_type[$spell_type_id] = $obj_character_class->player_character_class_id;
                }
            }
        }
    }

    return $extra_slot_max_by_spell_type;
}

function getSpellsForExtraSlotsBySpellType(\PDO $pdo, $player_name, $character_name, $spell_type, &$errors) {
    $sql_exec = "CALL getSpellsForExtraSlotsBySpellType(:playerName, :characterName, :spellTypeId)";

    $statement = $pdo->prepare($sql_exec);
    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    $statement->bindParam(':spellTypeId', $spell_type, PDO::PARAM_INT);
    try {
        $statement->execute();
    } catch(Exception $e) {
        $errors[] = "Exception in getSpellsForExtraSlotsBySpellType : " . $e->getMessage();
    }    

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getMaxExtraSlotLevelBySpellType(\PDO $pdo, $player_name, $character_name, $character_class_name, &$errors) {
    $sql_exec = "CALL getMaxExtraSlotLevelBySpellType(:playerName, :characterName, :characterClassName)";

    $statement = $pdo->prepare($sql_exec);
    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    $statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);

    try {
        $statement->execute();
    } catch(Exception $e) {
        $errors[] = "Exception in getMaxExtraSlotLevelBySpellType : " . $e->getMessage();
    }    

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildDeleteExtraSlotIcon($extra_slot_id) {
    $form_id = "xsDeallocate";
    $character_action_id = DEALLOCATE_CHARACTER_ACTION_ID;
    $character_action_value = DEALLOCATE_CHARACTER_ACTION;
    $spell_slot_id_value = $extra_slot_id;

    $deallocateExtraSlotIcon = new FaCancelIcon();
    $deallocateExtraSlotIcon->setOnClickJsFunction('deallocateExtraSlot');
    $deallocateExtraSlotIcon->addOnclickJsParameter($form_id);
    $deallocateExtraSlotIcon->addOnclickJsParameter($character_action_id);
    $deallocateExtraSlotIcon->addOnclickJsParameter($character_action_value);
    $deallocateExtraSlotIcon->addOnclickJsParameter($spell_slot_id_value);

    return $deallocateExtraSlotIcon->build();
}

function buildAddExtraSlotIcon($form_id, $character_action_id) {
    $addExtraSlotIcon = new FaAddIcon();
    $addExtraSlotIcon->setOnClickJsFunction('submitTheForm');
    $addExtraSlotIcon->addOnclickJsParameter($form_id);
    $addExtraSlotIcon->addOnclickJsParameter($character_action_id);
    $addExtraSlotIcon->addOnclickJsParameter(ALLOCATE_CHARACTER_ACTION);

    return $addExtraSlotIcon->build();;
}

function buildAddExtraSlotForm($form_id, $player_name, $character_name, $character_action_id, $player_character_class_id, $extra_slot_spell_type, $extra_slot_max_level, $nf) {
    $form_html  = PHP_EOL . '<form id="' . $form_id . '" name="' . $form_id . '" method="POST" action="' .  CurlHelper::buildUrl('characterActionRouter') . '">' . PHP_EOL;
    $form_html .= buildHiddenTag(PLAYER_NAME, $player_name) . PHP_EOL;
    $form_html .= buildHiddenTag(CHARACTER_NAME, $character_name) . PHP_EOL;
    $form_html .= buildHiddenTag(PLAYER_CHARACTER_CLASS_ID, $player_character_class_id) . PHP_EOL;
    $form_html .= buildHiddenTag(SPELL_TYPE_ID, $extra_slot_spell_type) . PHP_EOL;
    $form_html .= buildHiddenTagWithId(CHARACTER_ACTION, $character_action_id, ALLOCATE_CHARACTER_ACTION) . PHP_EOL;
    $form_html .= "Add ";
    $form_html .= '<select id="slotLevel" name="slotLevel">' . PHP_EOL;

    for ($i = $extra_slot_max_level; $i >= 1; $i--) {
        $spell_level_desc = $nf->format($i);
        $form_html .= '<option value="' . $i . '">' . $spell_level_desc . '</option>' . PHP_EOL;
    }
    $form_html .= '</select> level slot' . PHP_EOL . '</form>' . PHP_EOL;

    return $form_html;
}

function buildFormId($extra_slot_spell_type) {
    return 'xsAllocate-' . $extra_slot_spell_type;
}

function buildCharacterActionId($extra_slot_spell_type) {
    return 'xsAllocation-action-' . $extra_slot_spell_type;
}
?>
