<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once 'characterName.php';
require_once 'characterSummary.php';
require_once 'weaponDetail.php';
require_once 'weaponType.php';
require_once 'weaponSubtype.php';
require_once 'characterClasses.php';

require_once 'playerName.php';
require_once 'characterName.php';
require_once 'weaponCatalogId.php';
require_once 'playerWeaponProficiencyId.php';
require_once 'weaponDescription.php';
require_once 'weaponLocation.php';
require_once 'isReady.php';
require_once 'craftStatus.php';
require_once 'hitBonus.php';
require_once 'hitBonusSpec1.php';
require_once 'hitBonusSpec2.php';
require_once 'hitBonusSpec3.php';
require_once 'damageBonus.php';
require_once 'weaponSpeed.php';
require_once 'weaponShortRange.php';
require_once 'weaponMediumRange.php';
require_once 'weaponLongRange.php';
require_once 'weaponDamage.php';
require_once 'damageBonusSpec1.php';
require_once 'damageBonusSpec2.php';
require_once 'damageBonusSpec3.php';
require_once 'attacksPerRound.php';
require_once 'strengthBonusAvailable.php';
require_once 'playerNote1.php';
require_once 'playerNote2.php';
require_once 'playerNote3.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getOptionalWeaponCatalogId($errors, $input);

$weaponDetail = null;
if ($input['weaponCatalogId'] != OPTIONAL_INTEGER_PARAMETER) {
    $weaponDetail = getWeaponDetail($pdo, $input['weaponCatalogId'], $errors);
} 

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input['playerName'], $input[CHARACTER_NAME]);

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
    <script src="env.js" type="module"></script>
    <script src="RestHelper.js" type="module"></script>
    <script src="editPlayerCharacterWeapons.js" type="module"></script>
    <script src="characterSheetContainer.js"></script>
    <script type="module">
        import { populateWeaponList, getWeaponDetail } from './editPlayerCharacterWeapons.js';

        // Attach to global scope
        window.populateWeaponList = populateWeaponList; 
        window.getWeaponDetail = getWeaponDetail;
    </script>
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
    <script src="submitTheForm.js"></script>
    <style>
        label {
            color: lightgray;
            font-size: 14px;
            vertical-align: sub;
        }
    </style>
