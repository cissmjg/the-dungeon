<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once 'CurlHelper.php';
require_once 'playerName.php';
require_once 'characterName.php';
require_once 'RestHeaderHelper.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'hiddenTag.php';

require_once 'faCancelIcon.php';
require_once 'faCastSpellIcon.php';
require_once 'faEditIcon.php';
require_once 'faRefreshIcon.php';
require_once 'faMemorizeSpellIcon.php';
require_once 'faPraySpellIcon.php';
require_once 'faHealSpellIcon.php';
require_once 'faNatureIcon.php';
require_once 'faReclaimCantripIcon.php';
require_once 'faStopSpellIcon.php';
require_once 'faRunSpellIcon.php';

require_once 'characterAttributes.php';
require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';

require_once 'emptySpellSlot.php';
require_once 'cantripSpellSlot.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$params = [];
$params['playerName'] = $input['playerName'];
$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

$url = CurlHelper::buildUrl('getReadySpellsForPlayerCharacter');
$raw_results = CurlHelper::performGetRequest($url, $params);

$readySpells = json_decode($raw_results);

$spellListByClassAndLevel = [];

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input['playerName'], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$prev_spell_level = -1;

/*
    spell_type
    player_slot_level
    spell_name
    spell_link
    spell_slot_id
	has_spell_cast
    character_class_name
    spell_casting_time
    spell_range
    spell_duration
*/
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
    <script src="submitTheForm.js" type="text/javascript"></script>
    <script src="editReadySpells.js" type="text/javascript"></script>
</head>
<body>
    <form name="slot-action-form" id="slot-action-form" method="POST" action="<?= CurlHelper::buildUrl('characterActionRouter')?>">
        <input type="hidden" name="playerName" id="playerName" value="<?= $input['playerName'] ?>">
        <input type="hidden" name="characterName" id="characterName" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="characterAction" id="characterAction" value="">
        <input type="hidden" name="spellSlotId" id="spellSlotId" value="">
        <input type="hidden" id="spellDuration" name="spellDuration" value="">
        <input type="hidden" id="spellCastingTime" name="spellCastingTime" value="">
    </form>
    <?php
    $locale = 'en_US';
    $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
    $action_bar = ActionBarHelper::buildActionBar($input['playerName'], $input[CHARACTER_NAME]);
    echo '<div style="width: 100%;"><span class="character_summary">' . $character_summary_stats . '</span><span class="action_bar">' . $action_bar . '</span></div>';
    if (count($readySpells) == 0) {
        echo '<h3>No spells available</h3>';
    } else {
        echo '<div style="width: 100%; text-align: center; padding-bottom: 3px;"><button onclick="window.location.reload();">End Of Round</button></div>' . PHP_EOL;
        $prevSpellType = '';
        $prevSlotLevel = -1;
        $spellPoolList = null;
        $rowCounter = 0;
        echo '<table class="ready_spells">' . PHP_EOL;
        foreach($readySpells AS $spellCollectionForClass) {
            foreach($spellCollectionForClass AS $readySpell) {
                if ($readySpell->spell_type != $prevSpellType) {
                    $prevSpellType = $readySpell->spell_type;
                    $prevSlotLevel = -1;
                    $spellListByClassAndLevel[$readySpell->character_class_name] = array();
                    $spellType_header = buildSpellTypeHeader($readySpell->spell_type);
                    echo $spellType_header . PHP_EOL;
                    $spellLevel_header = buildSpellLevelHeader($readySpell->player_slot_level, $nf);
                    echo $spellLevel_header . PHP_EOL;
                }

                if ($readySpell->player_slot_level !=  $prevSlotLevel) {
                    if ($prevSlotLevel != -1) {
                        $spellLevel_header = buildSpellLevelHeader($readySpell->player_slot_level, $nf);
                        echo $spellLevel_header . PHP_EOL;
                    }

                    $prevSlotLevel = $readySpell->player_slot_level;
                    $spellPoolList = getSpellPoolForLevelAndType($input, $readySpell->character_class_name, $readySpell->player_slot_level, $readySpell->spell_type);

                    $spellListByClassAndLevel[$readySpell->character_class_name][$readySpell->player_slot_level] = $spellPoolList;
                }
                $slotAction_html = buildSlotActionHtml($readySpell, $input, $readySpells);
                $backgroundColor = $rowCounter % 2 == 0 ? "white" : "lightgray";
                echo '<tr style="background-color: '. $backgroundColor .'" id="' . buildActionSlotRowId($readySpell) . '">' . PHP_EOL . $slotAction_html . '</tr>' . PHP_EOL;
                $slot_change_html = buildSlotChangeHtml($input['playerName'], $input[CHARACTER_NAME], $readySpell->character_class_name, $readySpell, $spellListByClassAndLevel[$readySpell->character_class_name], $nf);
                $backgroundStyle = getBackgroundStyle($readySpell->spell_type);
                echo '<tr style="' . $backgroundStyle . '" id="' . buildChangeSlotRowId($readySpell) . '" hidden>' . PHP_EOL . $slot_change_html . '</tr>' . PHP_EOL;
                $rowCounter++;
            }
        }

        echo '</table>';
    }
    echo '</div>';
    ?>
