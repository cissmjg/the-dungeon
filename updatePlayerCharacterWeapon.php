<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';

require_once 'playerCharacterWeapon.php';
require_once 'weaponType.php';
require_once 'weaponSubtype.php';
require_once 'characterClasses.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
require_once __DIR__ . '/webio/craftStatus.php';
require_once 'weapons.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once 'playerCharacterWeaponId.php';
require_once 'playerCharacterWeaponSkillId.php';
require_once __DIR__ . '/webio/weaponDescription.php';
require_once __DIR__ . '/webio/weaponLocation.php';
require_once __DIR__ . '/webio/isProficient.php';
require_once __DIR__ . '/webio/isReady.php';
require_once __DIR__ . '/webio/isPreferred.php';
require_once __DIR__ . '/webio/craftStatus.php';
require_once __DIR__ . '/webio/strengthBonusAvailable.php';
require_once 'playerNote1.php';
require_once 'playerNote2.php';
require_once 'playerNote3.php';
require_once 'meleeWeaponType.php';
require_once 'meleeWeaponSpeed.php';
require_once 'meleeWeaponDamage.php';
require_once 'meleeAttacksPerRound.php';
require_once 'meleeNumberOfHands.php';
require_once 'meleeAdditionalText.php';
require_once 'missileWeaponType.php';
require_once 'missileWeaponSpeed.php';
require_once 'missileWeaponDamage.php';
require_once 'missileAttacksPerRound.php';
require_once 'missileAdditionalText.php';
require_once 'missileShortRange.php';
require_once 'missileMediumRange.php';
require_once 'missileLongRange.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponId($errors, $input);

$playerCharacterWeapon = getPlayerCharacterWeapon($pdo, $input['playerCharacterWeaponId'], $errors);

if(!empty($errors)) {
    die($errors);
}

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$mastercraft_hidden = " hidden";
$magic_hidden = " hidden";

$craft_status_artisan_selected = "";
$craft_status_mastercraft_selected = "";
$craft_status_magic_selected = "";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $input[CHARACTER_NAME] ?> Weapons</title>
	<link rel="stylesheet" href="dnd-default.css">
	<link rel="stylesheet" href="characterSheet.css">
    <script src="../js/jquery-1.12.4.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="./env.js" type="module"></script>
    <script src="./RestHelper.js" type="module"></script>
    <script src="./playerCharacterWeaponIO.js" type="module"></script>
    <script src="characterSheetContainer.js"></script>
    <script type="module">
        import { populateWeaponLocation, craftStatusChanged, updateHitBonus, updateDamageBonus, populateDefaultHitDamageBonuses } from './playerCharacterWeaponIO.js';

        // Attach to global scope
        window.populateWeaponLocation = populateWeaponLocation;
        window.craftStatusChanged = craftStatusChanged;
        window.updateHitBonus = updateHitBonus;
        window.updateDamageBonus = updateDamageBonus;
        window.populateDefaultHitDamageBonuses = populateDefaultHitDamageBonuses;
    </script>
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
    <script src="submitTheForm.js"></script>
    <style>
        label {
            color: DarkSlateGrey;
            font-size: 14px;
        }

        .inputRow {
            padding-bottom: 3px;
        }

        .masterCraftSection {
            width: 350px; 
            border: 2px solid green; 
            margin-top: 10px; 
            padding-top: 3px; 
            margin-bottom: 10px; 
            padding-bottom: 3px; 
            padding-left: 3px;
        }

        .magicSection {
            width: 400px; 
            border: 2px solid green; 
            margin-top: 10px; 
            padding-top: 3px; 
            margin-bottom: 10px; 
            padding-bottom: 3px; 
            padding-left: 3px;
        }
    </style>
