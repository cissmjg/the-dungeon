<?php

const CLERIC_NON_PROFICIENCY = -3;
const DRUID_NON_PROFICIENCY = -4;
const FIGHTER_NON_PROFICIENCY = -2;
const MAGIC_USER_NON_PROFICIENCY = -5;
const THIEF_NON_PROFICIENCY = -3;
const ASSASSIN_NON_PROFICIENCY = -2;
const MONK_NON_PROFICIENCY = -3;
const CAVALIER_NON_PROFICIENCY = -3;
const BARBARIAN_NON_PROFICIENCY = -1;
const SAMURAI_NON_PROFICIENCY = -2;
const SHUKENJA_NON_PROFICIENCY = -4;
const SOHEI_NON_PROFICIENCY = -2;
const KENSAI_NON_PROFICIENCY = -3;
const ORIENTAL_BARBARIAN_NON_PROFICIENCY = -2;
const BUSHI_NON_PROFICIENCY = -2;
const WU_JEN_NON_PROFICIENCY = -5;
const NINJA_NON_PROFICIENCY = -4;
const ARCHER_NON_PROFICIENCY = -3;
const MERCHANT_NON_PROFICIENCY = -4;
const BARD_NON_PROFICIENCY = -2;
const NEW_BARD_NON_PROFICIENCY = -4;

function getNonProficiencyPenalty($class_id) {
    switch($class_id) {
        case CLERIC:
            return CLERIC_NON_PROFICIENCY;
        case DRUID:
            return DRUID_NON_PROFICIENCY;
        case FIGHTER:
            return FIGHTER_NON_PROFICIENCY;
        case PALADIN:
            return CAVALIER_NON_PROFICIENCY;
        case RANGER:
            return FIGHTER_NON_PROFICIENCY;
        case MAGIC_USER:
            return MAGIC_USER_NON_PROFICIENCY;
        case ILLUSIONIST:
            return MAGIC_USER_NON_PROFICIENCY;
        case THIEF:
            return THIEF_NON_PROFICIENCY;
        case ASSASSIN:
            return ASSASSIN_NON_PROFICIENCY;
        case MONK:
            return MONK_NON_PROFICIENCY;
        case CAVALIER:
            return CAVALIER_NON_PROFICIENCY;
        case BARBARIAN:
            return BARBARIAN_NON_PROFICIENCY;
        case THIEF_ACROBAT:
            return THIEF_NON_PROFICIENCY;
        case ORIENTAL_BARBARIAN:
            return ORIENTAL_BARBARIAN_NON_PROFICIENCY;
        case BUSHI:
            return BUSHI_NON_PROFICIENCY;
        case KENSAI:
            return KENSAI_NON_PROFICIENCY;
        case OA_MONK:
            return MONK_NON_PROFICIENCY;
        case NINJA:
            return NINJA_NON_PROFICIENCY;
        case SAMURAI:
            return SAMURAI_NON_PROFICIENCY;
        case SHUKENJA:
            return SHUKENJA_NON_PROFICIENCY;
        case SOHEI:
            return SOHEI_NON_PROFICIENCY;
        case WU_JEN:
            return WU_JEN_NON_PROFICIENCY;
        case YAKUZA:
            return THIEF_NON_PROFICIENCY;
        case ANTI_PALADIN:
            return CAVALIER_NON_PROFICIENCY;
        case ARCHER:
            return ARCHER_NON_PROFICIENCY;
        case BERSERKER:
            return FIGHTER_NON_PROFICIENCY;
        case MARINER:
            return FIGHTER_NON_PROFICIENCY;
        case PIAO_SHIH:
            return FIGHTER_NON_PROFICIENCY;
        case SENTINAL:
            return FIGHTER_NON_PROFICIENCY;
        case SMITH:
            return FIGHTER_NON_PROFICIENCY;
        case SUMOTORI:
            return BUSHI_NON_PROFICIENCY;
        case GREATER_MAGE:
            return MAGIC_USER_NON_PROFICIENCY;
        case BANDIT:
            return FIGHTER_NON_PROFICIENCY;
        case DUELIST:
            return FIGHTER_NON_PROFICIENCY;
        case ESCRIMA:
            return FIGHTER_NON_PROFICIENCY;
        case NEW_MONK:
            return MONK_NON_PROFICIENCY;
        case HEALER:
            return CLERIC_NON_PROFICIENCY;
        case MERCHANT:
            return MERCHANT_NON_PROFICIENCY;
        case BARD:
            return BARD_NON_PROFICIENCY;
        case NEW_BARD:
            return NEW_BARD_NON_PROFICIENCY;
        case ARCHER_RANGER:
            return ARCHER_NON_PROFICIENCY;
        default: 
            return -10;
    }
}
?>