</head>
<body>
    <i class="fa-solid fa-sword"></i> <i class="fa-regular fa-sword"></i> <i class="fa-light fa-sword"></i> <i class="fa-thin fa-sword"></i>
    <div class="characterSheetFeature">
        <a href="#">
            <i class="fa fa-plus"></i> Add a Weapon 
        </a>
        <div class="characterSheetFeatureContent">
            <form name="selectWeapon" id="selectWeapon" method="POST" action="<?= STARTING_URL . basename($_SERVER['PHP_SELF']) ?>">
                <label for="weaponNamePattern">Weapon Name</label><br>
                <input type="hidden" name="playerName" value="<?= $input['playerName'] ?>">
                <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
                <input type="text" id="weaponNamePattern" maxlength="32"><button type="button" onclick="populateWeaponList('weaponCatalogId', 'weaponNamePattern');">Go</button><br>
                <select name="weaponCatalogId" id="weaponCatalogId" onchange="getWeaponDetail('selectWeapon', 'weaponCatalogId');" hidden>
                </select>
            </form>
        </div>
    </div>
    <div>
        <form name="addPlayerCharacterWeapon" id="addPlayerCharacterWeapon" method="POST" action="<?= CurlHelper::buildUrl('characterActionRouter'); ?>">
            <input type="hidden" name="playerName" value="<?= $input['playerName'] ?>">
            <input type="hidden" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
            <input type="hidden" name="weaponCatalogId" value="<?= $input['weaponCatalogId'] ?>">
            <input type="hidden" name="playerWeaponProficiencyId" value="0">
            <label><?= $weaponDetail->getWeaponName(); ?></label><br>
            <label for="weaponDescription">Weapon Name</label><br>
            <input type="text" name="weaponDescription" id="weaponDescription" maxlength="32" value="<?= $weaponDetail->getWeaponName(); ?>">
        
            <input type="text" name="weaponSpeed" id="weaponSpeed" maxlength="32" value="<?= $weaponDetail->getWeaponSpeed(); ?>">
            <input type="text" name="weaponDamage" id="weaponDamage" maxlength="32" value="<?= $weaponDetail->getWeaponDamage(); ?>">
            <input type="text" name="attacksPerRound" id="attacksPerRound" maxlength="32">
            <label for="weaponLocation">Weapon Location</label><br>
            <input type="text" name="weaponLocation" id="weaponLocation" maxlength="32">
            <label for="isReady">Ready Weapon? </label><select name="isReady" id="isReady">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <?php if (isCavalier($character_summary->character_classes)): ?>
                <label for="isPreferred">Is Preferred?</label><select name="isPreferred" id="isPreferred">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            <?php else: ?>
                <input type="hidden" name="isPreferred" id="isPreferred" value="no">
            <?php endif ?>
            <input type="hidden" name="hitBonus" id="hitBonus" value="0">
            <input type="hidden" name="damageBonus" id="damageBonus" value="0">
            <select name="craftStatus" id="craftStatus" onchange="showHitDamageSections('craftStatus', 'masterCraftSection', 'magicSection');">
                <option value="<?= CRAFT_STATUS_ARTISAN ?>">Artisan</option>
                <option value="<?= CRAFT_STATUS_MASTERCRAFT ?>">MasterCraft</option>
                <option value="<?= CRAFT_STATUS_MAGIC ?>">Magic</option>
            </select>
            <div id="masterCraftSection" hidden>
                <label for="masterCraftHitBonus">Hit</label>
                <select id="masterCraftHitBonus" onchange="updateHitBonus('masterCraftHitBonus', 'hitBonus');">
                    <option value="0">Artisan</option>
                    <option value="1">Balanced</option>
                </select>
                <label for="masterCraftDamageBonus">Damage</label>
                <select id="masterCraftDamageBonus" onchange="updateDamageBonus('masterCraftDamageBonus', 'damageBonus');">
                    <option value="0">Artisan</option>
                    <option value="1">Sharp/Heavy</option>
                    <option value="2">Extra-Sharp/Extra-Heavy</option>
                </select>
            </div>
            <div id="magicSection" hidden>
                <label for="magicBonus">Magic Bonus</label>
                <select id="magicBonus" onchange="updateHitBonus('magicBonus', 'hitBonus'); updateDamageBonus('masterCraftDamageBonus', 'damageBonus'); showSpecBonusesSection('specBonuses');">
                    <option value="1">+1</option>
                    <option value="2">+2</option>
                    <option value="3">+3</option>
                    <option value="4">+4</option>
                    <option value="5">+5</option>
                </select>
                <div id="specBonuses" hidden>
                    <label for="hitBonusSpec1">Additional Hit Bonus</label><input type="text" name="hitBonusSpec1" id="hitBonusSpec1" maxlength="32"><br>
                    <label for="hitBonusSpec2">Additional Hit Bonus</label><input type="text" name="hitBonusSpec2" id="hitBonusSpec2" maxlength="32"><br>
                    <label for="hitBonusSpec3">Additional Hit Bonus</label><input type="text" name="hitBonusSpec3" id="hitBonusSpec3" maxlength="32"><br>
                    <br>
                    <label for="damageBonusSpec1">Additional Damage Bonus</label><input type="text" name="damageBonusSpec1" id="damageBonusSpec1" maxlength="32"><br>
                    <label for="damageBonusSpec2">Additional Damage Bonus</label><input type="text" name="damageBonusSpec2" id="damageBonusSpec2" maxlength="32"><br>
                    <label for="damageBonusSpec3">Additional Damage Bonus</label><input type="text" name="damageBonusSpec3" id="damageBonusSpec3" maxlength="32"><br>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<?php
function getWeaponDetail(\PDO $pdo, $weapon_id, &$errors) {
    $weapon_detail = new WeaponDetail();
    $weapon_detail->init($pdo, $weapon_id, $errors);

    if(!empty($errors)) {
        die($errors);
    }

    return $weapon_detail;
}

function isCavalier($character_classes) {
    foreach($character_classes AS $character_class) {
        if (getClassID($character_class_name) == CAVALIER) {
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
?>