</body>
</html>

<?php
function getBackgroundStyle($spellType) {
    if ($spellType == 'Magic-User' || $spellType == 'Illusionist'|| $spellType == 'Wu Jen') {
        return "background-color:rgba(0, 0, 255, 0.15)";
    } else if ($spellType == 'Healer') {
        return "background-color:rgba(128, 0, 0, 0.25);";
    } else if ($spellType == 'Druid') {
        return "background-color:rgba(0, 250, 154, 0.25);";
    } 
    
    return "background-color:rgba(34, 139, 34, 0.25);";
}

function buildClassSpecificSpellIcon($spellType, $updateFormId, $updateFormCharacterActionId) {
    $spellActionIcon = null;
    if ($spellType == 'Magic-User' || $spellType == 'Illusionist'|| $spellType == 'Wu Jen') {
        $spellActionIcon = new FaMemorizeSpellIcon();
    } else if ($spellType == 'Healer') {
        $spellActionIcon = new FaHealSpellIcon();
    } else if ($spellType == 'Druid') {
        $spellActionIcon = new FaNatureIcon();
    } else {
        $spellActionIcon = new FaPraySpellIcon();
    }

    $spellActionIcon->setOnClickJsFunction('submitTheForm');
    $spellActionIcon->addOnclickJsParameter($updateFormId);
    $spellActionIcon->addOnclickJsParameter($updateFormCharacterActionId);
    $spellActionIcon->addOnclickJsParameter('updateReadySpellSlot');

    return  $spellActionIcon->build();
}

function buildSlotChangeHtml($playerName, $characterName, $characterClassName, $readySpell, $spellPoolListByLevel, $nf) {
    $changeSlotHtml = buildSlotChangeForm($playerName, $characterName, $characterClassName, $readySpell, $spellPoolListByLevel, $nf);

    return '<td colspan="7">' . $changeSlotHtml . '</td>';
}

/*
    spell_type
    player_slot_level
    spell_name
    spell_link
    spell_slot_id
	has_spell_cast
    character_class_name
    spell_casting_time
    spell_range
    spell_duration
*/

function buildSlotChangeForm($playerName, $characterName, $characterClassName, $readySpell, $spellListByLevel, $nf) {
    $spellName = $readySpell->spell_name;
    $spellSlotId = $readySpell->spell_slot_id;
    $spellLevel = $readySpell->player_slot_level;
    $spellType = $readySpell->spell_type;

    $formId = buildSlotChangeFormId($spellSlotId);
    $characterActionId = buildSlotChangeCharacterActionId($spellSlotId);
    $formStartHtml = buildSlotActionFormStart($formId, $spellSlotId, $playerName, $characterName, $characterActionId);  // player, character, slot, action
    $characterClassNameTag = buildHiddenTag('characterClassName', $characterClassName);
    $spellLevelTag = buildHiddenTag('spellLevel', $spellLevel);

    $spellNameTag = '<span>' . $spellName . '</span>&nbsp;';
    $leftArrowHtml = buildLeftArrowHtml() . '&nbsp;';
    $formSpellList = buildCandidateOptionsForTypeAndLevel($spellListByLevel,  $spellType, $spellLevel, $nf);
    $spellActionIcon = buildClassSpecificSpellIcon($spellType, $formId, $characterActionId) . '&nbsp;';
    $faCancelIcon = buildCancelChangeFormIcon($readySpell);
    
    return $formStartHtml . $characterClassNameTag . $spellLevelTag . $spellNameTag . $leftArrowHtml . $formSpellList . '&nbsp;' . $spellActionIcon . $faCancelIcon . '</form>';
}

