<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/characterClassName.php';
require_once 'pageAction.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'hiddenTag.php';

require_once 'faEditIcon.php';
require_once 'faCancelIcon.php';
require_once 'faUpdateSpellBookIcon.php';

require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';
require_once 'classAbilitiesGM.php';
require_once 'characterClasses.php';

require_once 'emptySpellSlot.php';

const UPDATE_SPELL_POOL_ACTION = 'updateSpellPool';

$input = [];
$errors = [];
$log = [];

// Populate player, character and character class names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterClassName($errors, $input);
getPageAction($errors, $input);

$params = [];
$params[PLAYER_NAME] = $input[PLAYER_NAME];
$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
$params[CHARACTER_CLASS_NAME] = $input[CHARACTER_CLASS_NAME];
$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

$url = CurlHelper::buildUrl('getSpellBookForPlayerCharacter');
$raw_results = CurlHelper::performGetRequest($url, $params);

$spell_pool_entries = json_decode($raw_results);
$edit_page = getEditPage($errors, $input);

$available_spells_by_level = [];
$next_available_slot_id = [];
$spell_pool_slots = [];
$occupied_slots = [];
$spell_pool_entries_by_level = [];

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

foreach($spell_pool_entries AS $spell_pool_entry) {
    if (!array_key_exists($spell_pool_entry->spell_level, $available_spells_by_level)) {
        $spell_pool_slot_url = CurlHelper::buildUrl('getUnallocatedSpellsForSpellBook');
        $spell_pool_slot_params = buildUnallocatedSpellsParams($input[PLAYER_NAME], $input[CHARACTER_NAME], $input[CHARACTER_CLASS_NAME], $spell_pool_entry->spell_level);
        $mu_spells = json_decode(CurlHelper::performGetRequest($spell_pool_slot_url, $spell_pool_slot_params));
        $available_spells_by_level[$spell_pool_entry->spell_level] = $mu_spells;

        $next_available_slot_id[$spell_pool_entry->spell_level] = -1;
        $spell_pool_entries_by_level[$spell_pool_entry->spell_level] = array();
    }
    $spell_pool_slots[$spell_pool_entry->spell_level][] = $spell_pool_entry->spell_pool_id;
    $spell_pool_entries_by_level[$spell_pool_entry->spell_level][] = $spell_pool_entry;
}

