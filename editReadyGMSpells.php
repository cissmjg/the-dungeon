<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'hiddenTag.php';

require_once 'faCastSpellIcon.php';
require_once 'faSleepIcon.php';
require_once 'spellSlotTypes.php';

require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';
require_once 'faRunSpellIcon.php';
require_once 'faStopSpellIcon.php';

require_once 'cantripSpellSlot.php';

const MAX_HOURS_SLEEP = 8;

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$params = [];
$params[PLAYER_NAME] = $input[PLAYER_NAME];
$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

$url = CurlHelper::buildUrl('getSpellBookForGreaterMage');
$raw_results = CurlHelper::performGetRequest($url, $params);

$availableSpells = json_decode($raw_results);

$spellListByLevel = [];

$url = CurlHelper::buildUrl('getReadySpellsForPlayerCharacter');
$raw_results = CurlHelper::performGetRequest($url, $params);

$allRunningSpells = json_decode($raw_results);

$runningSpells = [];
if (!empty($allRunningSpells)) {
    $runningSpells = filterByGMOnlySlotTypes($allRunningSpells[0]);
}

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$prev_spell_level = -1;

$locale = 'en_US';
$nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_level = getCharacterLevel($character_summary->getCharacterClasses());
$cantrip_select_html = '<select id="available_cantrip" name="available_cantrip" onchange="showCantrip()" style="font-size: 14pt;">' . PHP_EOL;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $input[CHARACTER_NAME] ?> Spells</title>
	<link rel="stylesheet" href="dnd-default.css">
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
    <script src="editReadyGMSpells.js" type="text/javascript"></script>
    <script src="submitTheForm.js" type="text/javascript"></script>
    <style>
        .column_left {
            float: left;
            width: 33.33%;
            text-align: left;
            background-color: PaleTurquoise;
            /* display: inline-block; */
        }

        .column_mid {
            float: left;
            width: 33.33%;
            text-align: center;
            background-color: PaleTurquoise;
            /* display: inline-block; */
        }

        .column_right {
            float: left;
            width: 33.33%;
            text-align: right;
            background-color: PaleTurquoise;
            /* display: inline-block; */
        }

        .row {
            width: 100%;
            padding-bottom: 2px;
            padding-top: 2px;
            display: flex;
            flex-direction: row;
            background-color: PaleTurquoise;
        }

        /* Clear floats after the columns */
        row:after {
            content: "";
            display: table;
            clear: both;
        }

        .positive_spell_points {
            padding-left: 3px;
            font-size: 20pt;
            color: blue;
        }

        .negative_spell_points {
            padding-left: 3px;
            font-size: 20pt;
            color: red;
        }

        .recover_spell_points {
            font-size: 20pt;
            padding-right: 3px;
        }
    </style>
</head>
<body>
    <form name="slot-action-form" id="slot-action-form" method="POST" action="<?= CurlHelper::buildUrl('characterActionRouter') ?>">
        <input type="hidden" name="playerName" id="playerName" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="characterName" id="characterName" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="characterAction" id="castGMSpellCharacterAction" value="">
        <input type="hidden" name="spellPoolId" id="spellPoolId" value="">
        <input type="hidden" name="spellCatalogId" id="spellCatalogId" value="">
        <input type="hidden" name="spellLevel" id="spellLevel" value="">
        <input type="hidden" name="spellDuration" id="spellDuration" value="">
        <input type="hidden" name="spellCastingTime" id ="spellCastingTime" value="">
    </form>
    <form name="recover-spell-points" id="recover-spell-points" method="POST" action="<?= CurlHelper::buildUrl('characterActionRouter') ?>">
        <input type="hidden" name="playerName" id="playerName" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="characterName" id="characterName" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="characterAction" id="recoverSpellPointsCharacterAction" value="">
        <input type="hidden" name="characterLevel" id="characterLevel" value="<?= $character_level ?>">
        <input type="hidden" name="hoursOfSleep" id="hoursOfSleep" value="">
    </form>
    <form name="stop-action-form" id="stop-action-form" method="POST" action="<?= CurlHelper::buildUrl('characterActionRouter') ?>">
        <input type="hidden" name="playerName" id="playerName" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="characterName" id="characterName" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="characterAction" id="stopGMSpellCharacterAction" value="">
        <input type="hidden" name="spellSlotId" id="spellSlotId" value="">
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

        foreach($availableSpells AS $availableSpell) {
            if ($availableSpell->spell_level == 0) {
                $option = buildCantripOptions($slot_action_row_id, $availableSpell->spell_name);
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
                $slot_action_row_id = buildActionSlotRowId($availableSpell);
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
<div>
    <pre>
        <?= $debug_output ?>
    </pre>
</div>
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
    return buildSlotActionButtonTag($castingSlotIcon, 'stopCastingGMSpellSlot', 'Stop casting spell', $spellSlotId);
}

function buildRunningSlotButtonTag($spellSlotId) {
    $runningSlotIcon = new FaRunSpellIcon();
    return buildSlotActionButtonTag($runningSlotIcon, 'stopRunninGMSpellSlot', 'Stop running spell', $spellSlotId);
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

function getCharacterLevel($character_classes) {
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

function getBackgroundStyle($spellType) {
    return "background-color:rgba(0, 0, 255, 0.15)";
}

function buildActionBar($playerName, $characterName) {
    return ActionBarHelper::buildActionBar($playerName, $characterName);
}

function buildActionSlotRowId($availableSpell) {
    return 'slot-action-row-' . $availableSpell->player_spell_pool_id;
}

?>