function buildCancelChangeFormIcon($readySpell) {
    $spellRowId = buildActionSlotRowId($readySpell);
    $formId = buildChangeSlotRowId($readySpell);
    
    $faCancelIcon = new FaCancelIcon();
    $faCancelIcon->setOnClickJsFunction('showSpellHideChangeForm');
    $faCancelIcon->addOnclickJsParameter($spellRowId);
    $faCancelIcon->addOnclickJsParameter($formId);

    return $faCancelIcon->build();
}

function buildShowChangeFormIcon($readySpell) {
    $spellRowId = buildActionSlotRowId($readySpell);
    $formId = buildChangeSlotRowId($readySpell);

    $faEditIcon = new FaEditIcon();
    $faEditIcon->setOnClickJsFunction('hideSpellShowChangeForm');
    $faEditIcon->addOnclickJsParameter($spellRowId);
    $faEditIcon->addOnclickJsParameter($formId);
    $faEditIcon->setHoverText('Change Spell');

    return $faEditIcon->build();
}

function buildSpellTypeHeader($spellType) {
    $header = '<th class="spell_type_header" colspan="7">' . $spellType . ' spells' . '</th>';
    return $header;
}

function buildSpellLevelHeader($spellLevel, $nf) {
    $spellLevelDesc = 'Cantrips';
    if ($spellLevel > 0) {
        $spellLevelDesc = $nf->format($spellLevel) . ' level';
    }

    $header = '<tr><th>' . $spellLevelDesc . '</th><th>Name</th><th>&nbsp;</th><th>CT</th><th>Rng</th><th>Dur</th><th>AoE</th></tr>';
    return $header;
}

function getSpellPoolForLevelAndType($input, $characterClassName, $playerSlotLevel, $spellType) {

    $playerSlotLevel = $playerSlotLevel == 0 ? -1 : $playerSlotLevel;
    
    $params = [];
    $params['playerName'] = $input['playerName'];
    $params[CHARACTER_NAME] = $input[CHARACTER_NAME];
    $params['characterClassName'] = $characterClassName;
    $params['spellLevel'] = $playerSlotLevel;
    $params['removeEmpty'] = true;
    $params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

    $url = CurlHelper::buildUrl('getSpellPoolForPlayerCharacter');
    $spellPool = CurlHelper::performGetRequest($url, $params);
    $allSpellsForLevel = json_decode($spellPool);

    $spellPoolList = [];
    foreach($allSpellsForLevel AS $spellForLevel) {
        if ($characterClassName == 'Healer') {
            $spellPoolList[] = $spellForLevel;
        } else {
            if($spellForLevel->spell_type_name == $spellType) {
                $spellPoolList[] = $spellForLevel;
            }
        }
    }
    return $spellPoolList;
}

function buildSlotActionHtml($readySpell, $input, $readySpells) {
    if ($readySpell->spell_name == EMPTY_SLOT_SPELL_NAME) {
        $htmlReturn = buildUpdateNoneSlot($readySpell);
    } else if ($readySpell->spell_name == CANTRIP_SLOT_SPELL_NAME) {
        $htmlReturn = buildReclaimCantripSlot($readySpell, $input['playerName'], $input[CHARACTER_NAME]);
    } else {
        if (!$readySpell->has_spell_cast) {
            $htmlReturn = buildCastSlotForm($readySpell->spell_slot_id, $readySpell->spell_name, $readySpell->spell_link, $input['playerName'], $input[CHARACTER_NAME], $readySpell, $readySpells);
        } else {
            $slotCastingTime = $readySpell->player_slot_casting_time_remaining;
            $slotRunningTime = $readySpell->player_slot_running_time_remaining;
            if(($slotCastingTime == -1 && $slotRunningTime == -1) || ($slotCastingTime == 0 && $slotRunningTime == 0)) {
                // Refresh 
                $htmlReturn = buildResetSlotForm($readySpell->spell_slot_id, $readySpell->spell_name, $readySpell->spell_link, $readySpell);
            }

            if($slotCastingTime > 0) {
                // Casting
                $htmlReturn = buildCastingSlotForm($readySpell->spell_slot_id, $readySpell->spell_name, $readySpell->spell_link, $readySpell);           
            }

            if(($slotCastingTime == -1 || $slotCastingTime == 0) && $slotRunningTime > 0) {
                // Running 
                $htmlReturn = buildRunningSlotForm($readySpell->spell_slot_id, $readySpell->spell_name, $readySpell->spell_link, $readySpell);           
            }
        }
    }

    return $htmlReturn;
}

