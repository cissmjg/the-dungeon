<?php

const CLERIC = 2;
const DRUID = 3;
const FIGHTER = 4;
const PALADIN = 5;
const RANGER = 6;
const MAGIC_USER = 7;
const ILLUSIONIST = 8;
const THIEF = 9;
const ASSASSIN = 10;
const MONK = 11;
const CAVALIER = 12;
const BARBARIAN = 13;
const THIEF_ACROBAT = 14;
const ORIENTAL_BARBARIAN = 15;
const BUSHI = 16;
const KENSAI = 17;
const OA_MONK = 18;
const NINJA = 19;
const SAMURAI = 20;
const SHUKENJA = 21;
const SOHEI = 22;
const WU_JEN = 23;
const YAKUZA = 24;
const ANTI_PALADIN = 25;
const ARCHER = 26;
const BERSERKER = 27;
const MARINER = 28;
const PIAO_SHIH = 29;
const SENTINAL = 30;
const SMITH = 31;
const SUMOTORI = 32;
const GREATER_MAGE = 38;
const BANDIT = 39;
const DUELIST = 40;
const ESCRIMA = 41;
const NEW_MONK = 42;
const HEALER = 43;
const MERCHANT = 44;
const BARD = 45;
const NEW_BARD = 46;
const ARCHER_RANGER = 47;

function getClassID($class_name) {
    switch($class_name) {
        case "Cleric":
            return CLERIC;
        case "Druid":
            return DRUID;
        case "Fighter":
            return FIGHTER;
        case "Paladin":
            return PALADIN;
        case "Ranger":
            return RANGER;
        case "Magic-User":
            return MAGIC_USER;
        case "Illusionist":
            return ILLUSIONIST;
        case "Thief":
            return THIEF;
        case "Assassin":
            return ASSASSIN;
        case "Monk":
            return "Monk";
        case "Cavalier":
            return CAVALIER;
        case "Barbarian":
            return BARBARIAN;
        case "Thief-Acrobat":
            return THIEF_ACROBAT;
        case "Oriental Barbarian":
            return ORIENTAL_BARBARIAN;
        case "Bushi":
            return BUSHI;
        case "Kensai":
            return KENSAI;
        case "OA Monk":
            return OA_MONK;
        case "Ninja":
            return NINJA;
        case "Samurai":
            return SAMURAI;
        case "Shukenja":
            return SHUKENJA;
        case "Sohei":
            return SOHEI;
        case "Wu Jen":
            return WU_JEN;
        case "Yakuza":
            return YAKUZA;
        case "Anti-Paladin":
            return ANTI_PALADIN;
        case "Archer":
            return ARCHER;
        case "Berserker":
            return BERSERKER;
        case "Mariner":
            return MARINER;
        case "Piao Shih":
            return PIAO_SHIH;
        case "Sentinal":
            return SENTINAL;
        case "Smith":
            return SMITH;
        case "Sumotori":
            return SUMOTORI;
        case "Greater Mage":
            return GREATER_MAGE;
        case "Bandit":
            return BANDIT;
        case "Duelist":
            return DUELIST;
        case "Escrima":
            return ESCRIMA;
        case "New Monk":
            return NEW_MONK;
        case "Healer":
            return HEALER;
        case "Merchant":
            return MERCHANT;
        case "Bard":
            return BARD;
        case "New Bard":
            return NEW_BARD;
        case "Archer-Ranger":
            return ARCHER_RANGER;
        default:
            return -1;
    }
}

function isArcaneSpellcaster($class_id) {
    return ($class_id == MAGIC_USER || $class_id == ILLUSIONIST || $class_id == WU_JEN || $class_id == ARCHER || $class_id == GREATER_MAGE || $class_id == GREATER_MAGE || $class_id == HEALER || $class_id == NEW_BARD || $class_id == ARCHER_RANGER);
}

function isDivineSpellcaster($class_id) {
    return ($class_id == CLERIC || $class_id == DRUID || $class_id == PALADIN || $class_id == RANGER || $class_id == SHUKENJA || $class_id == ANTI_PALADIN || $class_id == HEALER || $class_id == NEW_BARD);
}

function isCharacterFighterType($class_id) {
    return ($class_id == FIGHTER || $class_id == RANGER || $class_id == ARCHER || $class_id == ARCHER_RANGER || $class_id == BERSERKER || $class_id == MARINER || $class_id == SENTINAL);

}
?>