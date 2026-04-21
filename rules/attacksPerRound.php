<?php
    require_once __DIR__ . '/../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../dbio/constants/weapons.php';
    require_once __DIR__ . '/../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

//!!!!!!!!!!!!!!!!!!!!
//! Unearthed Arcana ! (page 22)
//!!!!!!!!!!!!!!!!!!!!
/*
ATTACKS PER MELEE ROUND TABLE
(For Fighters, Cavaliers, and Sub-classes Thereof)
Level Attacks per Melee Round*

Fighter 1-6         1/1 round
Barbarian 1-5       1/1 round
Ranger 1-7          1/1 round
Archer 1-8          1/1 round
Cavalier 1-5        1/1 round
Paladin 1-6         1/1 round

Fighter 7-12        3/2 rounds
Barbarian 6-10      3/2 rounds
Ranger 8-14         3/2 rounds
Archer 9-16         3/2 rounds
Cavalier 6-10       3/2 rounds
Paladin 7-12        3/2 rounds

Fighter 13 & up     2/1 round
Barbarian 11 & up   2/1 round
Ranger 15 & up      2/1 round
Archer 16 & up      2/1 round
Cavalier 11-15      2/1 round
Paladin 12-18       2/1 round

Cavalier 16 & up    5/2 rounds
Paladin 19 & up     5/2 rounds

* With any thrusting or striking weapon

*/

const ATTACKS_PER_ROUND_1_FOR_1 = 1;
const ATTACKS_PER_ROUND_3_FOR_2 = 2;
const ATTACKS_PER_ROUND_2_FOR_1 = 3;
const ATTACKS_PER_ROUND_1_FOR_2 = 4;
const ATTACKS_PER_ROUND_3_FOR_1 = 5;
const ATTACKS_PER_ROUND_4_FOR_1 = 6;
const ATTACKS_PER_ROUND_5_FOR_1 = 7;
const ATTACKS_PER_ROUND_5_FOR_2 = 8;
const ATTACKS_PER_ROUND_6_FOR_1 = 9;

function getAttacksPerRound($class_id, $character_level, $is_weapon_preferred, $is_mounted) {
    if ($class_id == FIGHTER || $class_id == BERSERKER || $class_id == MARINER || $class_id == SENTINAL) {
        return getFighterAttacksPerRound($character_level);
    }

    if ($class_id == BARBARIAN) {
        return getBarbarianAttacksPerRound($character_level);
    }

    if ($class_id == RANGER) {
        return getRangerAttacksPerRound($character_level);
    }

    if ($class_id == ARCHER || $class_id == ARCHER_RANGER) {
        return getArcherAttacksPerRound($character_level);
    }

    if ($class_id == CAVALIER || $class_id == ELVEN_CAVALIER) {
        return getCavalierAttacksPerRound($character_level, $is_weapon_preferred, $is_mounted);
    }

    if ($class_id == PALADIN) {
        return getPaladinAttacksPerRound($character_level);
    }

    return ATTACKS_PER_ROUND_1_FOR_1;
}

function getAttacksPerRoundDescription($attacks_per_round) {
    switch($attacks_per_round) {
        case ATTACKS_PER_ROUND_1_FOR_1:
            return "1/1";
        case ATTACKS_PER_ROUND_3_FOR_2:
            return "3/2";
        case ATTACKS_PER_ROUND_2_FOR_1:
            return "2/1";
        case ATTACKS_PER_ROUND_1_FOR_2:
            return "1/2";
        case ATTACKS_PER_ROUND_3_FOR_1:
            return "3/1";
        case ATTACKS_PER_ROUND_4_FOR_1:
            return "4/1";
        case ATTACKS_PER_ROUND_5_FOR_1:
            return "5/1";
        case ATTACKS_PER_ROUND_5_FOR_2:
            return "5/2";
        case ATTACKS_PER_ROUND_6_FOR_1:
            return "6/1";
        default:
            return "1/1";
    }
}

