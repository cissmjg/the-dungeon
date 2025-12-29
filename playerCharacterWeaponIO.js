import { buildURL, addParameter } from './RestHelper.js';

const initialWeaponSelectPrompt = "[Select a Weapon]";

export function populateWeaponLocation(weaponLocationHintsName, weaponLocationName) {
    const jqWeaponLocationHintsName = '#' + weaponLocationHintsName;
    const jqWeaponLocationName = '#' + weaponLocationName;
    const jqSelectedLocation = $(jqWeaponLocationHintsName).val();

    $(jqWeaponLocationName).val(jqSelectedLocation);
}

export function craftStatusChanged(craftStatusId, masterCraftSectionId, magicSectionId, meleeWeaponType, missileWeaponType, magicBonusId, meleeHitBonusId, meleeDamageBonusId, meleeSpec1HitBonusId, meleeSpec2HitBonusId, meleeSpec3HitBonusId, meleeSpec1DamageBonusId, meleeSpec2DamageBonusId, meleeSpec3DamageBonusId, missileHitBonusId, missileDamageBonusId, missileSpec1HitBonusId, missileSpec2HitBonusId, missileSpec3HitBonusId, missileSpec1DamageBonusId, missileSpec2DamageBonusId, missileSpec3DamageBonusId) {
    showHitDamageSections(craftStatusId, masterCraftSectionId, magicSectionId, meleeWeaponType, missileWeaponType, magicBonusId);
    resetHitDamageFields(meleeHitBonusId, meleeDamageBonusId, meleeSpec1HitBonusId, meleeSpec2HitBonusId, meleeSpec3HitBonusId, meleeSpec1DamageBonusId, meleeSpec2DamageBonusId, meleeSpec3DamageBonusId, missileHitBonusId, missileDamageBonusId, missileSpec1HitBonusId, missileSpec2HitBonusId, missileSpec3HitBonusId, missileSpec1DamageBonusId, missileSpec2DamageBonusId, missileSpec3DamageBonusId);
}

function resetHitDamageFields(meleeHitBonusId, meleeDamageBonusId, meleeSpec1HitBonusId, meleeSpec2HitBonusId, meleeSpec3HitBonusId, meleeSpec1DamageBonusId, meleeSpec2DamageBonusId, meleeSpec3DamageBonusId, missileHitBonusId, missileDamageBonusId, missileSpec1HitBonusId, missileSpec2HitBonusId, missileSpec3HitBonusId, missileSpec1DamageBonusId, missileSpec2DamageBonusId, missileSpec3DamageBonusId) {
    const jqMeleeHitBonusId = '#' + meleeHitBonusId;
    const jqMeleeDamageBonusId = '#' + meleeDamageBonusId;
    const jqMeleeSpec1HitBonusId = '#' + meleeSpec1HitBonusId
    const jqMeleeSpec2HitBonusId = '#' + meleeSpec2HitBonusId;
    const jqMeleeSpec3HitBonusId = '#' + meleeSpec3HitBonusId;
    const jqMeleeSpec1DamageBonusId = '#' + meleeSpec1DamageBonusId;
    const jqMeleeSpec2DamageBonusId = '#' + meleeSpec2DamageBonusId;
    const jqMeleeSpec3DamageBonusId = '#' + meleeSpec3DamageBonusId;
    const jqMissileHitBonusId = '#' + missileHitBonusId;
    const jqMissileDamageBonusId = '#' + missileDamageBonusId;
    const jqMissileSpec1HitBonusId = '#' + missileSpec1HitBonusId;
    const jqMissileSpec2HitBonusId = '#' + missileSpec2HitBonusId;
    const jqMissileSpec3HitBonusId = '#' + missileSpec3HitBonusId;
    const jqMissileSpec1DamageBonusId = '#' + missileSpec1DamageBonusId;
    const jqMissileSpec2DamageBonusId = '#' + missileSpec2DamageBonusId;
    const jqMissileSpec3DamageBonusId = '#' + missileSpec3DamageBonusId;

    $(jqMeleeHitBonusId).val("0");
    $(jqMeleeDamageBonusId).val("0");
    $(jqMeleeSpec1HitBonusId).val("0");
    $(jqMeleeSpec2HitBonusId).val("0");
    $(jqMeleeSpec3HitBonusId).val("0");
    $(jqMeleeSpec1DamageBonusId).val("0");
    $(jqMeleeSpec2DamageBonusId).val("0");
    $(jqMeleeSpec3DamageBonusId).val("0");
    $(jqMissileHitBonusId).val("0");
    $(jqMissileDamageBonusId).val("0");
    $(jqMissileSpec1HitBonusId).val("0");
    $(jqMissileSpec2HitBonusId).val("0");
    $(jqMissileSpec3HitBonusId).val("0");
    $(jqMissileSpec1DamageBonusId).val("0");
    $(jqMissileSpec2DamageBonusId).val("0");
    $(jqMissileSpec3DamageBonusId).val("0");
}

