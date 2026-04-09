<?php

require_once __DIR__ . '/../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../dbio/constants/characterAttributes.php';

$character_super_stats = [];

$character_super_stats[CLERIC] = [];
$character_super_stats[CLERIC][] = CHARACTER_WISDOM;

$character_super_stats[DRUID] = [];
$character_super_stats[DRUID][] = CHARACTER_WISDOM;

$character_super_stats[FIGHTER] = [];
$character_super_stats[FIGHTER][] = CHARACTER_STRENGTH;

$character_super_stats[PALADIN] = [];
$character_super_stats[PALADIN][] = CHARACTER_STRENGTH;
$character_super_stats[PALADIN][] = CHARACTER_DEXTERITY;
$character_super_stats[PALADIN][] = CHARACTER_CONSTITUTION;

$character_super_stats[RANGER] = [];
$character_super_stats[RANGER][] = CHARACTER_STRENGTH;

$character_super_stats[MAGIC_USER] = [];
$character_super_stats[MAGIC_USER][] = CHARACTER_INTELLIGENCE;

$character_super_stats[ILLUSIONIST] = [];
$character_super_stats[ILLUSIONIST][] = CHARACTER_INTELLIGENCE;

$character_super_stats[THIEF] = [];
$character_super_stats[THIEF][] = CHARACTER_DEXTERITY;

$character_super_stats[ASSASSIN] = [];
$character_super_stats[ASSASSIN][] = CHARACTER_DEXTERITY;

$character_super_stats[MONK] = [];

$character_super_stats[CAVALIER] = [];
$character_super_stats[CAVALIER][] = CHARACTER_STRENGTH;
$character_super_stats[CAVALIER][] = CHARACTER_DEXTERITY;
$character_super_stats[CAVALIER][] = CHARACTER_CONSTITUTION;

$character_super_stats[ELVEN_CAVALIER] = [];
$character_super_stats[ELVEN_CAVALIER][] = CHARACTER_STRENGTH;
$character_super_stats[ELVEN_CAVALIER][] = CHARACTER_DEXTERITY;
$character_super_stats[ELVEN_CAVALIER][] = CHARACTER_CONSTITUTION;

$character_super_stats[BARBARIAN] = [];
$character_super_stats[BARBARIAN][] = CHARACTER_STRENGTH;

$character_super_stats[THIEF_ACROBAT] = [];
$character_super_stats[THIEF_ACROBAT][] = CHARACTER_DEXTERITY;

$character_super_stats[ORIENTAL_BARBARIAN] = [];
$character_super_stats[ORIENTAL_BARBARIAN][] = CHARACTER_STRENGTH;

$character_super_stats[BUSHI] = [];
$character_super_stats[BUSHI][] = CHARACTER_STRENGTH;

$character_super_stats[KENSAI] = [];
$character_super_stats[KENSAI][] = CHARACTER_STRENGTH;

$character_super_stats[OA_MONK] = [];

$character_super_stats[NINJA] = [];
$character_super_stats[NINJA][] = CHARACTER_DEXTERITY;

$character_super_stats[SAMURAI] = [];
$character_super_stats[SAMURAI][] = CHARACTER_STRENGTH;

$character_super_stats[SHUKENJA] = [];
$character_super_stats[SHUKENJA][] = CHARACTER_WISDOM;

$character_super_stats[SOHEI] = [];
$character_super_stats[SOHEI][] = CHARACTER_STRENGTH;

$character_super_stats[WU_JEN] = [];
$character_super_stats[WU_JEN][] = CHARACTER_INTELLIGENCE;

$character_super_stats[YAKUZA] = [];

$character_super_stats[ANTI_PALADIN] = [];
$character_super_stats[ANTI_PALADIN][] = CHARACTER_STRENGTH;

$character_super_stats[ARCHER] = [];
$character_super_stats[ARCHER][] = CHARACTER_STRENGTH;

$character_super_stats[BERSERKER] = [];
$character_super_stats[BERSERKER][] = CHARACTER_STRENGTH;

$character_super_stats[MARINER] = [];
$character_super_stats[MARINER][] = CHARACTER_STRENGTH;

$character_super_stats[PIAO_SHIH] = [];
$character_super_stats[PIAO_SHIH][] = CHARACTER_STRENGTH;

$character_super_stats[SENTINAL] = [];
$character_super_stats[SENTINAL][] = CHARACTER_STRENGTH;

$character_super_stats[SMITH] = [];
$character_super_stats[SMITH][] = CHARACTER_STRENGTH;

$character_super_stats[SUMOTORI] = [];
$character_super_stats[SMITH][] = CHARACTER_STRENGTH;

$character_super_stats[GREATER_MAGE] = [];
$character_super_stats[GREATER_MAGE][] = CHARACTER_INTELLIGENCE;

$character_super_stats[BANDIT] = [];
$character_super_stats[BANDIT][] = CHARACTER_DEXTERITY;

$character_super_stats[DUELIST] = [];
$character_super_stats[DUELIST][] = CHARACTER_DEXTERITY;

$character_super_stats[ESCRIMA] = [];
$character_super_stats[ESCRIMA][] = CHARACTER_DEXTERITY;

$character_super_stats[NEW_MONK] = [];

$character_super_stats[HEALER] = [];
$character_super_stats[HEALER][] = CHARACTER_WISDOM;

$character_super_stats[MERCHANT] = [];

$character_super_stats[BARD] = [];

$character_super_stats[NEW_BARD] = [];

$character_super_stats[ARCHER_RANGER] = [];
$character_super_stats[ARCHER_RANGER][] = CHARACTER_STRENGTH;

?>