</head>
<body>
    <div style="width: 100%;"><span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span></div>
    <div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Weapon Details</div>
    <?php if ($playerCharacterWeapon != null): ?>
    <div id="updatePlayerCharacterWeaponContainer">
        <form name="updatePlayerCharacterWeapon" id="updatePlayerCharacterWeapon" method="POST" action="<?= CurlHelper::buildUrl('updateWeaponForPlayerCharacter'); ?>">
            <input type="hidden" name="playerName" value="<?= $input[PLAYER_NAME] ?>">
            <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
            <input type="hidden" name="playerCharacterWeaponId" value="<?= $playerCharacterWeapon->getWeaponId() ?? '0'?>">
            <input type="hidden" name="weaponProficiencyId" value="<?= $playerCharacterWeapon->getWeaponProficiencyId() ?>">
            <h3><?= $playerCharacterWeapon->getWeaponDescription(); ?></h3>
            <div class="inputRow"><label for="weaponDescription">Weapon Name: </label><input type="text" name="weaponDescription" id="weaponDescription" maxlength="32" value="<?= $playerCharacterWeapon->getWeaponDescription(); ?>"></div>
            <div class="inputRow"><label for="weaponLocation">Weapon Location: </label><input type="text" name="weaponLocation" id="weaponLocation" maxlength="32" value="<?= $playerCharacterWeapon->getWeaponLocation() ?>"> <select id="weaponLocationHints" onchange="populateWeaponLocation('weaponLocationHints', WEAPON_LOCATION);">
                <option value=""></option>
                <option value="Lower Left Leg">Lower Left Leg</option>
                <option value="Upper Left Leg">Upper Left Leg</option>
                <option value="Lower Right Leg">Lower Right Leg</option>
                <option value="Upper Right Leg">Upper Right Leg</option>
                <option value="Lower Left Arm">Lower Left Arm</option>
                <option value="Upper Left Arm">Upper Left Arm</option>
                <option value="Lower Right Arm">Lower Right Arm</option>
                <option value="Upper Right Arm">Upper Right Arm</option>
                <option value="Left Belt">Left Belt</option>
                <option value="Right Belt">Right Belt</option>
                <option value="Back Left">Back Left</option>
                <option value="Back Right">Back Right</option>
                <option value="Back Center">Back Center</option>
                <option value="Left Belt Pouch">Left Belt Pouch</option>
                <option value="Right Belt Pouch">Right Belt Pouch</option>
                <option value="Left Wizard Bag">Left Wizard Bag</option>
                <option value="Right Wizard Bag">Right Wizard Bag</option>
                <option value="Bandoleer">Bandoleer</option>
                <option value="Backpack">Backpack</option>
            </select></div>
            <?php
                $isReadyNo = '';
                $isReadyYes = '';
                if ($playerCharacterWeapon->getIsReady()) {
                    $isReadyYes = " selected";
                } else {
                    $isReadyNo = " selected";
                }
            ?>
            <div class="inputRow"><label for="isReady">Ready weapon? </label><select id="isReady" name="isReady">
                <option value="NO"<?= $isReadyNo ?>>No</option>
                <option value="YES"<?= $isReadyYes ?>>Yes</option>
            </select></div>
            <?php
                $isPreferredNo = '';
                $isPreferredYes = '';
                if ($playerCharacterWeapon->getIsPreferred()) {
                    $isPreferredYes = " selected";
                } else {
                    $isPreferredNo = " selected";
                }
            ?>
            <?php if (isCavalier($character_summary->getCharacterClasses())): ?>
                <div class="inputRow"><label for="isPreferred">Preferred weapon? </label><select name="isPreferred" id="isPreferred">
                    <option value="NO"<?= $isPreferredNo ?>>No</option>
                    <option value="YES"<?= $isPreferredYes ?>>Yes</option>
                </select></div>
            <?php else: ?>
                <input type="hidden" name="isPreferred" id="isPreferred" value="NO">
            <?php endif ?>
            <?php
                $craft_status = $playerCharacterWeapon->getCraftStatus();
                if ($craft_status == CRAFT_STATUS_ARTISAN) {
                    $mastercraft_hidden = " hidden";
                    $magic_hidden = " hidden";
                    $craft_status_artisan_selected = " selected";
                    $craft_status_mastercraft_selected = "";
                    $craft_status_magic_selected = "";
                } else if ($craft_status == CRAFT_STATUS_MASTERCRAFT) {
                    $mastercraft_hidden = "";
                    $magic_hidden = " hidden";
                    $craft_status_artisan_selected = "";
                    $craft_status_mastercraft_selected = " selected";
                    $craft_status_magic_selected = "";
                } else if ($craft_status == CRAFT_STATUS_MAGIC) {
                    $mastercraft_hidden = " hidden";
                    $magic_hidden = "";
                    $craft_status_artisan_selected = "";
                    $craft_status_mastercraft_selected = "";
                    $craft_status_magic_selected = " selected";
                }
            ?>
            <?php
                $magic_bonus_plus_1 = '';
                $magic_bonus_plus_2 = '';
                $magic_bonus_plus_3 = '';
                $magic_bonus_plus_4 = '';
                $magic_bonus_plus_5 = '';
                if ($playerCharacterWeapon->getMeleeHitBonus() == 1) {
                    $magic_bonus_plus_1 = " selected";
                }

                if ($playerCharacterWeapon->getMeleeHitBonus() == 2) {
                    $magic_bonus_plus_2 = " selected";
                }

                if ($playerCharacterWeapon->getMeleeHitBonus() == 3) {
                    $magic_bonus_plus_3 = " selected";
                }

                if ($playerCharacterWeapon->getMeleeHitBonus() == 4) {
                    $magic_bonus_plus_4 = " selected";
                }

                if ($playerCharacterWeapon->getMeleeHitBonus() == 5) {
                    $magic_bonus_plus_5 = " selected";
                }
            ?>
            <div class="inputRow"><label for="craftStatus">Craft Status: </label><select name="craftStatus" id="craftStatus" onchange="craftStatusChanged('craftStatus', 'masterCraftSection', 'magicSection', '<?= $playerCharacterWeapon->getMeleeWeaponType() ?>', '<?= $playerCharacterWeapon->getMissileWeaponType() ?>','magicBonus', 'meleeHitBonus', 'meleeDamageBonus', 'meleeSpec1HitBonus', 'meleeSpec2HitBonus', 'meleeSpec3HitBonus', 'meleeSpec1DamageBonus', 'meleeSpec2DamageBonus', 'meleeSpec3DamageBonus', 'missileHitBonus', 'missileDamageBonus', 'missileSpec1HitBonus', 'missileSpec2HitBonus', 'missileSpec3HitBonus', 'missileSpec1DamageBonus', 'missileSpec2DamageBonus', 'missileSpec3DamageBonus');">
                <option value="<?= CRAFT_STATUS_ARTISAN ?>"<?= $craft_status_artisan_selected ?>>Artisan</option>
                <option value="<?= CRAFT_STATUS_MASTERCRAFT ?>"<?= $craft_status_mastercraft_selected ?>>MasterCraft</option>
                <option value="<?= CRAFT_STATUS_MAGIC ?>"<?= $craft_status_magic_selected ?>>Magic</option>
            </select> <select id="magicBonus" onchange="populateDefaultHitDamageBonuses('magicBonus', 'meleeHitBonus', 'meleeDamageBonus', 'missileHitBonus', 'missileDamageBonus');"<?= $magic_hidden ?>>
                        <option value="0">None</option>
                        <option value="1"<?= $magic_bonus_plus_1 ?>>+1</option>
                        <option value="2"<?= $magic_bonus_plus_2 ?>>+2</option>
                        <option value="3"<?= $magic_bonus_plus_3 ?>>+3</option>
                        <option value="4"<?= $magic_bonus_plus_4 ?>>+4</option>
                        <option value="5"<?= $magic_bonus_plus_5 ?>>+5</option>
            </select></div>
            <div id="masterCraftSection" class="masterCraftSection"<?= $mastercraft_hidden ?>>
                    <?php
                        $mc_hit_desc_none = '';
                        $mc_hit_desc_balanced = '';
                        if ($playerCharacterWeapon->getMastercraftHitDescription() == "None") {
                            $mc_hit_desc_none = " selected";
                        }

                        if ($playerCharacterWeapon->getMastercraftHitDescription() == "Balanced") {
                            $mc_hit_desc_balanced = " selected";
                        }

                        $mc_damage_desc_none = '';
                        $mc_damage_desc_sharp_heavy = '';
                        $mc_damage_desc_ex_sharp_heavy = '';
                        if ($playerCharacterWeapon->getMastercraftDamageDescription() == "None") {
                            $mc_damage_desc_none = " selected";
                        }

                        if ($playerCharacterWeapon->getMastercraftDamageDescription() == "Sharp/Heavy") {
                            $mc_damage_desc_sharp_heavy = " selected";
                        }

                        if ($playerCharacterWeapon->getMastercraftDamageDescription() == "Extra-Sharp/Extra-Heavy") {
                            $mc_damage_desc_ex_sharp_heavy = " selected";
                        }
                    ?>
                <div class="inputRow"><label for="mastercraftHitDescription">Mastercraft Hit: </label><select id="mastercraftHitDescription" name="mastercraftHitDescription" onchange="updateHitBonus('mastercraftHitDescription', 'meleeHitBonus', 'missileHitBonus');">
                    <option value="None"<?= $mc_hit_desc_none?>>None</option>
                    <option value="Balanced"<?= $mc_hit_desc_balanced?>>Balanced</option>
                </select></div>
                <?php if (isMasterCraftDamageEligible($playerCharacterWeapon->getMeleeWeaponSubtype())): ?>
                <div class="inputRow"><label for="mastercraftDamageDescription">Mastercraft Damage: </label><select id="mastercraftDamageDescription" name="mastercraftDamageDescription" onchange="updateDamageBonus('mastercraftDamageDescription', 'meleeDamageBonus', 'missileDamageBonus');">
                    <option value="None"<?= $mc_damage_desc_none ?>>None</option>
                    <option value="Sharp/Heavy"<?= $mc_damage_desc_sharp_heavy ?>>Sharp/Heavy</option>
                    <option value="Extra-Sharp/Extra-Heavy"<?= $mc_damage_desc_ex_sharp_heavy ?>>Extra-Sharp/Extra-Heavy</option>
                </select></div>
                <?php else: ?>
                    <input type="hidden" name="mastercraftDamageDescription" id="mastercraftDamageDescription" value="None">
                <?php endif ?>
            </div>
            <div class="inputRow"><label for="playerNote1">Note 1: </label><input type="text" name="playerNote1" id="playerNote1" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getPlayerNote1() ?>"></div>
            <div class="inputRow"><label for="playerNote2">Note 2: </label><input type="text" name="playerNote2" id="playerNote2" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getPlayerNote2() ?>"></div>
            <div class="inputRow"><label for="playerNote3">Note 3: </label><input type="text" name="playerNote3" id="playerNote3" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getPlayerNote3() ?>"></div>
            <?php if ($playerCharacterWeapon->getMeleeWeaponType() == WEAPON_TYPE_MELEE): ?>
                <h3>Melee</h3>
                <div class="inputRow"><label for="meleeWeaponSpeed">Weapon Speed: </label><input type="text" id="meleeWeaponSpeed" name="meleeWeaponSpeed" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeWeaponSpeed() ?>"></div>
                <div class="inputRow"><label for="meleeWeaponDamage">Weapon Damage: </label><input type="text" id="meleeWeaponDamage" name="meleeWeaponDamage" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeWeaponDamage() ?>"></div>
                <div class="inputRow"><label for="meleeAttacksPerRound">Attacks Per Round: </label><input type="text" id="meleeAttacksPerRound" name="meleeAttacksPerRound" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeAttacksPerRound() ?>"></div>
                <div class="inputRow"><label for="meleeNumberOfHands">Number of Hands: </label><input type="text" id="meleeNumberOfHands" name="meleeNumberOfHands" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeNumberOfHands() ?>"></div>
                <?php if ($playerCharacterWeapon->getMeleeAdditionalText() != NULL): ?>
                    <div class="inputRow"><label for="meleeAdditionalText">Additional Info: </label><input type="text" id="meleeAdditionalText" name="meleeAdditionalText" size="40" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeAdditionalText() ?>"></div>
                <?php endif ?>
                <div id="magicSectionMelee" class="magicSection"<?= $magic_hidden ?>>
                    <span class="characterRollModifier">
                        <a href="#" onclick="showRollModifierSectionClick('magic-melee', 'magic-melee-icon');">
                            <span id="magic-melee-icon" class="fa-solid fa-chevron-down"></span> Melee Section
                        </a>
                    </span>
                    <div id="magic-melee" class="characterRollModifierContent"<?= $magic_hidden ?>>
                    <div class="inputRow"><label for="meleeHitBonus">Hit: </label><input type="number" id="meleeHitBonus" name="meleeHitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeHitBonus() ?? '0' ?>"> <label for="meleeDamageBonus">Damage: </label><input type="number" id="meleeDamageBonus" name="meleeDamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeDamageBonus() ?? '0' ?>"> </div>
                    <div class="inputRow"><label for="meleeSpec1HitBonus">Special1: </label><input type="number" id="meleeSpec1HitBonus" name="meleeSpec1HitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec1HitBonus() ?? '0' ?>"> <input type="number" id="meleeSpec1DamageBonus" name="meleeSpec1DamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec1DamageBonus() ?? '0' ?>"> <input type="text" id="meleeSpec1Description" name="meleeSpec1Description" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeSpec1Description() ?>"></div>
                    <div class="inputRow"><label for="meleespec2HitBonus">Special2: </label><input type="number" id="meleeSpec2HitBonus" name="meleeSpec2HitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec2HitBonus() ?? '0' ?>"> <input type="number" id="meleeSpec2DamageBonus" name="meleeSpec2DamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec2DamageBonus() ?? '0' ?>"> <input type="text" id="meleeSpec2Description" name="meleeSpec2Description" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeSpec2Description() ?>"></div>
                    <div class="inputRow"><label for="meleeSpec3HitBonus">Special3: </label><input type="number" id="meleeSpec3HitBonus" name="meleeSpec3HitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec3HitBonus() ?? '0' ?>"> <input type="number" id="meleeSpec3DamageBonus" name="meleeSpec3DamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec3DamageBonus() ?? '0' ?>"> <input type="text" id="meleeSpec3Description" name="meleeSpec3Description" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeSpec3Description() ?>"></div>
                    </div>
                </div>
                <input type="hidden" name="meleeWeaponType" value="<?= WEAPON_TYPE_MELEE ?>">
            <?php else: ?>
                <input type="hidden" name="meleeWeaponType" value="0">
                <input type="hidden" id="meleeWeaponSpeed" name="meleeWeaponSpeed" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="meleeWeaponDamage" name="meleeWeaponDamage" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="meleeAttacksPerRound" name="meleeAttacksPerRound" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="meleeNumberOfHands" name="meleeNumberOfHands" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="meleeAdditionalText" name="meleeAdditionalText" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="meleeHitBonus" name="meleeHitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="meleeDamageBonus" name="meleeDamageBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">            
                <input type="hidden" id="meleeSpec1HitBonus" name="meleeSpec1HitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="meleeSpec1DamageBonus" name="meleeSpec1DamageBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="meleeSpec1Description" name="meleeSpec1Description" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="meleeSpec2HitBonus" name="meleeSpec2HitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="meleeSpec2DamageBonus" name="meleeSpec2DamageBonus"  value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="meleeSpec2Description" name="meleeSpec2Description" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="meleeSpec3HitBonus" name="meleeSpec3HitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="meleeSpec3DamageBonus" name="meleeSpec3DamageBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="meleeSpec3Description" name="meleeSpec3Description" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
            <?php endif ?>
            <?php if ($playerCharacterWeapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE): ?>
                <h3>Missile</h3>
                <div class="inputRow"><label for="missileWeaponSpeed">Weapon Speed: </label><input type="text" id="missileWeaponSpeed" name="missileWeaponSpeed" maxlength="32" value="<?= $playerCharacterWeapon->getMissileWeaponSpeed() ?>"></div>
                <div class="inputRow"><label for="missileWeaponDamage">Weapon Damage: </label><input type="text" id="missileWeaponDamage" name="missileWeaponDamage" maxlength="32" value="<?= $playerCharacterWeapon->getMissileWeaponDamage() ?>"></div>
                <div class="inputRow"><label for="missileAttacksPerRound">Attacks Per Round: </label><input type="text" id="missileAttacksPerRound" name="missileAttacksPerRound" maxlength="32" value="<?= $playerCharacterWeapon->getMissileAttacksPerRound() ?>"></div>
                <?php if ($playerCharacterWeapon->getMissileAdditionalText() != NULL): ?>
                    <div class="inputRow"><label for="missileAdditionalText">Additional Info: </label><input type="text" id="missileAdditionalText" name="missileAdditionalText" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getMissileAdditionalText() ?>"></div>
                <?php endif ?>
                <?php if ($playerCharacterWeapon->getMissileWeaponSubtype() == BOW): ?>
                    <?php
                        $strength_bow_no = '';
                        $strength_bow_yes = '';
                        if ($playerCharacterWeapon->getStrengthBonusAvailable()) {
                            $strength_bow_yes = " selected";
                        } else {
                            $strength_bow_no = " selected";
                        }
                    ?>
                <div class="inputRow"><label for="strengthBonusAvailable">Strength Bonus? </label><select name="strengthBonusAvailable" id="strengthBonusAvailable">
                    <option value="NO"<?= $strength_bow_no ?>>No</option>
                    <option value="YES"<?= $strength_bow_yes ?>>Yes</option>
                </select></div>
                <?php else: ?>
                    <input type="hidden" name="strengthBonusAvailable" value="<?= isStrengthBonusAvailable($playerCharacterWeapon->getWeaponProficiencyId()) ?>">
                <?php endif ?>
                <div class="inputRow"><label for="missileShortRange">Short Range: </label><input type="text" id="missileShortRange" name="missileShortRange" maxlength="32" value="<?= $playerCharacterWeapon->getMissileShortRange() ?>"></div>
                <div class="inputRow"><label for="missileMediumRange">Medium Range: </label><input type="text" id="missileMediumRange" name="missileMediumRange" maxlength="32" value="<?= $playerCharacterWeapon->getMissileMediumRange() ?>"></div>
                <div class="inputRow"><label for="missileLongRange">Long Range: </label><input type="text" id="missileLongRange" name="missileLongRange" maxlength="32" value="<?= $playerCharacterWeapon->getMissileLongRange() ?>"></div>
                <div id="magicSectionMissile" class="magicSection"<?= $magic_hidden ?>>
                    <span class="characterRollModifier">
                        <a href="#" onclick="showRollModifierSectionClick('magic-missile', 'magic-missile-icon');">
                            <span id="magic-missile-icon" class="fa-solid fa-chevron-down"></span> Missile Section
                        </a>
                    </span>
                    <div id="magic-missile" class="characterRollModifierContent"<?= $magic_hidden ?>>
                    <div class="inputRow"><label for="missileHitBonus">Hit: </label><input type="number" id="missileHitBonus" name="missileHitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileHitBonus() ?? "0" ?>"> <label for="missileDamageBonus">Damage: </label><input type="number" id="missileDamageBonus" name="missileDamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileDamageBonus() ?? "0" ?>"> </div>
                    <div class="inputRow"><label for="missileSpec1HitBonus">Special1: </label><input type="number" id="missileSpec1HitBonus" name="missileSpec1HitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec1HitBonus() ?? "0" ?>"> <input type="number" id="missileSpec1DamageBonus" name="missileSpec1DamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec1DamageBonus() ?? "0" ?>"> <input type="text" id="missileSpec1Description" name="missileSpec1Description" maxlength="32" value="<?= $playerCharacterWeapon->getMissileSpec1Description() ?>"></div>
                    <div class="inputRow"><label for="missilespec2HitBonus">Special2: </label><input type="number" id="missileSpec2HitBonus" name="missileSpec2HitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec2HitBonus() ?? "0" ?>"> <input type="number" id="missileSpec2DamageBonus" name="missileSpec2DamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec2DamageBonus() ?? "0" ?>"> <input type="text" id="missileSpec2Description" name="missileSpec2Description" maxlength="32" value="<?= $playerCharacterWeapon->getMissileSpec2Description() ?>"></div>
                    <div class="inputRow"><label for="missileSpec3HitBonus">Special3: </label><input type="number" id="missileSpec3HitBonus" name="missileSpec3HitBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec3HitBonus() ?? "0" ?>"> <input type="number" id="missileSpec3DamageBonus" name="missileSpec3DamageBonus" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec3DamageBonus() ?? "0" ?>"> <input type="text" id="missileSpec3Description" name="missileSpec3Description" maxlength="32" value="<?= $playerCharacterWeapon->getMissileSpec3Description() ?>"></div>
                    </div>
                </div>
                <input type="hidden" name="missileWeaponType" value="<?= WEAPON_TYPE_MISSILE ?>">
            <?php else: ?>
                <input type="hidden" name="missileWeaponType" value="0">
                <input type="hidden" id="strengthBonusAvailable" name="strengthBonusAvailable" value="NO">
                <input type="hidden" id="missileWeaponSpeed" name="missileWeaponSpeed" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="missileWeaponDamage" name="missileWeaponDamage" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="missileAttacksPerRound" name="missileAttacksPerRound" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="missileAdditionalText" name="missileAdditionalText" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="missileHitBonus" name="missileHitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="missileDamageBonus" name="missileDamageBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">            
                <input type="hidden" id="missileSpec1HitBonus" name="missileSpec1HitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="missileSpec1DamageBonus" name="missileSpec1DamageBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="missileSpec1Description" name="missileSpec1Description" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="missileSpec2HitBonus" name="missileSpec2HitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="missileSpec2DamageBonus" name="missileSpec2DamageBonus"  value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="missileSpec2Description" name="missileSpec2Description" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="missileSpec3HitBonus" name="missileSpec3HitBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="missileSpec3DamageBonus" name="missileSpec3DamageBonus" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="missileSpec3Description" name="missileSpec3Description" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
            <?php endif ?>
            <button type="submit">Update Weapon</button>
        </form>
    </div>
    <?php endif ?>
