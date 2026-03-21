<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/characterActionRoutes.php';
require_once __DIR__ . '/webio/spellCatalogId.php';
require_once __DIR__ . '/webio/spellLevel.php';
require_once __DIR__ . '/webio/spellDuration.php';
require_once __DIR__ . '/webio/spellCastingTime.php';
require_once __DIR__ . '/webio/hoursOfSleep.php';
require_once __DIR__ . '/webio/spellSlotId.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/characterLevel.php';

require_once __DIR__ . '/fa/faCastSpellIcon.php';
require_once __DIR__ . '/fa/faSleepIcon.php';
require_once __DIR__ . '/dbio/constants/spellSlotTypes.php';

require_once __DIR__ . '/classes/characterSummary.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/fa/faRunSpellIcon.php';
require_once __DIR__ . '/fa/faStopSpellIcon.php';

require_once __DIR__ . '/dbio/constants/cantripSpellSlot.php';

const MAX_HOURS_SLEEP = 8;

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$params = [];
$params[PLAYER_NAME] = $input[PLAYER_NAME];
$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

$url = CurlHelper::buildUrlDbioDirectory('getSpellBookForGreaterMage');
$raw_results = CurlHelper::performGetRequest($url, $params);

$availableSpells = json_decode($raw_results);

$spellListByLevel = [];

$url = CurlHelper::buildUrlDbioDirectory('getReadySpellsForPlayerCharacter');
$raw_results = CurlHelper::performGetRequest($url, $params);

$allRunningSpells = json_decode($raw_results);

$runningSpells = [];
if (!empty($allRunningSpells)) {
    $runningSpells = filterByGMOnlySlotTypes($allRunningSpells[0]);
}

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$prev_spell_level = -1;

$locale = 'en_US';
$nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_level = getCharacterLevelFromCharacterSummary($character_summary->getCharacterClasses());
$cantrip_select_html = '<select id="available_cantrip" name="available_cantrip" onchange="showCantrip()" style="font-size: 14pt;">' . PHP_EOL;