function buildUpdateNoneSlot($readySpell) {
    $spellName = $readySpell->spell_name;

    $updateNoneIcon = buildShowChangeFormIcon($readySpell);

    $sourceSpellNameContainerId = buildSourceSpellNameContainer($readySpell->spell_slot_id);

    return '<td>&nbsp;</td><td><span id="' . $sourceSpellNameContainerId . '" class="spell_slot_none">'. $spellName . '</span></td>' . '<td style="text-align: center;">' . $updateNoneIcon . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
}

// Refactor
function buildReclaimCantripSlot($readySpell, $playerName, $characterName) {
    $spellName = $readySpell->spell_name;

    $formId = buildReclaimCantripFormId($readySpell->spell_slot_id);
    $characterActionId = buildReclaimCantripCharacterActionId($readySpell->spell_slot_id);
    $formStart = buildSlotActionFormStart($formId, $readySpell->spell_slot_id, $playerName, $characterName, $characterActionId);
    
    $outputHtml = '';
    $outputHtml .= $formStart;
    $outputHtml .= '<td>&nbsp;</td>';
    $outputHtml .= '<td><span class="spell_slot_cantrip">' . $spellName . '</span></td>';
 
    $reclaimCantripIcon =  buildReclaimCantripIcon($formId, $characterActionId);

    $outputHtml .= '<td style="text-align: center;">' . $reclaimCantripIcon . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
    $outputHtml .= '</form>' . PHP_EOL;

    return $outputHtml;
}

function buildCastSlotForm($spellSlotId, $spellName, $spellLink, $playerName, $characterName, $readySpell, $readySpells) {
    $spellDurationInRounds = "0";
    if(isset($readySpell->spell_duration_in_rounds)) {
        $spellDurationInRounds = $readySpell->spell_duration_in_rounds;
    }
    
    $spellCastingTimeInRounds = "0";
    if(isset($readySpell->spell_casting_time_in_rounds)) {
        $spellCastingTimeInRounds = $readySpell->spell_casting_time_in_rounds;
    }

    $updateSpellSlotIcon = buildShowChangeFormIcon($readySpell);
    $wandCastTag = buildCastSlotButtonTag($spellSlotId, $spellDurationInRounds, $spellCastingTimeInRounds);

    $spellLinkTag = '<a class="spell_slot_available" href="' . $spellLink . '" target="_blank">' . $spellName . '</a>';
    $spellCastingTime = $readySpell->spell_casting_time;
    $spellRange = $readySpell->spell_range;
    $spellDuration = $readySpell->spell_duration;
    $spellAreaOfEffect = $readySpell->spell_area_of_effect;
    
    return '<td class="spell_slot_available">' . $wandCastTag . '</td><td>' . $spellLinkTag . '</td><td class="spell_slot_available">' . $updateSpellSlotIcon . '</td><td class="spell_slot_available">' . $spellCastingTime . '</td><td class="spell_slot_available">' . $spellRange . '</td><td class="spell_slot_available">' . $spellDuration . '</td><td class="spell_slot_available">' . $spellAreaOfEffect . '</td>' . PHP_EOL;
}

function buildResetSlotForm($spellSlotId, $spellName, $spellLink, $readySpell) {
    $updateSpellSlotIcon = buildShowChangeFormIcon($readySpell);
    $refreshResetTag = buildResetSlotButtonTag($spellSlotId);

    $spellDuration = "";
    if(!isset($readySpell->spell_duration_in_rounds)) {
        $spellDuration = $readySpell->spell_duration;
    }

    $spellLinkTag = '<a class="spell_slot_cast" href="' . $spellLink . '" target="_blank">' . $spellName . '</a>';
    return '<td class="spell_slot_cast">' . $refreshResetTag . '</td><td>' . $spellLinkTag . '</td><td class="spell_slot_cast">' . $updateSpellSlotIcon . '</td><td>&nbsp;</td><td>&nbsp;</td><td class="spell_slot_cast">' . $spellDuration . '</td><td>&nbsp;</td>' . PHP_EOL;
}