// Fill up lookup tables with the next available spell slot for each level
foreach($spell_pool_entries AS $spell_pool_entry) {
    if ($next_available_slot_id[$spell_pool_entry->spell_level] == -1 && $spell_pool_entry->spell_name == EMPTY_SLOT_SPELL_NAME) {
        $next_available_slot_id[$spell_pool_entry->spell_level] = $spell_pool_entry->spell_pool_id;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit SpellBook</title>
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="submitTheForm.js"></script>
    <script type="text/javascript" src="editSpellBook.js"></script>
	<link rel="stylesheet" href="dnd-default.css">
</head>
<body>
<?php
$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME], $input[CHARACTER_CLASS_NAME], $edit_page);
echo '<div style="width: 100%;"><span class="character_summary">' . $character_summary_stats . '</span><span class="action_bar">' . $action_bar . '</span></div>';
if (count($spell_pool_entries) == 0) {
    echo '<h3>No spells available</h3>';
} else {
    echo '<table>';
    $prev_spell_level = -1;
    $column_counter = 1;
    $row_counter = 1;
    foreach($spell_pool_entries AS $spell_pool_entry) {
        if ($prev_spell_level != $spell_pool_entry->spell_level) {
            $prev_spell_level = $spell_pool_entry->spell_level;
            $available_mu_spells = $available_spells_by_level[$spell_pool_entry->spell_level];
            if ($row_counter > 1) {
                echo '</tr>' . PHP_EOL;
            }

            echo '<tr><td colspan="4" style="text-align:center;">Level ' . $spell_pool_entry->spell_level . '</td></tr>' . PHP_EOL;
            $submit_icon_id = buildSubmitIconId($spell_pool_entry->spell_level);
            $spell_pool_form_id = buildSpellPoolFormId($spell_pool_entry->spell_pool_id);
            $form_character_action_id = buildCharacterActionId($spell_pool_entry->spell_pool_id);
            $form_id_name = buildFormId($spell_pool_entry->spell_level);
            if ($edit_page) {
                $form_html = buildFormHtml($spell_pool_entry->spell_level, $available_mu_spells, $next_available_slot_id[$spell_pool_entry->spell_level], $input[PLAYER_NAME], $input[CHARACTER_NAME], $input[CHARACTER_CLASS_NAME], $submit_icon_id, $spell_pool_form_id);
                echo '<tr><td colspan="4">' . $form_html . '</td></tr>' . PHP_EOL;
            }
            $column_counter = 1;
            $row_counter++;
        }

        if ($spell_pool_entry->spell_catalog_id === GM_ESP_SPELL_CATALOG_ID || $spell_pool_entry->spell_catalog_id === GM_LEVITATE_SPELL_CATALOG_ID) {
            continue;
        }

        if ($column_counter == 5) {
            echo '</tr>' . PHP_EOL;
            $row_counter++;
            $column_counter = 1;
        }

        if ($column_counter == 1) {
            echo '<tr>';
        }

        $spell_slot_action_form = '';
        $show_spell_list = '';
        if ($spell_pool_entry->spell_name != EMPTY_SLOT_SPELL_NAME && $edit_page) {
            $form_character_action_update_existing_id = buildCharacterActionId($spell_pool_entry->spell_level);
            $show_spell_list = buildEditExistingSlotIcon($spell_pool_slots, $spell_pool_entry->spell_pool_id, $submit_icon_id, $spell_pool_entry->spell_level, $spell_pool_form_id, $form_id_name, $form_character_action_update_existing_id);
        }

        $td_html = '<td id="';
        $td_html .= $spell_pool_entry->spell_pool_id;
        $td_html .= '" style="width: 25%">';

        if ($spell_pool_entry->spell_name == EMPTY_SLOT_SPELL_NAME) {
            $td_html .= EMPTY_SLOT_SPELL_NAME;
        } else {
            $td_html .= '<a href="' . $spell_pool_entry->spell_link . '" target="_blank">' . $spell_pool_entry->spell_name . '</a>';
        }

        $td_html .= '&nbsp;' . $show_spell_list . '</td>' . PHP_EOL;
        
        echo $td_html;
        $column_counter++;
    }

    if ($column_counter != 5) {
        echo '</tr>' . PHP_EOL;
    }
    echo '</table>';
}
?>
</body>
</html>

<?php
function buildUnallocatedSpellsParams($player_name, $character_name, $character_class_name, $spell_level) {
    $params = [];
    $params[PLAYER_NAME] = $player_name;
    $params[CHARACTER_NAME] = $character_name;
    $params[CHARACTER_CLASS_NAME] = $character_class_name;
    $params[SPELL_LEVEL] = $spell_level;
    $params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

    return $params;
}

function buildFormHtml($spell_level, $available_mu_spells, $spell_pool_id, $player_name, $character_name, $character_class_name, $submit_icon_id, $spell_pool_form_id) {
    $form_id_name = buildFormId($spell_level);
    $character_action_id = buildCharacterActionId($spell_level);
    $form_start_tag = buildFormStart($form_id_name, $character_action_id, $spell_pool_id, $player_name, $character_name, $character_class_name, $spell_pool_form_id);
    $label_for_select = buildLabelForSelect();
    $select_tag = buildCandidateOptionsForSlot($available_mu_spells, $spell_pool_id);
    $button_tag = buildUpdateSpellBookButtonTag($form_id_name, $character_action_id, $submit_icon_id, $spell_pool_id) . PHP_EOL;
    
    $form_end_tag = '</form>';
    return $form_start_tag . $label_for_select . $select_tag . '&nbsp;' . $button_tag . $form_end_tag;
}