$page_title = $input[CHARACTER_NAME] . ' Spells';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editReadyGMSpells.js';
$page_specific_css = 'editReadyGMSpells.css';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
    <form name="slot-action-form" id="slot-action-form" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="castGMSpellCharacterAction" value="">
        <input type="hidden" name="<?= SPELL_CATALOG_ID ?>" id="<?= SPELL_CATALOG_ID ?>" value="">
        <input type="hidden" name="<?= SPELL_LEVEL ?>" id="<?= SPELL_LEVEL ?>" value="">
        <input type="hidden" name="<?= SPELL_DURATION ?>" id="<?= SPELL_DURATION ?>" value="">
        <input type="hidden" name="<?= SPELL_CASTING_TIME ?>" id ="<?= SPELL_CASTING_TIME?>" value="">
    </form>
    <form name="recover-spell-points" id="recover-spell-points" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="recoverSpellPointsCharacterAction" value="">
        <input type="hidden" name="<?= CHARACTER_LEVEL ?>" id="<?= CHARACTER_LEVEL ?>" value="<?= $character_level ?>">
        <input type="hidden" name="<?= HOURS_OF_SLEEP ?>" id="<?= HOURS_OF_SLEEP ?>" value="">
    </form>
    <form name="stop-action-form" id="stop-action-form" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl() ?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="stopGMSpellCharacterAction" value="">
        <input type="hidden" name="<?= SPELL_SLOT_ID ?>" id="<?= SPELL_SLOT_ID ?>" value="">
    </form>
    <div style="width: 100%;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <?php
    if (count($availableSpells) == 0) {
        echo '<h3>No spells available</h3>';
    } else {
        echo '<div class="row">' . PHP_EOL;
        $spell_points_class = $character_summary->getSpellPoints() > 0 ? "positive_spell_points" : "negative_spell_points";
        echo '  <span class="column_left"><span class="' . $spell_points_class . '">Spell Points: ' . $character_summary->getSpellPoints() . '</span></span>' . PHP_EOL;
        echo '  <span class="column_mid"><button onclick="window.location.reload();">End Of Round</button></span>' . PHP_EOL;
        echo '  <span class="column_right"><span class="recover_spell_points">' . buildRecoverSpellPointsForm() . '</span></span>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
        $prevSpellLevel = -1;
        $rowCounter = 0;
        echo '<table class="ready_spells">' . PHP_EOL;

        $cantrip_select_option_added = false;
        foreach($availableSpells AS $availableSpell) {
            $slot_action_row_id = buildActionSlotRowId($availableSpell);
            if ($availableSpell->spell_level == 0) {
                $option = buildCantripOptions($slot_action_row_id, $availableSpell->spell_name);
                if ($cantrip_select_option_added == false) {
                    $cantrip_select_html .= '<option value="slot-action-row-select">' . "[Select a Cantrip]" . '</option>' . PHP_EOL;
                    $cantrip_select_option_added = true;
                }
                    
                $cantrip_select_html .= $option;
            }

            if ($prevSpellLevel == 0 && $availableSpell->spell_level == 1) {
                $cantrip_select_html .= '</select>';
                echo '<tr><td colspan="6">Select : ' . $cantrip_select_html . '</td></tr>' . PHP_EOL;
            }

            if ($availableSpell->spell_level !=  $prevSpellLevel) {
                $spellLevel_header = buildSpellLevelHeader($availableSpell->spell_level, $nf);
                echo $spellLevel_header . PHP_EOL;
                $prevSpellLevel = $availableSpell->spell_level;
            }

            if ($character_summary->getSpellPoints() >= $availableSpell->spell_level) {
                $slotAction_html = buildCastSlotForm($input[PLAYER_NAME], $input[CHARACTER_NAME], $availableSpell);
                $backgroundColor = $rowCounter % 2 == 0 ? "white" : "lightgray";
                $hidden_row = $availableSpell->spell_level == 0 ? ' hidden' : '';
                echo '<tr style="background-color: '. $backgroundColor .'" id="' . $slot_action_row_id . '"' . $hidden_row . '>' . PHP_EOL . $slotAction_html . '</tr>' . PHP_EOL;
            }
            
            // Get running spell
            $running_spell_instances = isSpellRunning($availableSpell, $runningSpells);
            foreach($running_spell_instances AS $running_spell_instance) {
                $html_return = '<tr style="background-color: PaleTurquoise">' . PHP_EOL;
                $slotCastingTime = $running_spell_instance->player_slot_casting_time_remaining;
                $slotRunningTime = $running_spell_instance->player_slot_running_time_remaining;
                if($slotCastingTime > 0) {
                    // Casting
                    $html_return .= buildCastingSlotForm($running_spell_instance) . '</tr>' . PHP_EOL;
                }

                if(($slotCastingTime == -1 || $slotCastingTime == 0) && $slotRunningTime > 0) {
                    // Running 
                    $html_return .= buildRunningSlotForm($running_spell_instance) . '</tr>' . PHP_EOL;
                }

                echo $html_return;
            }

            $rowCounter++;
        }
    }
    ?>
</table>
</body>
</html>

<?php
/* Spellbook
      spell_type
      player_slot_level
      player_slot_spell_slot_type_id
      spell_name
      spell_link
      spell_slot_id
      has_spell_cast
      character_class_name
      spell_casting_time
      spell_range
      spell_duration
      spell_area_of_effect
      player_slot_casting_time_remaining
      player_slot_running_time_remaining
*/

/* Ready spell
      spell_type
      player_slot_level
      player_slot_spell_slot_type_id
      spell_name
      spell_link
      spell_slot_id
      has_spell_cast
      character_class_name
      spell_casting_time
      spell_range
      spell_duration
      spell_area_of_effect
      player_slot_casting_time_remaining
      player_slot_running_time_remaining
      spell_duration_in_rounds
      spell_casting_time_in_rounds
*/