function getFighterAttacksPerRound($character_level) {
    if ($character_level >= 1 && $character_level < 7) {
        return ATTACKS_PER_ROUND_1_FOR_1;
    }

    if ($character_level >= 7 && $character_level < 13) {
        return ATTACKS_PER_ROUND_3_FOR_2;
    }

    if ($character_level >= 13) {
        return ATTACKS_PER_ROUND_2_FOR_1;
    }
}
    
function getBarbarianAttacksPerRound($character_level) {
    if ($character_level >= 1 && $character_level < 6) {
        return ATTACKS_PER_ROUND_1_FOR_1;
    }

    if ($character_level >= 6 && $character_level < 11) {
        return ATTACKS_PER_ROUND_3_FOR_2;
    }

    if ($character_level >= 11) {
        return ATTACKS_PER_ROUND_2_FOR_1;
    }
}

function getRangerAttacksPerRound($character_level) {
    if ($character_level >= 1 && $character_level < 8) {
        return ATTACKS_PER_ROUND_1_FOR_1;
    }

    if ($character_level >= 8 && $character_level < 14) {
        return ATTACKS_PER_ROUND_3_FOR_2;
    }

    if ($character_level >= 15) {
        return ATTACKS_PER_ROUND_2_FOR_1;
    }
}

function getArcherAttacksPerRound($character_level) {
    if ($character_level >= 1 && $character_level < 9) {
        return ATTACKS_PER_ROUND_1_FOR_1;
    }

    if ($character_level >= 9 && $character_level < 16) {
        return ATTACKS_PER_ROUND_3_FOR_2;
    }

    if ($character_level >= 16) {
        return ATTACKS_PER_ROUND_2_FOR_1;
    }
}

function getCavalierAttacksPerRound($character_level, $is_weapon_preferred, $is_mounted) {
    if ($is_weapon_preferred && $is_mounted) {
        $character_level += 5;
    }

    if ($character_level >= 1 && $character_level < 6) {
        return ATTACKS_PER_ROUND_1_FOR_1;
    }

    if ($character_level >= 6 && $character_level < 11) {
        return ATTACKS_PER_ROUND_3_FOR_2;
    }

    if ($character_level >= 11 && $character_level < 16) {
        return ATTACKS_PER_ROUND_2_FOR_1;
    }

    if ($character_level >= 16) {
        return ATTACKS_PER_ROUND_5_FOR_2;
    }
}

function getPaladinAttacksPerRound($character_level) {
    if ($character_level >= 1 && $character_level < 7) {
        return ATTACKS_PER_ROUND_1_FOR_1;
    }

    if ($character_level >= 7 && $character_level < 13) {
        return ATTACKS_PER_ROUND_3_FOR_2;
    }

    if ($character_level >= 13 && $character_level < 19) {
        return ATTACKS_PER_ROUND_2_FOR_1;
    }

    if ($character_level >= 19) {
        return ATTACKS_PER_ROUND_5_FOR_2;
    }
}

