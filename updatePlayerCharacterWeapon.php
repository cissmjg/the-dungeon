<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/classes/characterSummary.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';

require_once __DIR__ . '/classes/playerCharacterWeapon.php';
require_once __DIR__ . '/dbio/constants/weaponType.php';
require_once __DIR__ . '/dbio/constants/weaponSubtype.php';
require_once __DIR__ . '/dbio/constants/characterClasses.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
require_once __DIR__ . '/webio/craftStatus.php';
require_once __DIR__ . '/dbio/constants/weapons.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/playerCharacterWeaponId.php';
require_once __DIR__ . '/webio/playerCharacterWeaponSkillId.php';
require_once __DIR__ . '/webio/weaponDescription.php';
require_once __DIR__ . '/webio/weaponLocation.php';
require_once __DIR__ . '/webio/craftStatus.php';
require_once __DIR__ . '/webio/isPreferred.php';
require_once __DIR__ . '/webio/isProficient.php';
require_once __DIR__ . '/webio/isReady.php';
require_once __DIR__ . '/webio/mastercraftDamageDescription.php';
require_once __DIR__ . '/webio/mastercraftHitDescription.php';
require_once __DIR__ . '/webio/meleeAdditionalText.php';
require_once __DIR__ . '/webio/meleeAttacksPerRound.php';
require_once __DIR__ . '/webio/meleeDamageBonus.php';
require_once __DIR__ . '/webio/meleeHitBonus.php';
require_once __DIR__ . '/webio/meleeNumberOfHands.php';
require_once __DIR__ . '/webio/meleeSpec1DamageBonus.php';
require_once __DIR__ . '/webio/meleeSpec1Description.php';
require_once __DIR__ . '/webio/meleeSpec1HitBonus.php';
require_once __DIR__ . '/webio/meleeSpec2DamageBonus.php';
require_once __DIR__ . '/webio/meleeSpec2Description.php';
require_once __DIR__ . '/webio/meleeSpec2HitBonus.php';
require_once __DIR__ . '/webio/meleeSpec3DamageBonus.php';
require_once __DIR__ . '/webio/meleeSpec3Description.php';
require_once __DIR__ . '/webio/meleeSpec3HitBonus.php';
require_once __DIR__ . '/webio/meleeWeaponDamage.php';
require_once __DIR__ . '/webio/meleeWeaponSpeed.php';
require_once __DIR__ . '/webio/meleeWeaponSubtype.php';
require_once __DIR__ . '/webio/meleeWeaponType.php';
require_once __DIR__ . '/webio/missileAdditionalText.php';
require_once __DIR__ . '/webio/missileAttacksPerRound.php';
require_once __DIR__ . '/webio/missileDamageBonus.php';
require_once __DIR__ . '/webio/missileHitBonus.php';
require_once __DIR__ . '/webio/missileLongRange.php';
require_once __DIR__ . '/webio/missileMediumRange.php';
require_once __DIR__ . '/webio/missileShortRange.php';
require_once __DIR__ . '/webio/missileSpec1DamageBonus.php';
require_once __DIR__ . '/webio/missileSpec1Description.php';
require_once __DIR__ . '/webio/missileSpec1HitBonus.php';
require_once __DIR__ . '/webio/missileSpec2DamageBonus.php';
require_once __DIR__ . '/webio/missileSpec2Description.php';
require_once __DIR__ . '/webio/missileSpec2HitBonus.php';
require_once __DIR__ . '/webio/missileSpec3DamageBonus.php';
require_once __DIR__ . '/webio/missileSpec3Description.php';
require_once __DIR__ . '/webio/missileSpec3HitBonus.php';
require_once __DIR__ . '/webio/missileWeaponDamage.php';
require_once __DIR__ . '/webio/missileWeaponSpeed.php';
require_once __DIR__ . '/webio/missileWeaponSubtype.php';
require_once __DIR__ . '/webio/missileWeaponType.php';
require_once __DIR__ . '/webio/playerNote1.php';
require_once __DIR__ . '/webio/playerNote2.php';
require_once __DIR__ . '/webio/playerNote3.php';
require_once __DIR__ . '/webio/strengthBonusAvailable.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponId($errors, $input);