function buildCastSlotForm($playerName, $characterName, $availableSpell) {
    $spellCatalogId = $availableSpell->spell_catalog_id;
    $spellLevel = $availableSpell->spell_level;

    $spellDuration = 0;
    if (!empty($availableSpell->spell_duration_in_rounds)) {
        if (is_numeric($availableSpell->spell_duration_in_rounds)) {
            $spellDuration = $availableSpell->spell_duration_in_rounds;
        }
    }

    $spellCastingTime = 0;
    if (!empty($availableSpell->spell_casting_time_in_rounds)) {
        if (is_numeric($availableSpell->spell_casting_time_in_rounds)) {
            $spellCastingTime = $availableSpell->spell_casting_time_in_rounds;
        }
    }

    $wandCastTag = buildCastSlotButtonTag($spellCatalogId, $spellLevel, $spellDuration, $spellCastingTime);

    $spellLink = $availableSpell->spell_link;
    $spellName = $availableSpell->spell_name;

    $spellLinkTag = '<a class="spell_slot_available" href="' . $spellLink . '" target="_blank">' . $spellName . '</a>';
    $spellCastingTime = $availableSpell->spell_casting_time;
    $spellRange = $availableSpell->spell_range;
    $spellDuration = $availableSpell->spell_duration;
    $spellAreaOfEffect = $availableSpell->spell_area_of_effect;
    
    return '<td class="spell_slot_available">' . $wandCastTag . '</td><td>' . $spellLinkTag . '</td><td class="spell_slot_available">' . $spellCastingTime . '</td><td class="spell_slot_available">' . $spellRange . '</td><td class="spell_slot_available">' . $spellDuration . '</td><td class="spell_slot_available">' . $spellAreaOfEffect . '</td>' . PHP_EOL;
}

function buildCastSlotButtonTag($spellCatalogId, $spellLevel, $spellDuration, $spellCastingTime) {
    $castSpellIcon = new FaCastSpellIcon();
    
    $castSpellIcon->setOnClickJsFunction('castGMSpell');
    $castSpellIcon->addOnclickJsParameter($spellCatalogId);
    $castSpellIcon->addOnclickJsParameter($spellLevel);
    $castSpellIcon->addOnclickJsParameter($spellDuration);
    $castSpellIcon->addOnclickJsParameter($spellCastingTime);
    $castSpellIcon->setHoverText('Cast Spell');

    return $castSpellIcon->build();
}

function buildRecoverSpellPointsForm() {
    $form_html  = buildHoursOfSleepDropdown();
    $form_html .= '&nbsp;' . 'Hours' . '&nbsp;';
    $form_html .= buildRecoverSpellPointsIcon();

    return $form_html;
}

function buildHoursOfSleepDropdown() {
    $dropdown_html = '<select id="hoursOfSleepInput" name="hoursOfSleepInput" style="font-size: 16pt;">' . PHP_EOL;
    for ($i = MAX_HOURS_SLEEP; $i > 0; $i--) {
        $dropdown_html .= '<option value="' . $i . '">' . $i . '</option>' . PHP_EOL;
    }

    $dropdown_html .= '</select>' . PHP_EOL;
    return $dropdown_html;
}

function filterByGMOnlySlotTypes($all_running_spells) {
    $final_spell_set = [];
    foreach($all_running_spells AS $running_spell) {
        if ($running_spell->player_slot_spell_slot_type_id == GM_EXTRA_SLOT_SKILL_SLOT_TYPE) { 
            $final_spell_set[] = $running_spell;
        }
    }
         
    return $final_spell_set;
}

function buildCastingSlotForm($readySpell) {
    $spellSlotId = $readySpell->spell_slot_id;
    $spellName = $readySpell->spell_name;
    $spellLink = $readySpell->spell_link;
    $castingSlotTag = buildCastingSlotButtonTag($spellSlotId);
    $castingTimeRemaining = $readySpell->player_slot_casting_time_remaining;

    $spellDuration = $readySpell->spell_duration;
    $spellAreaOfEffect = $readySpell->spell_area_of_effect;
    $spellLinkTag = '<a class="spell_slot_casting" href="' . $spellLink . '" target="_blank">' . $spellName . '</a>';
    return '<td class="spell_slot_casting">' . $castingSlotTag . '</td><td>' . $spellLinkTag . '</td><td class="spell_slot_casting">' . $castingTimeRemaining . '</td><td>&nbsp;</td><td class="spell_slot_casting">' . $spellDuration . '</td><td class="spell_slot_casting">' . $spellAreaOfEffect . '</td>' . PHP_EOL;
}