function buildFormStart($form_id_name, $character_action_id, $spell_pool_id, $player_name, $character_name, $character_class_name, $spell_pool_form_id) {
    $form_start_tag = "<form ";
    $form_start_tag .= 'id="' . $form_id_name . '" name="' . $form_id_name .'" ';
    $router_action_url = CurlHelper::buildUrl('characterActionRouter');
    $form_start_tag .= 'action="' . $router_action_url . '">';
    $player_name_tag = buildHiddenTag(PLAYER_NAME, $player_name);
    $character_name_tag = buildHiddenTag(CHARACTER_NAME, $character_name);
    $character_class_name = buildHiddenTag(CHARACTER_CLASS_NAME, $character_class_name);
    $router_action_tag = buildHiddenTagWithId('characterAction', $character_action_id, '');
    $spell_pool_id_tag = buildHiddenTagWithId('spellPoolSlotId', $spell_pool_form_id, $spell_pool_id);
    $page_action_tag = buildHiddenTag('pageAction', 'edit');

    return $form_start_tag . $player_name_tag . $character_name_tag . $character_class_name . $router_action_tag . $spell_pool_id_tag . $page_action_tag . PHP_EOL;
}

function buildLabelForSelect() {
    return '<label for="spellCatalogId" style="font-size: 18px;">Allocate Spell </label>';
}

function buildCandidateOptionsForSlot($mu_spells, $spell_slot_id) {
    $tag_name_id = SPELL_CATALOG_ID;
    $data_spell_pool_id = 'data-spell-pool-id="' . $spell_slot_id;
    $select_tag = '<select id="' . $tag_name_id . '" name="' .  $tag_name_id . '" ' . $data_spell_pool_id . '" style="font-size: 18px;">' . PHP_EOL;
    $option_list = '';
    foreach($mu_spells AS $mu_spell) {
        $option_list .= '<option value="' . $mu_spell->spell_catalog_id .'">' . $mu_spell->spell_name . "</option>" . PHP_EOL;
    }

    $option_list .= '<option value="' . EMPTY_SLOT_SPELL_CATALOG_ID . '">' . EMPTY_SLOT_SPELL_NAME . '</option>';
    return $select_tag . $option_list . '</select>';
}

function getEditPage(&$errors, &$input) {
    if ($input['pageAction'] == 'edit') {
        return true;
    } else {
        return false;
    }
}

function buildActionBar($player_name, $character_name, $character_class_name, $edit_page) {
    $user_action_bar = ActionBarHelper::buildActionBar($player_name, $character_name) . PHP_EOL;

    $ready_spells_action_bar = '';
    if (getClassID($character_class_name) == GREATER_MAGE) {
        $ready_spells_action_bar = ActionBarHelper::buildReadyGMSpellsIcon($player_name, $character_name) . PHP_EOL;
    } else {
        $ready_spells_action_bar = ActionBarHelper::buildReadySpellsIcon($player_name, $character_name) . PHP_EOL;
    }
    return $user_action_bar . '&nbsp;' . $ready_spells_action_bar . '&nbsp;';
}

function buildEditExistingSlotIcon($spell_pool_slots, $spell_pool_id, $submit_icon_id, $spell_level, $spell_pool_form_id, $form_id_name, $form_character_action_id) {
    $all_slots_for_level = $spell_pool_slots[$spell_level];

    $offset =  array_search($spell_pool_id, $all_slots_for_level);
    unset($all_slots_for_level[$offset]);

    $all_occupied_spell_slots = implode(',', $all_slots_for_level);
    $all_occupied_spell_slots_quoted = $all_occupied_spell_slots;

    $cancel_hide_elements_id = buildCancelHideElementsId($spell_pool_id);
    $hide_elements_id = buildHideElementsId($spell_pool_id);
    $update_existing_slot_submit_icon_id = buildUpdateExistingSlotIconId($spell_pool_id);
    $output_html = buildHideElementsIcon($all_occupied_spell_slots_quoted, $submit_icon_id, $cancel_hide_elements_id, $hide_elements_id, $update_existing_slot_submit_icon_id);
    $output_html .= PHP_EOL;
    $output_html .= buildUpdateExistingSlotIcon($spell_pool_id, $spell_pool_form_id, $form_id_name, $form_character_action_id, $submit_icon_id, $update_existing_slot_submit_icon_id);
    $output_html .= PHP_EOL;
    $output_html .= '&nbsp;';
    $output_html .= buildCancelHideElementsIcon($all_occupied_spell_slots_quoted, $submit_icon_id, $cancel_hide_elements_id, $hide_elements_id, $update_existing_slot_submit_icon_id);
    $output_html .= PHP_EOL;

    return $output_html;
}
function buildUpdateExistingSlotIcon($spell_pool_id, $spell_pool_form_id, $form_id_name, $form_character_action_id, $submit_icon_id, $update_existing_slot_submit_icon_id) {

    $cast_spell_icon = new FaUpdateSpellBookIcon();
    $cast_spell_icon->setElementId($update_existing_slot_submit_icon_id);
    $cast_spell_icon->setHidden(true);
    $cast_spell_icon->setOnClickJsFunction('updateExistingSpellSlot');
    $cast_spell_icon->addOnclickJsParameter($spell_pool_form_id);
    $cast_spell_icon->addOnclickJsParameter($spell_pool_id);
    $cast_spell_icon->addOnclickJsParameter($form_id_name);
    $cast_spell_icon->addOnclickJsParameter($form_character_action_id);
    $cast_spell_icon->addOnclickJsParameter(UPDATE_SPELL_POOL_ACTION);

    return $cast_spell_icon->build();
}