function buildCastingSlotForm($spellSlotId, $spellName, $spellLink, $readySpell) {
    $updateSpellSlotIcon = buildShowChangeFormIcon($readySpell);
    $castingSlotTag = buildCastingSlotButtonTag($spellSlotId);
    $castingTimeRemaining = $readySpell->player_slot_casting_time_remaining;

    $spellDuration = $readySpell->spell_duration;
    $spellAreaOfEffect = $readySpell->spell_area_of_effect;
    $spellLinkTag = '<a class="spell_slot_casting" href="' . $spellLink . '" target="_blank">' . $spellName . '</a>';
    return '<td class="spell_slot_casting">' . $castingSlotTag . '</td><td>' . $spellLinkTag . '</td><td class="spell_slot_casting">' . $updateSpellSlotIcon . '</td><td class="spell_slot_casting">' . $castingTimeRemaining . '</td><td>&nbsp;</td><td class="spell_slot_casting">' . $spellDuration . '</td><td class="spell_slot_casting">' . $spellAreaOfEffect . '</td>' . PHP_EOL;
}

function buildRunningSlotForm($spellSlotId, $spellName, $spellLink, $readySpell) {
    $updateSpellSlotIcon = buildShowChangeFormIcon($readySpell);
    $castingSlotTag = buildRunningSlotButtonTag($spellSlotId);
    
    $spellRunningTimeRemaining = $readySpell->player_slot_running_time_remaining;
    $spellAreaOfEffect = $readySpell->spell_area_of_effect;
    $spellLinkTag = '<a class="spell_slot_running" href="' . $spellLink . '" target="_blank">' . $spellName . '</a>';
    return '<td class="spell_slot_running">' . $castingSlotTag . '</td><td>' . $spellLinkTag . '</td><td class="spell_slot_running">' . $updateSpellSlotIcon . '</td><td>&nbsp;</td><td>&nbsp;</td><td class="spell_slot_running">' . $spellRunningTimeRemaining . '</td><td class="spell_slot_running">' . $spellAreaOfEffect . '</td>' . PHP_EOL;
}

function buildSlotActionFormStart($formId, $spellSlotId, $playerName, $characterName, $characterActionId) {
    $formStartTag = '<form ';
    $formStartTag .= 'id="' . $formId . '" name="' . $formId .'" ';
    $routerActionUrl = CurlHelper::buildUrl('characterActionRouter');
    $formStartTag .= 'action="' . $routerActionUrl . '" ';
    $formStartTag .= 'method="POST">';
    $playerNameTag = buildHiddenTag('playerName', $playerName);
    $characterNameTag = buildHiddenTag(CHARACTER_NAME, $characterName);
    $spellSlotIdTag = buildHiddenTag('spellSlotId', $spellSlotId);
    $characterActionTag = buildHiddenTagWithId('characterAction', $characterActionId, '');
    return $formStartTag . $playerNameTag . $characterNameTag . $spellSlotIdTag . $characterActionTag;
}

function buildCastSlotButtonTag($spellSlotId, $spellDuration, $spellCastingTime) {
    $castSpellIcon = new FaCastSpellIcon();
    return buildSlotActionButtonTag($castSpellIcon, 'castSpellSlot', 'Cast Spell', $spellSlotId, $spellDuration, $spellCastingTime);
}

function buildResetSlotButtonTag($spellSlotId) {
    $refreshSlotIcon = new FaRefreshIcon();
    return buildSlotActionButtonTag($refreshSlotIcon, 'resetSpellSlot', "Refresh spell", $spellSlotId, '0', '0');
}

function buildCastingSlotButtonTag($spellSlotId) {
    $castingSlotIcon = new FaStopSpellIcon();
    return buildSlotActionButtonTag($castingSlotIcon, 'stopCastingSpellSlot', 'Stop casting spell', $spellSlotId, '0', '0');
}

function buildRunningSlotButtonTag($spellSlotId) {
    $runningSlotIcon = new FaRunSpellIcon();
    return buildSlotActionButtonTag($runningSlotIcon, 'stopRunningSpellSlot', 'Stop running spell', $spellSlotId, '0', '0');
}