function buildRunningSlotForm($readySpell) {
    $spellSlotId = $readySpell->spell_slot_id;
    $spellName = $readySpell->spell_name;
    $spellLink = $readySpell->spell_link;
    $castingSlotTag = buildRunningSlotButtonTag($spellSlotId);
    
    $spellRunningTimeRemaining = $readySpell->player_slot_running_time_remaining;
    $spellAreaOfEffect = $readySpell->spell_area_of_effect;
    $spellLinkTag = '<a class="spell_slot_running" href="' . $spellLink . '" target="_blank">' . $spellName . '</a>';
    return '<td class="spell_slot_running">' . $castingSlotTag . '</td><td>' . $spellLinkTag . '</td><td>&nbsp;</td><td>&nbsp;</td><td class="spell_slot_running">' . $spellRunningTimeRemaining . '</td><td class="spell_slot_running">' . $spellAreaOfEffect . '</td>' . PHP_EOL;
}

function buildCastingSlotButtonTag($spellSlotId) {
    $castingSlotIcon = new FaStopSpellIcon();
    return buildSlotActionButtonTag($castingSlotIcon, CHARACTER_ACTION_STOP_CASTING_GM_SPELL, 'Stop casting spell', $spellSlotId);
}

function buildRunningSlotButtonTag($spellSlotId) {
    $runningSlotIcon = new FaRunSpellIcon();
    return buildSlotActionButtonTag($runningSlotIcon, CHARACTER_ACTION_STOP_RUNNING_GM_SPELL, 'Stop running spell', $spellSlotId);
}

function buildSlotActionButtonTag($slotActionIcon, $slotAction, $iconTitleText, $spellSlotId) {
    $slotActionIcon->setOnClickJsFunction('submitStopActionForm');
    $slotActionIcon->addOnclickJsParameter($spellSlotId);
    $slotActionIcon->addOnclickJsParameter($slotAction);
    $slotActionIcon->setHoverText($iconTitleText);

    return $slotActionIcon->build();
}

function isSpellRunning($available_spell, $running_spells) {
    $running_spells_active = [];
    foreach($running_spells AS $running_spell) {
        if ($available_spell->spell_name == $running_spell->spell_name && $available_spell->spell_level == $running_spell->player_slot_level) {
            $running_spells_active[] = $running_spell;
        }
    }

    return $running_spells_active;
}

function buildRecoverSpellPointsIcon() {
    $sleepIcon = new FaSleepIcon();
    $sleepIcon->setOnClickJsFunction('recoverSpellPoints');
    $sleepIcon->setHoverText('Sleep');

    return $sleepIcon->build();
}

function buildCantripOptions($row_id, $available_spell_name) {
    return '<option value="' . $row_id . '">' . $available_spell_name . '</option>' . PHP_EOL;
}

function getCharacterLevelFromCharacterSummary($character_classes) {
    foreach($character_classes AS $character_class) {
        return $character_class['character_level'];
    }
}

function buildSpellLevelHeader($spellLevel, $nf) {
    $spellLevelDesc = 'Cantrips';
    if ($spellLevel > 0) {
        $spellLevelDesc = $nf->format($spellLevel) . ' level';
    }

    $header = '<tr><th>' . $spellLevelDesc . '</th><th>Name</th><th>CT</th><th>Rng</th><th>Dur</th><th>AoE</th></tr>';
    return $header;
}

function buildActionBar($playerName, $characterName) {
    return ActionBarHelper::buildActionBar($playerName, $characterName);
}

function buildActionSlotRowId($availableSpell) {
    return 'slot-action-row-' . $availableSpell->player_spell_pool_id;
}

?>