function getSpecializedAttacksPerRound($character_level, $weapon_type, $weapon_subtype, $weapon_proficiency_id) {

    $is_melee_weapon = isMeleeWeapon($weapon_subtype);
    if ($character_level >= 1 && $character_level < 7) {
        if ($weapon_type == WEAPON_TYPE_MISSILE && $weapon_proficiency_id == DAGGER) {
            return ATTACKS_PER_ROUND_3_FOR_1;
        } else if ($is_melee_weapon) {
            return ATTACKS_PER_ROUND_3_FOR_2;
        } if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } else if ($weapon_proficiency_id == LIGHT_CROSSBOW) {
            return ATTACKS_PER_ROUND_1_FOR_1;
        } else if ($weapon_proficiency_id == HEAVY_CROSSBOW) {
            return ATTACKS_PER_ROUND_1_FOR_2;
        } else if ($weapon_proficiency_id == DOKYU) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } else if ($weapon_proficiency_id == LASSO || $weapon_proficiency_id == STAFF_SLING) {
            return ATTACKS_PER_ROUND_1_FOR_1;
        } else if ($weapon_proficiency_id == DART) {
            return ATTACKS_PER_ROUND_4_FOR_1;
        } else if ($weapon_type == WEAPON_TYPE_MISSILE) {
            return ATTACKS_PER_ROUND_3_FOR_2;
        } else {
            return ATTACKS_PER_ROUND_1_FOR_1;
        }
    }

    if ($character_level >= 7 && $character_level < 13) {
        if ($weapon_type == WEAPON_TYPE_MISSILE && $weapon_proficiency_id == DAGGER) {
            return ATTACKS_PER_ROUND_4_FOR_1;
        } else if ($is_melee_weapon) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
            return ATTACKS_PER_ROUND_3_FOR_1;
        } else if ($weapon_proficiency_id == LIGHT_CROSSBOW) {
            return ATTACKS_PER_ROUND_3_FOR_2;
        } else if ($weapon_proficiency_id == HEAVY_CROSSBOW) {
            return ATTACKS_PER_ROUND_1_FOR_1;
        } else if ($weapon_proficiency_id == DOKYU) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } else if ($weapon_proficiency_id == LASSO || $weapon_proficiency_id == STAFF_SLING) {
            return ATTACKS_PER_ROUND_3_FOR_2;
        } else if ($weapon_proficiency_id == DART) {
            return ATTACKS_PER_ROUND_5_FOR_1;
        } else if ($weapon_type == WEAPON_TYPE_MISSILE) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } else {
            return ATTACKS_PER_ROUND_1_FOR_1;
        }
    }

    if ($character_level >= 13) {
        if ($weapon_type == WEAPON_TYPE_MISSILE && $weapon_proficiency_id == DAGGER) {
            return ATTACKS_PER_ROUND_5_FOR_1;
        } else if ($is_melee_weapon) {
            return ATTACKS_PER_ROUND_5_FOR_2;
        } if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
            return ATTACKS_PER_ROUND_4_FOR_1;
        } else if ($weapon_proficiency_id == LIGHT_CROSSBOW) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } else if ($weapon_proficiency_id == HEAVY_CROSSBOW) {
            return ATTACKS_PER_ROUND_3_FOR_2;
        } else if ($weapon_proficiency_id == DOKYU) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } else if ($weapon_proficiency_id == LASSO || $weapon_proficiency_id == STAFF_SLING) {
            return ATTACKS_PER_ROUND_2_FOR_1;
        } else if ($weapon_proficiency_id == DART) {
            return ATTACKS_PER_ROUND_6_FOR_1;
        } else if ($weapon_type == WEAPON_TYPE_MISSILE) {
            return ATTACKS_PER_ROUND_5_FOR_2;
        } else {
            return ATTACKS_PER_ROUND_1_FOR_1;
        }
    }
}

function isMeleeWeapon($weapon_subtype) {
    return $weapon_subtype == WEAPON_SUBTYPE_MISC_MELEE || $weapon_subtype == WEAPON_SUBTYPE_AXE || $weapon_subtype == WEAPON_SUBTYPE_POLE_ARM || $weapon_subtype == WEAPON_SUBTYPE_CLUB || $weapon_subtype == WEAPON_SUBTYPE_ONE_HANDED_SWORD || $weapon_subtype == WEAPON_SUBTYPE_HAMMER || $weapon_subtype == WEAPON_SUBTYPE_LANCE || $weapon_subtype == WEAPON_SUBTYPE_TWO_HANDED_SWORD;
}

//!!!!!!!!!!!!!!!!!!!!
//! Unearthed Arcana ! (page 18)
//!!!!!!!!!!!!!!!!!!!!
/*
Weapon Specialization Table for Fighters, Rangers,  Archer (melee only), Archer-Ranger (melee only), Berserker, Mariner, Sentinal
                                                  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% 
                                                  % Number of Attacks Per Round % 
                                                  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% 

Level of Specialist     Melee Weapon    Bow     Light Crossbow  Heavy Crossbow  Lasso and Staff Sling   Thrown Dagger   Thrown Dart     Other Missiles and Hurled Weapons
1-6                     3/2             2/1     1/1             1/2             1/1                     3/1             4/1             3/2
7-12                    2/1             3/1     3/2             1/1             3/2                     4/1             5/1             2/1
13+                     5/2             4/1     2/1             3/2             2/1                     5/1             6/1             5/2

 At 7th level and above all Archers can fire three arrows per round instead of just two. 
*/

?>