</body>
</html>
<?php

function getPlayerCharacterWeapon(\PDO $pdo, $player_character_weapon_id, &$errors) {
    $player_character_weapon = new PlayerCharacterWeapon();
    $player_character_weapon->init($pdo, $player_character_weapon_id, $errors);

    if(!empty($errors)) {
        die($errors);
    }

    return $player_character_weapon;
}

function isCavalier($character_classes) {
    foreach($character_classes AS $character_class) {
        if (getClassID($character_class['class_name']) == CAVALIER) {
            return true;
        } else {
            return false;
        }
    }
}

function isRanged($weapon_subtype) {
    return  ($weapon_subtype == MISC_MISSILE) || ($weapon_subtype == BOW) || ($weapon_subtype == CROSSBOW) ||  
            ($weapon_subtype == AXE) || ($weapon_subtype ==  HAMMER) || ($weapon_subtype == SLING);
}

function isMasterCraftDamageEligible($weapon_subtype) {
    return ($weapon_subtype == MISC_MELEE) || ($weapon_subtype == AXE) || ($weapon_subtype == POLE_ARM) || ($weapon_subtype == CLUB) || ($weapon_subtype == ONE_HANDED_SWORD) || ($weapon_subtype == HAMMER) || ($weapon_subtype == LANCE) || ($weapon_subtype == TWO_HANDED_SWORD);
}

function isStrengthBonusAvailable($weapon_proficiency_id) {
    switch($weapon_proficiency_id) {
        case DAGGER:
            return "YES";
        case BATTLE_AXE:
            return "YES";
        case HAND_AXE:
            return "YES";
        case SPEAR:
            return "YES";
        case HAMMER:
            return "YES";
        case DWARVEN_THROWING_HAMMER:
            return "YES";
        case JAVELIN:
            return "YES";
        default:
            return "NO";
    }
}

function buildActionBar($player_name, $character_name) {
    $action_bar  = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
    $action_bar .= '&nbsp;';
    $action_bar .= ActionBarHelper::buildEditWeaponsIcon($player_name, $character_name);
    $action_bar .= '&nbsp;';

    return $action_bar;
}

?>