function buildSlotActionButtonTag($slotActionIcon, $slotAction, $iconTitleText, $spellSlotId, $spellDuration, $spellCastingTime) {
    $slotActionIcon->setOnClickJsFunction('submitSlotActionForm');
    $slotActionIcon->addOnclickJsParameter($spellSlotId);
    $slotActionIcon->addOnclickJsParameter($slotAction);
    $slotActionIcon->addOnclickJsParameter($spellDuration);
    $slotActionIcon->addOnclickJsParameter($spellCastingTime);
    $slotActionIcon->setHoverText($iconTitleText);

    return $slotActionIcon->build();
}

function buildReclaimCantripIcon($formId, $characterActionId) {
    $reclaimCantripIcon = new FaReclaimCantripIcon();
    $reclaimCantripIcon->setOnClickJsFunction('submitTheForm');
    $reclaimCantripIcon->addOnclickJsParameter($formId);
    $reclaimCantripIcon->addOnclickJsParameter($characterActionId);
    $reclaimCantripIcon->addOnclickJsParameter('reclaimCantripSlots');
    
    return $reclaimCantripIcon->build();
}

function buildCandidateOptionsForTypeAndLevel($spellListByLevel, $spellType, $spellLevel, $nf) {
    $spellCatalogFormId = buildUpdateSpellCatalogId($spellType, $spellLevel);

    $tagName = 'spellCatalogId';
    $selectTag = '<select id="' . $spellCatalogFormId . '" name="' .  $tagName . '" ' . ' style="font-size: 18px;">' . PHP_EOL;
    $optionList = buildOptionsForSpellList($spellListByLevel, $spellLevel, $nf);

    return $selectTag . $optionList . '</select>' . PHP_EOL;
}

function buildOptionsForSpellList($spellListByLevel, $spellLevel, $nf) {
    $optionList = '';
    if ($spellLevel == 0) {
        $optionList .= '<optgroup label="Cantrips">' . PHP_EOL;

        // spell list by level
        $spells = $spellListByLevel[0];
        foreach($spells AS $spell) {
            $optionList .= '<option value="' . $spell->spell_catalog_id .'">' . $spell->spell_name . "</option>" . PHP_EOL;
        }

        $optionList .= '</optgroup>' . PHP_EOL;
    } else {
        for($i = $spellLevel; $i > 0; $i--) {
            $optionList .= '<optgroup label="' . $nf->format($i) . ' level">' . PHP_EOL;

            // spell list by level
            $spells = $spellListByLevel[$i];
            foreach($spells AS $spell) {
                $optionList .= '<option value="' . $spell->spell_catalog_id .'">' . $spell->spell_name . "</option>" . PHP_EOL;
            }

            if ($spellLevel > 0) {
                $optionList .= '<option value="' . CANTRIP_SLOT_SPELL_CATALOG_ID . '">' . CANTRIP_SLOT_SPELL_NAME . '</option>' . PHP_EOL;
            }

            $optionList .= '</optgroup>' . PHP_EOL;
        }
    }

    return $optionList;
}

function buildLeftArrowHtml() {
    return '<span class="fa-solid fa-arrow-left"></span>';
}

function buildSourceSpellNameContainer($spellSlotId) {
    return 'source-spell-name-id-' . $spellSlotId;
}

function buildUpdateSpellCatalogId($spellType, $spellLevel) {
    return 'update-spell-catalog-id-' .  $spellType . '-' . $spellLevel;
}

function buildReclaimCantripFormId($spellPoolId) {
    return 'reclaim-cantrips-form-id-' . $spellPoolId;
}

function buildReclaimCantripCharacterActionId($spellPoolId) {
    return 'reclaim-cantrips-character-action-id-' . $spellPoolId;
}

function buildActionSlotRowId($readySpell) {
    return 'slot-action-row-' . $readySpell->spell_slot_id;
}

function buildChangeSlotRowId($readySpell) {
    return 'slot-change-row-' . $readySpell->spell_slot_id;
}

function buildSlotChangeFormId($spellSlotId) {
    return 'slot-change-form-id-' . $spellSlotId;
}

function buildSlotChangeCharacterActionId($spellSlotId) {
    return 'slot-change-character-action-id-' . $spellSlotId;
}

?>