$playerCharacterWeapon = getPlayerCharacterWeapon($pdo, $input[PLAYER_CHARACTER_WEAPON_ID], $errors);

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
            <input type="hidden" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
            <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
            <input type="hidden" name="<?= WEAPON_PROFICIENCY_ID ?>" value="<?= $playerCharacterWeapon->getWeaponProficiencyId() ?>">
            <input type="hidden" name="<?= PLAYER_CHARACTER_WEAPON_ID ?>" value="<?= $playerCharacterWeapon->getWeaponId() ?? '0'?>">
            <h3><?= $playerCharacterWeapon->getWeaponDescription(); ?></h3>
            <div class="inputRow"><label for="weaponDescription">Weapon Name: </label><input type="text" name="<?= WEAPON_DESCRIPTION ?>" id="<?= WEAPON_DESCRIPTION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getWeaponDescription(); ?>"></div>
            <div class="inputRow"><label for="weaponLocation">Weapon Location: </label><input type="text" name="<?= WEAPON_LOCATION ?>" id="<?= WEAPON_LOCATION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getWeaponLocation() ?>"> <select id="weaponLocationHints" onchange="populateWeaponLocation('weaponLocationHints', '<?= WEAPON_LOCATION ?>');">
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
            <div class="inputRow"><label for="isReady">Ready weapon? </label><select id="<?= IS_READY ?>" name="<?= IS_READY ?>">
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
                <div class="inputRow"><label for="isPreferred">Preferred weapon? </label><select name="<?= IS_PREFERRED ?>" id="<?= IS_PREFERRED ?>">
                    <option value="NO"<?= $isPreferredNo ?>>No</option>
                    <option value="YES"<?= $isPreferredYes ?>>Yes</option>
                </select></div>
            <?php else: ?>
                <input type="hidden" name="<?= IS_PREFERRED ?>" id="<?= IS_PREFERRED ?>" value="NO">
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
            <div class="inputRow"><label for="craftStatus">Craft Status: </label><select name="<?= CRAFT_STATUS ?>" id="<?= CRAFT_STATUS ?>" onchange="craftStatusChanged('<?= CRAFT_STATUS ?>', 'masterCraftSection', 'magicSection', '<?= $playerCharacterWeapon->getMeleeWeaponType() ?>', '<?= $playerCharacterWeapon->getMissileWeaponType() ?>','magicBonus', '<?= MELEE_HIT_BONUS ?>', '<?= MELEE_DAMAGE_BONUS ?>', '<?= MELEE_SPEC1_HIT_BONUS ?>', '<?= MELEE_SPEC2_HIT_BONUS ?>', '<?= MELEE_SPEC3_HIT_BONUS ?>', '<?= MELEE_SPEC1_DAMAGE_BONUS ?>', '<?= MELEE_SPEC2_DAMAGE_BONUS ?>', '<?= MELEE_SPEC3_DAMAGE_BONUS ?>', '<?= MISSILE_HIT_BONUS ?>', '<?= MISSILE_DAMAGE_BONUS ?>', '<?= MISSILE_SPEC1_HIT_BONUS ?>', '<?= MISSILE_SPEC2_HIT_BONUS ?>', '<?= MISSILE_SPEC3_HIT_BONUS ?>', '<?= MISSILE_SPEC1_DAMAGE_BONUS ?>', '<?= MISSILE_SPEC2_DAMAGE_BONUS ?>', '<?= MISSILE_SPEC3_DAMAGE_BONUS ?>');">
                <option value="<?= CRAFT_STATUS_ARTISAN ?>"<?= $craft_status_artisan_selected ?>>Artisan</option>
                <option value="<?= CRAFT_STATUS_MASTERCRAFT ?>"<?= $craft_status_mastercraft_selected ?>>MasterCraft</option>
                <option value="<?= CRAFT_STATUS_MAGIC ?>"<?= $craft_status_magic_selected ?>>Magic</option>
            </select> <select id="magicBonus" onchange="populateDefaultHitDamageBonuses('magicBonus', '<?= MELEE_HIT_BONUS ?>', '<?= MELEE_DAMAGE_BONUS ?>', '<?= MISSILE_HIT_BONUS ?>', '<?= MISSILE_DAMAGE_BONUS ?>');"<?= $magic_hidden ?>>
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
                <div class="inputRow"><label for="mastercraftHitDescription">Mastercraft Hit: </label><select name="<?= MASTERCRAFT_HIT_DESCRIPTION ?>" id="<?= MASTERCRAFT_HIT_DESCRIPTION ?>" onchange="updateHitBonus('<?= MASTERCRAFT_HIT_DESCRIPTION ?>', '<?= MELEE_HIT_BONUS ?>', '<?= MISSILE_HIT_BONUS ?>');">
                    <option value="None"<?= $mc_hit_desc_none?>>None</option>
                    <option value="Balanced"<?= $mc_hit_desc_balanced?>>Balanced</option>
                </select></div>
                <?php if (isMasterCraftDamageEligible($playerCharacterWeapon->getMeleeWeaponSubtype())): ?>
                <div class="inputRow"><label for="mastercraftDamageDescription">Mastercraft Damage: </label><select name="<?= MASTERCRAFT_DAMAGE_DESCRIPTION ?>" id="<?= MASTERCRAFT_DAMAGE_DESCRIPTION ?>" onchange="updateDamageBonus('<?= MASTERCRAFT_DAMAGE_DESCRIPTION ?>', '<?= MELEE_DAMAGE_BONUS ?>', '<?= MISSILE_DAMAGE_BONUS ?>');">
                    <option value="None"<?= $mc_damage_desc_none ?>>None</option>
                    <option value="Sharp/Heavy"<?= $mc_damage_desc_sharp_heavy ?>>Sharp/Heavy</option>
                    <option value="Extra-Sharp/Extra-Heavy"<?= $mc_damage_desc_ex_sharp_heavy ?>>Extra-Sharp/Extra-Heavy</option>
                </select></div>
                <?php else: ?>
                    <input type="hidden" name="<?= MASTERCRAFT_DAMAGE_DESCRIPTION ?>" id="<?= MASTERCRAFT_DAMAGE_DESCRIPTION ?>" value="None">
                <?php endif ?>
            </div>
            <div class="inputRow"><label for="playerNote1">Note 1: </label><input type="text" name="<?= PLAYER_NOTE1 ?>" id="<?= PLAYER_NOTE1 ?>" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getPlayerNote1() ?>"></div>
            <div class="inputRow"><label for="playerNote2">Note 2: </label><input type="text" name="<?= PLAYER_NOTE2 ?>" id="<?= PLAYER_NOTE2 ?>" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getPlayerNote2() ?>"></div>
            <div class="inputRow"><label for="playerNote3">Note 3: </label><input type="text" name="<?= PLAYER_NOTE3 ?>" id="<?= PLAYER_NOTE3 ?>" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getPlayerNote3() ?>"></div>
            <?php if ($playerCharacterWeapon->getMeleeWeaponType() == WEAPON_TYPE_MELEE): ?>
                <h3>Melee</h3>
                <div class="inputRow"><label for="meleeWeaponSpeed">Weapon Speed: </label><input type="text" id="<?= MELEE_WEAPON_SPEED ?>" name="<?= MELEE_WEAPON_SPEED ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeWeaponSpeed() ?>"></div>
                <div class="inputRow"><label for="meleeWeaponDamage">Weapon Damage: </label><input type="text" id="<?= MELEE_WEAPON_DAMAGE ?>" name="<?= MELEE_WEAPON_DAMAGE ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeWeaponDamage() ?>"></div>
                <div class="inputRow"><label for="meleeAttacksPerRound">Attacks Per Round: </label><input type="text" id="<?= MELEE_ATTACKS_PER_ROUND ?>" name="<?= MELEE_ATTACKS_PER_ROUND ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeAttacksPerRound() ?>"></div>
                <div class="inputRow"><label for="meleeNumberOfHands">Number of Hands: </label><input type="text" id="<?= MELEE_NUMBER_OF_HANDS ?>" name="<?= MELEE_NUMBER_OF_HANDS ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeNumberOfHands() ?>"></div>
                <?php if ($playerCharacterWeapon->getMeleeAdditionalText() != NULL): ?>
                    <div class="inputRow"><label for="meleeAdditionalText">Additional Info: </label><input type="text" id="<?= MELEE_ADDITIONAL_TEXT ?>" name="<?= MELEE_ADDITIONAL_TEXT ?>" size="40" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeAdditionalText() ?>"></div>
                <?php endif ?>
                <div id="magicSectionMelee" class="magicSection"<?= $magic_hidden ?>>
                    <span class="characterRollModifier">
                        <a href="#" onclick="showRollModifierSectionClick('magic-melee', 'magic-melee-icon');">
                            <span id="magic-melee-icon" class="fa-solid fa-chevron-down"></span> Melee Section
                        </a>
                    </span>
                    <div id="magic-melee" class="characterRollModifierContent"<?= $magic_hidden ?>>
                    <div class="inputRow"><label for="meleeHitBonus">Hit: </label><input type="number" id="<?= MELEE_HIT_BONUS ?>" name="<?= MELEE_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeHitBonus() ?? '0' ?>"> <label for="meleeDamageBonus">Damage: </label><input type="number" id="<?= MELEE_DAMAGE_BONUS ?>" name="<?= MELEE_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeDamageBonus() ?? '0' ?>"> </div>
                    <div class="inputRow"><label for="meleeSpec1HitBonus">Special1: </label><input type="number" id="<?= MELEE_SPEC1_HIT_BONUS ?>" name="<?= MELEE_SPEC1_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec1HitBonus() ?? '0' ?>"> <input type="number" id="<?= MELEE_SPEC1_DAMAGE_BONUS ?>" name="<?= MELEE_SPEC1_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec1DamageBonus() ?? '0' ?>"> <input type="text" id="<?= MELEE_SPEC1_DESCRIPTION ?>" name="<?= MELEE_SPEC1_DESCRIPTION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeSpec1Description() ?>"></div>
                    <div class="inputRow"><label for="meleespec2HitBonus">Special2: </label><input type="number" id="<?= MELEE_SPEC2_HIT_BONUS ?>" name="<?= MELEE_SPEC2_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec2HitBonus() ?? '0' ?>"> <input type="number" id="<?= MELEE_SPEC2_DAMAGE_BONUS ?>" name="<?= MELEE_SPEC2_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec2DamageBonus() ?? '0' ?>"> <input type="text" id="<?= MELEE_SPEC2_DESCRIPTION ?>" name="<?= MELEE_SPEC2_DESCRIPTION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeSpec2Description() ?>"></div>
                    <div class="inputRow"><label for="meleeSpec3HitBonus">Special3: </label><input type="number" id="<?= MELEE_SPEC3_HIT_BONUS ?>" name="<?= MELEE_SPEC3_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec3HitBonus() ?? '0' ?>"> <input type="number" id="<?= MELEE_SPEC3_DAMAGE_BONUS ?>" name="<?= MELEE_SPEC3_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMeleeSpec3DamageBonus() ?? '0' ?>"> <input type="text" id="<?= MELEE_SPEC3_DESCRIPTION ?>" name="<?= MELEE_SPEC3_DESCRIPTION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMeleeSpec3Description() ?>"></div>
                    </div>
                </div>
                <input type="hidden" name="<?= MELEE_WEAPON_TYPE ?>" value="<?= WEAPON_TYPE_MELEE ?>">
            <?php else: ?>
                <input type="hidden" name="<?= MELEE_WEAPON_TYPE ?>" value="0">
                <input type="hidden" id="<?= MELEE_WEAPON_SPEED ?>" name="<?= MELEE_WEAPON_SPEED ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_WEAPON_DAMAGE ?>" name="<?= MELEE_WEAPON_DAMAGE ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_ATTACKS_PER_ROUND ?>" name="<?= MELEE_ATTACKS_PER_ROUND ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_NUMBER_OF_HANDS ?>" name="<?= MELEE_NUMBER_OF_HANDS ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_ADDITIONAL_TEXT ?>" name="<?= MELEE_ADDITIONAL_TEXT ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_HIT_BONUS ?>" name="<?= MELEE_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_DAMAGE_BONUS ?>" name="<?= MELEE_DAMAGE_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">            
                <input type="hidden" id="<?= MELEE_SPEC1_HIT_BONUS ?>" name="<?= MELEE_SPEC1_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_SPEC1_DAMAGE_BONUS ?>" name="<?= MELEE_SPEC1_DAMAGE_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MELEE_SPEC1_DESCRIPTION ?>" name="<?= MELEE_SPEC1_DESCRIPTION ?>" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_SPEC2_HIT_BONUS ?>" name="<?= MELEE_SPEC2_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MELEE_SPEC2_DAMAGE_BONUS ?>" name="<?= MELEE_SPEC2_DAMAGE_BONUS ?>"  value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MELEE_SPEC2_DESCRIPTION ?>" name="<?= MELEE_SPEC2_DESCRIPTION ?>" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MELEE_SPEC3_HIT_BONUS ?>" name="<?= MELEE_SPEC3_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MELEE_SPEC3_DAMAGE_BONUS ?>" name="<?= MELEE_SPEC3_DAMAGE_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MELEE_SPEC3_DESCRIPTION ?>" name="<?= MELEE_SPEC3_DESCRIPTION ?>" maxlength="32" value="<?= OPTIONAL_STRING_PARAMETER ?>">
            <?php endif ?>
            <?php if ($playerCharacterWeapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE): ?>
                <h3>Missile</h3>
                <div class="inputRow"><label for="missileWeaponSpeed">Weapon Speed: </label><input type="text" id="<?= MISSILE_WEAPON_SPEED ?>" name="<?= MISSILE_WEAPON_SPEED ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileWeaponSpeed() ?>"></div>
                <div class="inputRow"><label for="missileWeaponDamage">Weapon Damage: </label><input type="text" id="<?= MISSILE_WEAPON_DAMAGE ?>" name="<?= MISSILE_WEAPON_DAMAGE ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileWeaponDamage() ?>"></div>
                <div class="inputRow"><label for="missileAttacksPerRound">Attacks Per Round: </label><input type="text" id="<?= MISSILE_ATTACKS_PER_ROUND ?>" name="<?= MISSILE_ATTACKS_PER_ROUND ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileAttacksPerRound() ?>"></div>
                <?php if ($playerCharacterWeapon->getMissileAdditionalText() != NULL): ?>
                    <div class="inputRow"><label for="missileAdditionalText">Additional Info: </label><input type="text" id="<?= MISSILE_ADDITIONAL_TEXT ?>" name="<?= MISSILE_ADDITIONAL_TEXT ?>" size="32" maxlength="32" value="<?= $playerCharacterWeapon->getMissileAdditionalText() ?>"></div>
                <?php endif ?>
                <?php if ($playerCharacterWeapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW): ?>
                    <?php
                        $strength_bow_no = '';
                        $strength_bow_yes = '';
                        if ($playerCharacterWeapon->getStrengthBonusAvailable()) {
                            $strength_bow_yes = " selected";
                        } else {
                            $strength_bow_no = " selected";
                        }
                    ?>
                <div class="inputRow"><label for="strengthBonusAvailable">Strength Bonus? </label><select name="<?= STRENGTH_BONUS_AVAILABLE ?>" id="<?= STRENGTH_BONUS_AVAILABLE ?>">
                    <option value="NO"<?= $strength_bow_no ?>>No</option>
                    <option value="YES"<?= $strength_bow_yes ?>>Yes</option>
                </select></div>
                <?php else: ?>
                    <input type="hidden" name="<?= STRENGTH_BONUS_AVAILABLE ?>" value="<?= isStrengthBonusAvailable($playerCharacterWeapon->getWeaponProficiencyId()) ?>">
                <?php endif ?>
                <div class="inputRow"><label for="missileShortRange">Short Range: </label><input type="text" id="<?= MISSILE_SHORT_RANGE ?>" name="<?= MISSILE_SHORT_RANGE ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileShortRange() ?>"></div>
                <div class="inputRow"><label for="missileMediumRange">Medium Range: </label><input type="text" id="<?= MISSILE_MEDIUM_RANGE ?>" name="<?= MISSILE_MEDIUM_RANGE ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileMediumRange() ?>"></div>
                <div class="inputRow"><label for="missileLongRange">Long Range: </label><input type="text" id="<?= MISSILE_LONG_RANGE ?>" name="<?= MISSILE_LONG_RANGE ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileLongRange() ?>"></div>
                <div id="magicSectionMissile" class="magicSection"<?= $magic_hidden ?>>
                    <span class="characterRollModifier">
                        <a href="#" onclick="showRollModifierSectionClick('magic-missile', 'magic-missile-icon');">
                            <span id="magic-missile-icon" class="fa-solid fa-chevron-down"></span> Missile Section
                        </a>
                    </span>
                    <div id="magic-missile" class="characterRollModifierContent"<?= $magic_hidden ?>>
                    <div class="inputRow"><label for="missileHitBonus">Hit: </label><input type="number" id="<?= MISSILE_HIT_BONUS ?>" name="<?= MISSILE_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileHitBonus() ?? "0" ?>"> <label for="missileDamageBonus">Damage: </label><input type="number" id="<?= MISSILE_DAMAGE_BONUS ?>" name="<?= MISSILE_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileDamageBonus() ?? "0" ?>"> </div>
                    <div class="inputRow"><label for="missileSpec1HitBonus">Special1: </label><input type="number" id="<?= MISSILE_SPEC1_HIT_BONUS ?>" name="<?= MISSILE_SPEC1_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec1HitBonus() ?? "0" ?>"> <input type="number" id="<?= MISSILE_SPEC1_DAMAGE_BONUS ?>" name="<?= MISSILE_SPEC1_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec1DamageBonus() ?? "0" ?>"> <input type="text" id="<?= MISSILE_SPEC1_DESCRIPTION ?>" name="<?= MISSILE_SPEC1_DESCRIPTION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileSpec1Description() ?>"></div>
                    <div class="inputRow"><label for="missilespec2HitBonus">Special2: </label><input type="number" id="<?= MISSILE_SPEC2_HIT_BONUS ?>" name="<?= MISSILE_SPEC2_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec2HitBonus() ?? "0" ?>"> <input type="number" id="<?= MISSILE_SPEC2_DAMAGE_BONUS ?>" name="<?= MISSILE_SPEC2_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec2DamageBonus() ?? "0" ?>"> <input type="text" id="<?= MISSILE_SPEC2_DESCRIPTION ?>" name="<?= MISSILE_SPEC2_DESCRIPTION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileSpec2Description() ?>"></div>
                    <div class="inputRow"><label for="missileSpec3HitBonus">Special3: </label><input type="number" id="<?= MISSILE_SPEC3_HIT_BONUS ?>" name="<?= MISSILE_SPEC3_HIT_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec3HitBonus() ?? "0" ?>"> <input type="number" id="<?= MISSILE_SPEC3_DAMAGE_BONUS ?>" name="<?= MISSILE_SPEC3_DAMAGE_BONUS ?>" size="1" min="-5" max="5" value="<? echo $playerCharacterWeapon->getMissileSpec3DamageBonus() ?? "0" ?>"> <input type="text" id="<?= MISSILE_SPEC3_DESCRIPTION ?>" name="<?= MISSILE_SPEC3_DESCRIPTION ?>" maxlength="32" value="<?= $playerCharacterWeapon->getMissileSpec3Description() ?>"></div>
                    </div>
                </div>
                <input type="hidden" name="<?= MISSILE_WEAPON_TYPE ?>" value="<?= WEAPON_TYPE_MISSILE ?>">
            <?php else: ?>
                <input type="hidden" name="<?= MISSILE_WEAPON_TYPE ?>" value="0">
                <input type="hidden" id="<?= STRENGTH_BONUS_AVAILABLE ?>" name="<?= STRENGTH_BONUS_AVAILABLE ?>" value="NO">
                <input type="hidden" id="<?= MISSILE_WEAPON_SPEED ?>" name="<?= MISSILE_WEAPON_SPEED ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_WEAPON_DAMAGE ?>" name="<?= MISSILE_WEAPON_DAMAGE ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_ATTACKS_PER_ROUND ?>" name="<?= MISSILE_ATTACKS_PER_ROUND ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_ADDITIONAL_TEXT ?>" name="<?= MISSILE_ADDITIONAL_TEXT ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_HIT_BONUS ?>" name="<?= MISSILE_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_DAMAGE_BONUS ?>" name="<?= MISSILE_DAMAGE_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">            
                <input type="hidden" id="<?= MISSILE_SPEC1_HIT_BONUS ?>" name="<?= MISSILE_SPEC1_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_SPEC1_DAMAGE_BONUS ?>" name="<?= MISSILE_SPEC1_DAMAGE_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MISSILE_SPEC1_DESCRIPTION ?>" name="<?= MISSILE_SPEC1_DESCRIPTION ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_SPEC2_HIT_BONUS ?>" name="<?= MISSILE_SPEC2_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MISSILE_SPEC2_DAMAGE_BONUS ?>" name="<?= MISSILE_SPEC2_DAMAGE_BONUS ?>"  value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MISSILE_SPEC2_DESCRIPTION ?>" name="<?= MISSILE_SPEC2_DESCRIPTION ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
                <input type="hidden" id="<?= MISSILE_SPEC3_HIT_BONUS ?>" name="<?= MISSILE_SPEC3_HIT_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MISSILE_SPEC3_DAMAGE_BONUS ?>" name="<?= MISSILE_SPEC3_DAMAGE_BONUS ?>" value="<?= OPTIONAL_INTEGER_PARAMETER ?>"> 
                <input type="hidden" id="<?= MISSILE_SPEC3_DESCRIPTION ?>" name="<?= MISSILE_SPEC3_DESCRIPTION ?>" value="<?= OPTIONAL_STRING_PARAMETER ?>">
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
    return  ($weapon_subtype == WEAPON_SUBTYPE_MISC_MISSILE) || ($weapon_subtype == WEAPON_SUBTYPE_BOW) || ($weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) ||  
            ($weapon_subtype == WEAPON_SUBTYPE_AXE) || ($weapon_subtype ==  WEAPON_SUBTYPE_HAMMER) || ($weapon_subtype == WEAPON_SUBTYPE_SLING);
}

function isMasterCraftDamageEligible($weapon_subtype) {
    return  ($weapon_subtype == WEAPON_SUBTYPE_MISC_MELEE) || ($weapon_subtype == WEAPON_SUBTYPE_AXE) || ($weapon_subtype == WEAPON_SUBTYPE_POLE_ARM) || 
            ($weapon_subtype == WEAPON_SUBTYPE_CLUB) || ($weapon_subtype == WEAPON_SUBTYPE_ONE_HANDED_SWORD) || ($weapon_subtype == WEAPON_SUBTYPE_HAMMER) || 
            ($weapon_subtype == WEAPON_SUBTYPE_LANCE) || ($weapon_subtype == WEAPON_SUBTYPE_TWO_HANDED_SWORD);
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