export function showHitDamageSections(craftStatusId, masterCraftSectionId, magicSectionId, meleeWeaponType, missileWeaponType, magicBonusId) {
    const jqMasterCraftSectionId = '#' + masterCraftSectionId;
    const jqMagicSectionMeleeId = '#' + magicSectionId + 'Melee';
    const jqMagicSectionMissileId = '#' + magicSectionId + 'Missile';
    const jqMagicBonusId = '#' + magicBonusId;
    
    // Craft Status values
    const craftStatusMastercraft = "1";
    const craftStatusMagic = "2";

    // Weapon Type
    const weaponTypeMelee = "1";
    const weaponTypeMissile = "2";

    const jqCraftStatusId = '#' + craftStatusId;
    const craftStatus = $(jqCraftStatusId).val();
    switch(craftStatus) {
        case craftStatusMastercraft:
            $(jqMasterCraftSectionId).show();
            $(jqMagicSectionMeleeId).hide();
            $(jqMagicSectionMissileId).hide();
            $(jqMagicBonusId).hide();
            break;
        case craftStatusMagic:
            $(jqMasterCraftSectionId).hide();
            $(jqMagicBonusId).show();
            if (meleeWeaponType == weaponTypeMelee) {
                $(jqMagicSectionMeleeId).show();
            }

            if (missileWeaponType == weaponTypeMissile) {
                $(jqMagicSectionMissileId).show();
            }
            break;
        default:
            $(jqMasterCraftSectionId).hide();
            $(jqMagicSectionMeleeId).hide();
            $(jqMagicSectionMissileId).hide();
            $(jqMagicBonusId).hide();
            break;
    }
}

export function updateHitBonus(mastercraftHitDescriptionId, meleeHitBonusId, missileHitBonusId) {
    const jqMastercraftHitDescriptionId = '#' + mastercraftHitDescriptionId;
    const jqMeleeHitBonusId = '#' + meleeHitBonusId;
    const jqMissileHitBonusId = '#' + missileHitBonusId;
    const jqMasterCraftHitDescription = $(jqMastercraftHitDescriptionId).val();

    switch(jqMasterCraftHitDescription) {
        case "None":
            $(jqMeleeHitBonusId).val("0");
            $(jqMissileHitBonusId).val("0");
            break;
        case "Balanced":
            $(jqMeleeHitBonusId).val("1");
            $(jqMissileHitBonusId).val("1");
            break;
        default:
            break;
    }
}

export function updateDamageBonus(mastercraftDamageDescriptionId, meleeDamageBonusId, missileDamageBonusId) {
    const jqMastercraftDamageDescriptionId = '#' + mastercraftDamageDescriptionId;
    const jqMeleeDamageBonusId = '#' + meleeDamageBonusId;
    const jqMissileDamageBonusId = '#' + missileDamageBonusId;
    const jqMasterCraftDamageDescription = $(jqMastercraftDamageDescriptionId).val();

    switch(jqMasterCraftDamageDescription) {
        case "None":
            $(jqMeleeDamageBonusId).val("0");
            $(jqMissileDamageBonusId).val("0");
            break;
        case "Sharp/Heavy":
            $(jqMeleeDamageBonusId).val("1");
            $(jqMissileDamageBonusId).val("1");
            break;
        case "Extra-Sharp/Extra-Heavy":
            $(jqMeleeDamageBonusId).val("2");
            $(jqMissileDamageBonusId).val("2");
            break;
        default:
            break;
    }
}

export function populateDefaultHitDamageBonuses(magicBonusId, meleeHitBonusId, meleeDamageBonusId, missileHitBonusId, missileDamageBonusId) {
    const jqMagicBonusId = '#' + magicBonusId;
    const jqMeleeHitBonusId = '#' + meleeHitBonusId;
    const jqMeleeDamageBonusId = '#' + meleeDamageBonusId;
    const jqMissileHitBonusId = '#' + missileHitBonusId;
    const jqMissileDamageBonusId = '#' + missileDamageBonusId;

    const magicBonus = $(jqMagicBonusId).val();
    $(jqMeleeHitBonusId).val(magicBonus);
    $(jqMeleeDamageBonusId).val(magicBonus);
    $(jqMissileHitBonusId).val(magicBonus);
    $(jqMissileDamageBonusId).val(magicBonus);
}