function buildUpdateSpellBookButtonTag($form_id_name, $form_character_action_id, $submit_icon_id, $spell_pool_id) {
    $cast_spell_icon = new FaUpdateSpellBookIcon();
    $cast_spell_icon->setOnClickJsFunction('submitTheForm');
    $cast_spell_icon->addOnclickJsParameter($form_id_name);
    $cast_spell_icon->addOnclickJsParameter($form_character_action_id);
    $cast_spell_icon->addOnclickJsParameter(UPDATE_SPELL_POOL_ACTION);
    $cast_spell_icon->setElementId($submit_icon_id);
    if ($spell_pool_id == -1) {
        $cast_spell_icon->setHidden(true);
    }

    return $cast_spell_icon->build();
}


function buildHideElementsIcon($all_occupied_spell_slots, $submit_icon_id, $cancel_hide_elements_id, $hide_elements_icon_id, $update_existing_slot_submit_icon_id) {
    $edit_icon = new FaEditIcon();
    $edit_icon->setOnClickJsFunction('hideOtherElements');
    $edit_icon->addOnclickJsParameter($all_occupied_spell_slots);
    $edit_icon->addOnclickJsParameter($submit_icon_id);
    $edit_icon->addOnclickJsParameter($cancel_hide_elements_id);
    $edit_icon->addOnclickJsParameter($hide_elements_icon_id);
    $edit_icon->addOnclickJsParameter($update_existing_slot_submit_icon_id);
    $edit_icon->setElementId($hide_elements_icon_id);

    return $edit_icon->build();
}

function buildCancelHideElementsIcon($all_occupied_spell_slots, $submit_icon_id, $cancel_hide_elements_id, $hide_elements_icon_id, $update_existing_slot_submit_icon_id) {
    $cancel_icon = new FaCancelIcon();
    $cancel_icon->setElementId($cancel_hide_elements_id);
    $cancel_icon->setOnClickJsFunction('unhideOtherElements');
    $cancel_icon->addOnclickJsParameter($all_occupied_spell_slots);
    $cancel_icon->addOnclickJsParameter($submit_icon_id);
    $cancel_icon->addOnclickJsParameter($cancel_hide_elements_id);
    $cancel_icon->addOnclickJsParameter($hide_elements_icon_id);
    $cancel_icon->addOnclickJsParameter($update_existing_slot_submit_icon_id);
    $cancel_icon->setHidden(true);

    return $cancel_icon->build();
}

function buildFormId($spell_pool_id) {
    return "form-spell-id-name-" . $spell_pool_id;
}

function buildCharacterActionId($spell_pool_id) {
    return 'character-action-' . $spell_pool_id;
}

function buildExistingSlotSubmitIconId($spell_pool_id) {
    return 'existing-slot-submit-icon-' . $spell_pool_id;
}

function buildHideElementsId($spell_pool_id) {
    return 'hide-elements-icon-id-' . $spell_pool_id;
}

function buildSubmitIconId($spell_level) {
    return 'submit-icon-' . $spell_level;
}

function  buildSpellPoolFormId($spell_pool_id) {
    return 'spell-pool-form-id-' . $spell_pool_id;
}

function buildCancelHideElementsId($spell_pool_id) {
    return 'existing-slot-cancel-icon-' . $spell_pool_id;
}

function buildUpdateExistingSlotIconId($spell_pool_id) {
    return 'existing-slot-update-icon-' . $spell_pool_id;
}
?>

