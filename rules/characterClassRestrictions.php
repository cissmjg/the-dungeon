<?php
declare(strict_types=1);

require_once __DIR__ . '/../dbio/constants/characterAttributes.php';
require_once __DIR__ . '/../dbio/constants/characterClasses.php';

const MINIMUM_ATTRIBUTE_VALUE = 3;
const MAXIMUM_ATTRIBUTE_VALUE = 25;

$character_class_maximums = [];
$character_class_minimums = [];

$character_class_minimums[CLERIC] = [];
$character_class_maximums[CLERIC] = [];
$character_class_minimums[CLERIC][CHARACTER_WISDOM] = 9;

$character_class_minimums[DRUID] = [];
$character_class_maximums[DRUID] = [];
$character_class_minimums[DRUID][CHARACTER_WISDOM] = 12;
$character_class_minimums[DRUID][CHARACTER_CHARISMA] = 15;

$character_class_minimums[FIGHTER] = [];
$character_class_maximums[FIGHTER] = [];
$character_class_minimums[FIGHTER][CHARACTER_STRENGTH] = 9;
$character_class_minimums[FIGHTER][CHARACTER_CONSTITUTION] = 7;

$character_class_minimums[PALADIN] = [];
$character_class_maximums[PALADIN] = [];
$character_class_minimums[PALADIN][CHARACTER_STRENGTH] = 15;
$character_class_minimums[PALADIN][CHARACTER_INTELLIGENCE] = 10;
$character_class_minimums[PALADIN][CHARACTER_WISDOM] = 13;
$character_class_minimums[PALADIN][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[PALADIN][CHARACTER_CONSTITUTION] = 15;
$character_class_minimums[PALADIN][CHARACTER_CHARISMA] = 17;

$character_class_minimums[RANGER] = [];
$character_class_maximums[RANGER] = [];
$character_class_minimums[RANGER][CHARACTER_STRENGTH] = 13;
$character_class_minimums[RANGER][CHARACTER_INTELLIGENCE] = 13;
$character_class_minimums[RANGER][CHARACTER_WISDOM] = 14;
$character_class_minimums[RANGER][CHARACTER_CONSTITUTION] = 14;

$character_class_minimums[MAGIC_USER] = [];
$character_class_maximums[MAGIC_USER] = [];
$character_class_minimums[MAGIC_USER][CHARACTER_INTELLIGENCE] = 9;
$character_class_minimums[MAGIC_USER][CHARACTER_DEXTERITY] = 6;

$character_class_minimums[ILLUSIONIST] = [];
$character_class_maximums[ILLUSIONIST] = [];
$character_class_minimums[ILLUSIONIST][CHARACTER_INTELLIGENCE] = 15;
$character_class_minimums[ILLUSIONIST][CHARACTER_DEXTERITY] = 16;

$character_class_minimums[THIEF] = [];
$character_class_maximums[THIEF] = [];
$character_class_minimums[THIEF][CHARACTER_DEXTERITY] = 9;

$character_class_minimums[ASSASSIN] = [];
$character_class_maximums[ASSASSIN] = [];
$character_class_minimums[ASSASSIN][CHARACTER_STRENGTH] = 12;
$character_class_minimums[ASSASSIN][CHARACTER_INTELLIGENCE] = 11;
$character_class_minimums[ASSASSIN][CHARACTER_DEXTERITY] = 12;

$character_class_minimums[MONK] = [];
$character_class_maximums[MONK] = [];
$character_class_minimums[MONK][CHARACTER_STRENGTH] = 15;
$character_class_minimums[MONK][CHARACTER_WISDOM] = 15;
$character_class_minimums[MONK][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[MONK][CHARACTER_CONSTITUTION] = 11;

$character_class_minimums[CAVALIER] = [];
$character_class_maximums[CAVALIER] = [];
$character_class_minimums[CAVALIER][CHARACTER_STRENGTH] = 15;
$character_class_minimums[CAVALIER][CHARACTER_INTELLIGENCE] = 10;
$character_class_minimums[CAVALIER][CHARACTER_WISDOM] = 10;
$character_class_minimums[CAVALIER][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[CAVALIER][CHARACTER_CONSTITUTION] = 15;

$character_class_maximums[BARBARIAN] = [];
$character_class_minimums[BARBARIAN] = [];

$character_class_minimums[BARBARIAN][CHARACTER_STRENGTH] = 15;
$character_class_minimums[BARBARIAN][CHARACTER_DEXTERITY] = 14;
$character_class_minimums[BARBARIAN][CHARACTER_CONSTITUTION] = 15;

$character_class_maximums[BARBARIAN][CHARACTER_WISDOM] = 16;

$character_class_minimums[THIEF_ACROBAT] = [];
$character_class_maximums[THIEF_ACROBAT] = [];
$character_class_minimums[THIEF_ACROBAT][CHARACTER_STRENGTH] = 15;
$character_class_minimums[THIEF_ACROBAT][CHARACTER_DEXTERITY] = 16;

$character_class_maximums[ORIENTAL_BARBARIAN] = [];
$character_class_minimums[ORIENTAL_BARBARIAN] = [];

$character_class_minimums[ORIENTAL_BARBARIAN][CHARACTER_STRENGTH] = 15;
$character_class_minimums[ORIENTAL_BARBARIAN][CHARACTER_DEXTERITY] = 14;
$character_class_minimums[ORIENTAL_BARBARIAN][CHARACTER_CONSTITUTION] = 15;

$character_class_maximums[ORIENTAL_BARBARIAN][CHARACTER_WISDOM] = 16;

$character_class_minimums[BUSHI] = [];
$character_class_maximums[BUSHI] = [];
$character_class_minimums[BUSHI][CHARACTER_STRENGTH] = 9;
$character_class_minimums[BUSHI][CHARACTER_DEXTERITY] = 8;
$character_class_minimums[BUSHI][CHARACTER_CONSTITUTION] = 8;

$character_class_minimums[KENSAI] = [];
$character_class_maximums[KENSAI] = [];
$character_class_minimums[KENSAI][CHARACTER_STRENGTH] = 12;
$character_class_minimums[KENSAI][CHARACTER_DEXTERITY] = 14;
$character_class_minimums[KENSAI][CHARACTER_WISDOM] = 12;

$character_class_minimums[OA_MONK] = [];
$character_class_maximums[OA_MONK] = [];
$character_class_minimums[OA_MONK][CHARACTER_STRENGTH] = 15;
$character_class_minimums[OA_MONK][CHARACTER_WISDOM] = 15;
$character_class_minimums[OA_MONK][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[OA_MONK][CHARACTER_CONSTITUTION] = 11;

$character_class_minimums[NINJA] = [];
$character_class_maximums[NINJA] = [];
$character_class_minimums[NINJA][CHARACTER_INTELLIGENCE] = 10;

$character_class_minimums[SHUKENJA] = [];
$character_class_maximums[SHUKENJA] = [];
$character_class_minimums[SHUKENJA][CHARACTER_STRENGTH] = 9;
$character_class_minimums[SHUKENJA][CHARACTER_WISDOM] = 12;
$character_class_minimums[SHUKENJA][CHARACTER_CONSTITUTION] = 9;

$character_class_minimums[SOHEI] = [];
$character_class_maximums[SOHEI] = [];
$character_class_minimums[SOHEI][CHARACTER_STRENGTH] = 13;
$character_class_minimums[SOHEI][CHARACTER_WISDOM] = 10;
$character_class_minimums[SOHEI][CHARACTER_CONSTITUTION] = 10;

$character_class_minimums[WU_JEN] = [];
$character_class_maximums[WU_JEN] = [];
$character_class_minimums[WU_JEN][CHARACTER_INTELLIGENCE] = 13;

$character_class_minimums[YAKUZA] = [];
$character_class_maximums[YAKUZA] = [];
$character_class_minimums[YAKUZA][CHARACTER_STRENGTH] = 11;
$character_class_minimums[YAKUZA][CHARACTER_INTELLIGENCE] = 15;
$character_class_minimums[YAKUZA][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[YAKUZA][CHARACTER_CHARISMA] = 16;

$character_class_minimums[ANTI_PALADIN] = [];
$character_class_maximums[ANTI_PALADIN] = [];
$character_class_minimums[ANTI_PALADIN][CHARACTER_STRENGTH] = 12;
$character_class_minimums[ANTI_PALADIN][CHARACTER_INTELLIGENCE] = 10;
$character_class_minimums[ANTI_PALADIN][CHARACTER_WISDOM] = 12;
$character_class_minimums[ANTI_PALADIN][CHARACTER_DEXTERITY] = 6;
$character_class_minimums[ANTI_PALADIN][CHARACTER_CONSTITUTION] = 10;

$character_class_minimums[ARCHER] = [];
$character_class_maximums[ARCHER] = [];
$character_class_minimums[ARCHER][CHARACTER_STRENGTH] = 15;
$character_class_minimums[ARCHER][CHARACTER_INTELLIGENCE] = 6;
$character_class_minimums[ARCHER][CHARACTER_WISDOM] = 6;
$character_class_minimums[ARCHER][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[ARCHER][CHARACTER_CONSTITUTION] = 9;

$character_class_maximums[BERSERKER] = [];
$character_class_minimums[BERSERKER] = [];
$character_class_minimums[BERSERKER][CHARACTER_STRENGTH] = 9;
$character_class_minimums[BERSERKER][CHARACTER_CONSTITUTION] = 9;
$character_class_maximums[BERSERKER][CHARACTER_INTELLIGENCE] = 9;

$character_class_minimums[MARINER] = [];
$character_class_maximums[MARINER] = [];
$character_class_minimums[MARINER][CHARACTER_STRENGTH] = 12;
$character_class_minimums[MARINER][CHARACTER_INTELLIGENCE] = 12;
$character_class_minimums[MARINER][CHARACTER_WISDOM] = 13;
$character_class_minimums[MARINER][CHARACTER_CONSTITUTION] = 10;

$character_class_minimums[PIAO_SHIH] = [];
$character_class_maximums[PIAO_SHIH] = [];
$character_class_minimums[PIAO_SHIH][CHARACTER_STRENGTH] = 12;
$character_class_minimums[PIAO_SHIH][CHARACTER_INTELLIGENCE] = 12;
$character_class_minimums[PIAO_SHIH][CHARACTER_WISDOM] = 12;
$character_class_minimums[PIAO_SHIH][CHARACTER_CONSTITUTION] = 12;

$character_class_minimums[SENTINAL] = [];
$character_class_maximums[SENTINAL] = [];
$character_class_minimums[SENTINAL][CHARACTER_STRENGTH] = 9;
$character_class_minimums[SENTINAL][CHARACTER_INTELLIGENCE] = 13;
$character_class_minimums[SENTINAL][CHARACTER_WISDOM] = 14;
$character_class_minimums[SENTINAL][CHARACTER_DEXTERITY] = 12;

$character_class_minimums[SMITH] = [];
$character_class_maximums[SMITH] = [];
$character_class_minimums[SMITH][CHARACTER_STRENGTH] = 12;
$character_class_minimums[SMITH][CHARACTER_DEXTERITY] = 13;

$character_class_minimums[SUMOTORI] = [];
$character_class_maximums[SUMOTORI] = [];
$character_class_minimums[SUMOTORI][CHARACTER_STRENGTH] = 16;
$character_class_minimums[SUMOTORI][CHARACTER_WISDOM] = 13;
$character_class_minimums[SUMOTORI][CHARACTER_DEXTERITY] = 12;
$character_class_minimums[SUMOTORI][CHARACTER_CONSTITUTION] = 14; 

$character_class_minimums[GREATER_MAGE] = [];
$character_class_maximums[GREATER_MAGE] = [];
$character_class_minimums[GREATER_MAGE][CHARACTER_INTELLIGENCE] = 18;
$character_class_minimums[GREATER_MAGE][CHARACTER_CONSTITUTION] = 15;
$character_class_minimums[GREATER_MAGE][CHARACTER_DEXTERITY] = 12;

$character_class_minimums[BANDIT] = [];
$character_class_maximums[BANDIT] = [];
$character_class_minimums[BANDIT][CHARACTER_STRENGTH] = 12;
$character_class_minimums[BANDIT][CHARACTER_INTELLIGENCE] = 10;
$character_class_minimums[BANDIT][CHARACTER_CONSTITUTION] = 12;
$character_class_minimums[BANDIT][CHARACTER_DEXTERITY] = 12;

$character_class_minimums[DUELIST] = [];
$character_class_maximums[DUELIST] = [];
$character_class_minimums[DUELIST][CHARACTER_STRENGTH] = 9;
$character_class_minimums[DUELIST][CHARACTER_INTELLIGENCE] = 10;
$character_class_minimums[DUELIST][CHARACTER_CONSTITUTION] = 15;
$character_class_minimums[DUELIST][CHARACTER_DEXTERITY] = 9;

$character_class_minimums[ESCRIMA] = [];
$character_class_maximums[ESCRIMA] = [];
$character_class_minimums[ESCRIMA][CHARACTER_STRENGTH] = 8;
$character_class_minimums[ESCRIMA][CHARACTER_INTELLIGENCE] = 7;
$character_class_minimums[ESCRIMA][CHARACTER_WISDOM] = 15;
$character_class_minimums[ESCRIMA][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[ESCRIMA][CHARACTER_CONSTITUTION] = 10;
$character_class_minimums[ESCRIMA][CHARACTER_CHARISMA] = 5;

$character_class_minimums[NEW_MONK] = [];
$character_class_maximums[NEW_MONK] = [];
$character_class_minimums[NEW_MONK][CHARACTER_STRENGTH] = 15;
$character_class_minimums[NEW_MONK][CHARACTER_WISDOM] = 15;
$character_class_minimums[NEW_MONK][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[NEW_MONK][CHARACTER_CONSTITUTION] = 11;

$character_class_minimums[HEALER] = [];
$character_class_maximums[HEALER] = [];
$character_class_minimums[HEALER][CHARACTER_INTELLIGENCE] = 15;
$character_class_minimums[HEALER][CHARACTER_WISDOM] = 15;
$character_class_minimums[HEALER][CHARACTER_DEXTERITY] = 15;

$character_class_minimums[MERCHANT] = [];
$character_class_maximums[MERCHANT] = [];
$character_class_minimums[MERCHANT][CHARACTER_STRENGTH] = 6;
$character_class_minimums[MERCHANT][CHARACTER_INTELLIGENCE] = 10;
$character_class_minimums[MERCHANT][CHARACTER_WISDOM] = 10;
$character_class_minimums[MERCHANT][CHARACTER_DEXTERITY] = 6;
$character_class_minimums[MERCHANT][CHARACTER_CONSTITUTION] = 6;
$character_class_minimums[MERCHANT][CHARACTER_CHARISMA] = 10;
$character_class_minimums[MERCHANT][CHARACTER_COMELINESS] = 10;

$character_class_minimums[BARD] = [];
$character_class_maximums[BARD] = [];
$character_class_minimums[BARD][CHARACTER_STRENGTH] = 15;
$character_class_minimums[BARD][CHARACTER_INTELLIGENCE] = 12;
$character_class_minimums[BARD][CHARACTER_WISDOM] = 15;
$character_class_minimums[BARD][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[BARD][CHARACTER_CONSTITUTION] = 10;
$character_class_minimums[BARD][CHARACTER_CHARISMA] = 15;

$character_class_minimums[NEW_BARD] = [];
$character_class_maximums[NEW_BARD] = [];
$character_class_minimums[NEW_BARD][CHARACTER_STRENGTH] = 9;
$character_class_minimums[NEW_BARD][CHARACTER_INTELLIGENCE] = 15;
$character_class_minimums[NEW_BARD][CHARACTER_WISDOM] = 12;
$character_class_minimums[NEW_BARD][CHARACTER_DEXTERITY] = 16;
$character_class_minimums[NEW_BARD][CHARACTER_CONSTITUTION] = 6;
$character_class_minimums[NEW_BARD][CHARACTER_CHARISMA] = 15;

$character_class_minimums[ARCHER_RANGER] = [];
$character_class_maximums[ARCHER_RANGER] = [];
$character_class_minimums[ARCHER_RANGER][CHARACTER_STRENGTH] = 15;
$character_class_minimums[ARCHER_RANGER][CHARACTER_INTELLIGENCE] = 14;
$character_class_minimums[ARCHER_RANGER][CHARACTER_WISDOM] = 14;
$character_class_minimums[ARCHER_RANGER][CHARACTER_DEXTERITY] = 15;
$character_class_minimums[ARCHER_RANGER][CHARACTER_CONSTITUTION] = 14;

function getMinimumsForClass($character_class_minimums, $character_class, $character_attribute) {
    $minimums = $character_class_minimums[$character_class];
    if (empty($minimums[$character_attribute])) {
        return MINIMUM_ATTRIBUTE_VALUE;
    }

    return $minimums[$character_attribute];
}

function getMaximumsForClass($character_class_maximums, $character_class, $character_attribute) {
    $maximums = $character_class_maximums[$character_class];
    if (empty($maximums[$character_attribute])) {
        return MAXIMUM_ATTRIBUTE_VALUE;
    }

    return $maximums[$character_attribute];
}

?>