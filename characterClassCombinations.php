<?php
declare(strict_types=1);

require_once 'characterRaces.php';
require_once __DIR__ . '/dbio/constants/characterClasses.php';

$class_combinations = array();

// Drow
$class_combinations[RACE_DARK_ELF] = array();

$class_combinations[RACE_DARK_ELF][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Cleric"] = array();
$class_combinations[RACE_DARK_ELF]["Cleric"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_DARK_ELF]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Cleric"][] = "Ranger";
$class_combinations[RACE_DARK_ELF]["Cleric"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_DARK_ELF]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Cleric"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Cleric"]["Thief"] = array();
$class_combinations[RACE_DARK_ELF]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Cleric"][] = "Assassin";

$class_combinations[RACE_DARK_ELF][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Fighter"] = array();
$class_combinations[RACE_DARK_ELF]["Fighter"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_DARK_ELF]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Fighter"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_DARK_ELF]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Fighter"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Fighter"]["Thief"] = array();
$class_combinations[RACE_DARK_ELF]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Fighter"][] = "Assassin";

$class_combinations[RACE_DARK_ELF][] = "Ranger";
$class_combinations[RACE_DARK_ELF]["Ranger"] = array();
$class_combinations[RACE_DARK_ELF]["Ranger"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Ranger"][] = "Magic-User";

$class_combinations[RACE_DARK_ELF][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Magic-User"] = array();
$class_combinations[RACE_DARK_ELF]["Magic-User"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Magic-User"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Magic-User"][] = "Ranger";
$class_combinations[RACE_DARK_ELF]["Magic-User"][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Magic-User"][] = "Assassin";

$class_combinations[RACE_DARK_ELF][] = "Thief";
$class_combinations[RACE_DARK_ELF]["Thief"] = array();
$class_combinations[RACE_DARK_ELF]["Thief"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Thief"]["Cleric"] = array();
$class_combinations[RACE_DARK_ELF]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Thief"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Thief"]["Fighter"] = array();
$class_combinations[RACE_DARK_ELF]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Thief"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_DARK_ELF]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_DARK_ELF][] = "Assassin";
$class_combinations[RACE_DARK_ELF]["Assassin"] = array();
$class_combinations[RACE_DARK_ELF]["Assassin"][] = "Cleric";
$class_combinations[RACE_DARK_ELF]["Assassin"][] = "Fighter";
$class_combinations[RACE_DARK_ELF]["Assassin"][] = "Magic-User";
$class_combinations[RACE_DARK_ELF][] = "Cavalier";
$class_combinations[RACE_DARK_ELF][] = "Greater Mage";
$class_combinations[RACE_DARK_ELF][] = "Healer";
$class_combinations[RACE_DARK_ELF][] = "New Bard";

// Gray Dwarf
$class_combinations[RACE_GRAY_DWARF] = array();
$class_combinations[RACE_GRAY_DWARF][] = "Fighter";
$class_combinations[RACE_GRAY_DWARF]["Fighter"] = array();
$class_combinations[RACE_GRAY_DWARF]["Fighter"][] = "Cleric";
$class_combinations[RACE_GRAY_DWARF]["Fighter"][] = "Thief";
$class_combinations[RACE_GRAY_DWARF]["Fighter"][] = "Assassin";

$class_combinations[RACE_GRAY_DWARF][] = "Cleric";
$class_combinations[RACE_GRAY_DWARF]["Cleric"] = array();
$class_combinations[RACE_GRAY_DWARF]["Cleric"][] = "Fighter";
$class_combinations[RACE_GRAY_DWARF]["Cleric"][] = "Thief";
$class_combinations[RACE_GRAY_DWARF]["Cleric"][] = "Assassin";

$class_combinations[RACE_GRAY_DWARF][] = "Thief";
$class_combinations[RACE_GRAY_DWARF]["Thief"] = array();
$class_combinations[RACE_GRAY_DWARF]["Thief"][] = "Fighter";
$class_combinations[RACE_GRAY_DWARF]["Thief"][] = "Cleric";

$class_combinations[RACE_GRAY_DWARF][] = "Assassin";
$class_combinations[RACE_GRAY_DWARF]["Assassin"] = array();
$class_combinations[RACE_GRAY_DWARF]["Assassin"][] = "Cleric";
$class_combinations[RACE_GRAY_DWARF]["Assassin"][] = "Fighter";

$class_combinations[RACE_GRAY_DWARF][] = "Healer";
$class_combinations[RACE_GRAY_DWARF][] = "New Bard";

// Gray Elf
$class_combinations[RACE_GRAY_ELF] = array();
$class_combinations[RACE_GRAY_ELF][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Cleric"] = array();
$class_combinations[RACE_GRAY_ELF]["Cleric"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Cleric"][] = "Ranger";
$class_combinations[RACE_GRAY_ELF]["Cleric"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Cleric"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Thief"] = array();
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Cleric"][] = "Assassin";

$class_combinations[RACE_GRAY_ELF][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Fighter"] = array();
$class_combinations[RACE_GRAY_ELF]["Fighter"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Fighter"][] = "Druid";
$class_combinations[RACE_GRAY_ELF]["Fighter"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Fighter"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Thief"] = array();
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Fighter"][] = "Assassin";

$class_combinations[RACE_GRAY_ELF][] = "Ranger";
$class_combinations[RACE_GRAY_ELF]["Ranger"] = array();
$class_combinations[RACE_GRAY_ELF]["Ranger"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Ranger"][] = "Druid";
$class_combinations[RACE_GRAY_ELF]["Ranger"][] = "Magic-User";

$class_combinations[RACE_GRAY_ELF][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Magic-User"] = array();
$class_combinations[RACE_GRAY_ELF]["Magic-User"][] = "Archer";
$class_combinations[RACE_GRAY_ELF]["Magic-User"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Magic-User"][] = "Druid";
$class_combinations[RACE_GRAY_ELF]["Magic-User"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Magic-User"][] = "Ranger";
$class_combinations[RACE_GRAY_ELF]["Magic-User"][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Magic-User"][] = "Assassin";

$class_combinations[RACE_GRAY_ELF][] = "Thief";
$class_combinations[RACE_GRAY_ELF]["Thief"] = array();
$class_combinations[RACE_GRAY_ELF]["Thief"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Thief"]["Cleric"] = array();
$class_combinations[RACE_GRAY_ELF]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Thief"][] = "Druid";
$class_combinations[RACE_GRAY_ELF]["Thief"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Thief"]["Fighter"] = array();
$class_combinations[RACE_GRAY_ELF]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Thief"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_GRAY_ELF]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_GRAY_ELF][] = "Assassin";
$class_combinations[RACE_GRAY_ELF]["Assassin"] = array();
$class_combinations[RACE_GRAY_ELF]["Assassin"][] = "Cleric";
$class_combinations[RACE_GRAY_ELF]["Assassin"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Assassin"][] = "Magic-User";

$class_combinations[RACE_GRAY_ELF][] = "Druid";
$class_combinations[RACE_GRAY_ELF]["Druid"] = array();
$class_combinations[RACE_GRAY_ELF]["Druid"][] = "Fighter";
$class_combinations[RACE_GRAY_ELF]["Druid"][] = "Ranger";
$class_combinations[RACE_GRAY_ELF]["Druid"][] = "Magic-User";
$class_combinations[RACE_GRAY_ELF]["Druid"][] = "Thief";

$class_combinations[RACE_GRAY_ELF][] = "Archer";
$class_combinations[RACE_GRAY_ELF]["Archer"] = [];
$class_combinations[RACE_GRAY_ELF]["Archer"][] = "Magic-User";

$class_combinations[RACE_GRAY_ELF][] = "Cavalier";
$class_combinations[RACE_GRAY_ELF][] = "Greater Mage";
$class_combinations[RACE_GRAY_ELF][] = "Healer";
$class_combinations[RACE_GRAY_ELF][] = "New Bard";

// Half Drow
$class_combinations[RACE_HALF_DROW] = array();
$class_combinations[RACE_HALF_DROW][] = "Anti-Paladin";
$class_combinations[RACE_HALF_DROW][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Cleric"] = array();
$class_combinations[RACE_HALF_DROW]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_HALF_DROW]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Fighter"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_HALF_DROW]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Cleric"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Illusionist"] = array();
$class_combinations[RACE_HALF_DROW]["Cleric"]["Illusionist"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Illusionist"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Thief"] = array();
$class_combinations[RACE_HALF_DROW]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Thief"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Cleric"][] = "Assassin";

$class_combinations[RACE_HALF_DROW][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Fighter"] = array();
$class_combinations[RACE_HALF_DROW]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_HALF_DROW]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Cleric"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_HALF_DROW]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Fighter"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Illusionist"] = array();
$class_combinations[RACE_HALF_DROW]["Fighter"]["Illusionist"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Illusionist"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Thief"] = array();
$class_combinations[RACE_HALF_DROW]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Thief"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Fighter"][] = "Assassin";

$class_combinations[RACE_HALF_DROW][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Magic-User"] = array();
$class_combinations[RACE_HALF_DROW]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Magic-User"][] = "Assassin";

$class_combinations[RACE_HALF_DROW][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Illusionist"] = array();
$class_combinations[RACE_HALF_DROW]["Illusionist"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Cleric"] = array();
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Illusionist"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Fighter"] = array();
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Illusionist"][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Thief"] = array();
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Illusionist"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Illusionist"][] = "Assassin";

$class_combinations[RACE_HALF_DROW][] = "Thief";
$class_combinations[RACE_HALF_DROW]["Thief"] = array();
$class_combinations[RACE_HALF_DROW]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Thief"]["Cleric"] = array();
$class_combinations[RACE_HALF_DROW]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Thief"]["Cleric"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Thief"]["Fighter"] = array();
$class_combinations[RACE_HALF_DROW]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Thief"]["Fighter"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_HALF_DROW]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Thief"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Thief"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Thief"]["Illusionist"] = array();
$class_combinations[RACE_HALF_DROW]["Thief"]["Illusionist"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Thief"]["Illusionist"][] = "Fighter";

$class_combinations[RACE_HALF_DROW][] = "Assassin";
$class_combinations[RACE_HALF_DROW]["Assassin"] = array();
$class_combinations[RACE_HALF_DROW]["Assassin"][] = "Cleric";
$class_combinations[RACE_HALF_DROW]["Assassin"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Assassin"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Assassin"][] = "Illusionist";

$class_combinations[RACE_HALF_DROW][] = "Druid";
$class_combinations[RACE_HALF_DROW]["Druid"] = array();
$class_combinations[RACE_HALF_DROW]["Druid"][] = "Fighter";
$class_combinations[RACE_HALF_DROW]["Druid"][] = "Magic-User";
$class_combinations[RACE_HALF_DROW]["Druid"][] = "Illusionist";
$class_combinations[RACE_HALF_DROW]["Druid"][] = "Thief";
$class_combinations[RACE_HALF_DROW][] = "Cavalier";
$class_combinations[RACE_HALF_DROW][] = "Greater Mage";
$class_combinations[RACE_HALF_DROW][] = "Bandit";
$class_combinations[RACE_HALF_DROW][] = "Duelist";
$class_combinations[RACE_HALF_DROW][] = "Healer";
$class_combinations[RACE_HALF_DROW][] = "Merchant";
$class_combinations[RACE_HALF_DROW][] = "Bard";
$class_combinations[RACE_HALF_DROW][] = "New Bard";

// Half Elf (High)
$class_combinations[RACE_HALF_ELF] = array();
$class_combinations[RACE_HALF_ELF][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF]["Cleric"][] = "Ranger";
$class_combinations[RACE_HALF_ELF]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF]["Cleric"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_HALF_ELF][] = "Druid";
$class_combinations[RACE_HALF_ELF]["Druid"] = array();
$class_combinations[RACE_HALF_ELF]["Druid"][] = "Fighter";
$class_combinations[RACE_HALF_ELF]["Druid"][] = "Ranger";
$class_combinations[RACE_HALF_ELF]["Druid"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Druid"][] = "Thief";

$class_combinations[RACE_HALF_ELF][] = "Fighter";
$class_combinations[RACE_HALF_ELF]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Fighter"][] = "Druid";
$class_combinations[RACE_HALF_ELF]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF]["Fighter"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF]["Fighter"]["Thief"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF][] = "Ranger";
$class_combinations[RACE_HALF_ELF]["Ranger"] = array();
$class_combinations[RACE_HALF_ELF]["Ranger"][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Ranger"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Ranger"][] = "Druid";

$class_combinations[RACE_HALF_ELF][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF]["Magic-User"][] = "Archer";
$class_combinations[RACE_HALF_ELF]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF]["Magic-User"][] = "Druid";
$class_combinations[RACE_HALF_ELF]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_ELF]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF]["Magic-User"]["Thief"][] = "Fighter";

$class_combinations[RACE_HALF_ELF][] = "Thief";
$class_combinations[RACE_HALF_ELF]["Thief"] = array();
$class_combinations[RACE_HALF_ELF]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF]["Thief"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Thief"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF]["Thief"]["Cleric"] = "Fighter";
$class_combinations[RACE_HALF_ELF]["Thief"]["Cleric"] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Thief"][] = "Druid";
$class_combinations[RACE_HALF_ELF]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF]["Thief"]["Magic-User"] = "Cleric";
$class_combinations[RACE_HALF_ELF]["Thief"]["Magic-User"] = "Fighter";

$class_combinations[RACE_HALF_ELF][] = "Archer";
$class_combinations[RACE_HALF_ELF]["Archer"] = [];
$class_combinations[RACE_HALF_ELF]["Archer"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF][] = "Assassin";
$class_combinations[RACE_HALF_ELF][] = "Cavalier";
$class_combinations[RACE_HALF_ELF][] = "Greater Mage";
$class_combinations[RACE_HALF_ELF][] = "Bandit";
$class_combinations[RACE_HALF_ELF][] = "Duelist";
$class_combinations[RACE_HALF_ELF][] = "Healer";
$class_combinations[RACE_HALF_ELF][] = "Merchant";
$class_combinations[RACE_HALF_ELF][] = "Bard";
$class_combinations[RACE_HALF_ELF][] = "New Bard";

// Half Orc
$class_combinations[RACE_HALF_ORC] = array();
$class_combinations[RACE_HALF_ORC][] = "Fighter";
$class_combinations[RACE_HALF_ORC]["Fighter"] = array();
$class_combinations[RACE_HALF_ORC]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ORC]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ORC]["Fighter"][] = "Assassin";

$class_combinations[RACE_HALF_ORC][] = "Cleric";
$class_combinations[RACE_HALF_ORC]["Cleric"] = array();
$class_combinations[RACE_HALF_ORC]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ORC]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ORC]["Cleric"][] = "Assassin";

$class_combinations[RACE_HALF_ORC][] = "Thief";
$class_combinations[RACE_HALF_ORC]["Thief"] = array();
$class_combinations[RACE_HALF_ORC]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ORC]["Thief"][] = "Cleric";

$class_combinations[RACE_HALF_ORC][] = "Assassin";
$class_combinations[RACE_HALF_ORC]["Assassin"] = array();
$class_combinations[RACE_HALF_ORC]["Assassin"][] = "Cleric";
$class_combinations[RACE_HALF_ORC]["Assassin"][] = "Fighter";

$class_combinations[RACE_HALF_ORC][] = "Archer";
$class_combinations[RACE_HALF_ORC][] = "Bandit";

// Halfling
$class_combinations[RACE_HALFLING] = array();
$class_combinations[RACE_HALFLING][] = "Cleric";
$class_combinations[RACE_HALFLING]["Cleric"] = array();
$class_combinations[RACE_HALFLING]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALFLING]["Cleric"][] = "Thief";

$class_combinations[RACE_HALFLING][] = "Druid";
$class_combinations[RACE_HALFLING]["Druid"] = array();
$class_combinations[RACE_HALFLING]["Druid"][] = "Fighter";
$class_combinations[RACE_HALFLING]["Druid"][] = "Thief";

$class_combinations[RACE_HALFLING][] = "Fighter";
$class_combinations[RACE_HALFLING]["Fighter"] = array();
$class_combinations[RACE_HALFLING]["Fighter"][] = "Thief";
$class_combinations[RACE_HALFLING]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALFLING]["Fighter"][] = "Druid";

$class_combinations[RACE_HALFLING][] = "Thief";
$class_combinations[RACE_HALFLING]["Thief"] = array();
$class_combinations[RACE_HALFLING]["Thief"][] = "Cleric";
$class_combinations[RACE_HALFLING]["Thief"][] = "Fighter";
$class_combinations[RACE_HALFLING]["Thief"][] = "Druid";
$class_combinations[RACE_HALFLING][] = "Healer";
$class_combinations[RACE_HALFLING][] = "New Bard";

// High Elf
$class_combinations[RACE_HIGH_ELF] = array();
$class_combinations[RACE_HIGH_ELF][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Cleric"] = array();
$class_combinations[RACE_HIGH_ELF]["Cleric"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Cleric"][] = "Ranger";
$class_combinations[RACE_HIGH_ELF]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Cleric"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Thief"] = array();
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Cleric"][] = "Assassin";

$class_combinations[RACE_HIGH_ELF][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Fighter"] = array();
$class_combinations[RACE_HIGH_ELF]["Fighter"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Fighter"][] = "Druid";
$class_combinations[RACE_HIGH_ELF]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Fighter"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Thief"] = array();
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Fighter"][] = "Assassin";

$class_combinations[RACE_HIGH_ELF][] = "Ranger";
$class_combinations[RACE_HIGH_ELF]["Ranger"] = array();
$class_combinations[RACE_HIGH_ELF]["Ranger"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Ranger"][] = "Druid";
$class_combinations[RACE_HIGH_ELF]["Ranger"][] = "Magic-User";

$class_combinations[RACE_HIGH_ELF][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Magic-User"] = array();
$class_combinations[RACE_HIGH_ELF]["Magic-User"][] = "Archer";
$class_combinations[RACE_HIGH_ELF]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Magic-User"][] = "Druid";
$class_combinations[RACE_HIGH_ELF]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Magic-User"][] = "Ranger";
$class_combinations[RACE_HIGH_ELF]["Magic-User"][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Magic-User"][] = "Assassin";

$class_combinations[RACE_HIGH_ELF][] = "Thief";
$class_combinations[RACE_HIGH_ELF]["Thief"] = array();
$class_combinations[RACE_HIGH_ELF]["Thief"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Thief"]["Cleric"] = array();
$class_combinations[RACE_HIGH_ELF]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Thief"][] = "Druid";
$class_combinations[RACE_HIGH_ELF]["Thief"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Thief"]["Fighter"] = array();
$class_combinations[RACE_HIGH_ELF]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Thief"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_HIGH_ELF]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_HIGH_ELF][] = "Assassin";
$class_combinations[RACE_HIGH_ELF]["Assassin"] = array();
$class_combinations[RACE_HIGH_ELF]["Assassin"][] = "Cleric";
$class_combinations[RACE_HIGH_ELF]["Assassin"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Assassin"][] = "Magic-User";

$class_combinations[RACE_HIGH_ELF][] = "Druid";
$class_combinations[RACE_HIGH_ELF]["Druid"] = array();
$class_combinations[RACE_HIGH_ELF]["Druid"][] = "Fighter";
$class_combinations[RACE_HIGH_ELF]["Druid"][] = "Ranger";
$class_combinations[RACE_HIGH_ELF]["Druid"][] = "Magic-User";
$class_combinations[RACE_HIGH_ELF]["Druid"][] = "Thief";

$class_combinations[RACE_HIGH_ELF][] = "Archer";
$class_combinations[RACE_HIGH_ELF]["Archer"] = [];
$class_combinations[RACE_HIGH_ELF]["Archer"][] = "Magic-User";

$class_combinations[RACE_HIGH_ELF][] = "Cavalier";
$class_combinations[RACE_HIGH_ELF][] = "Greater Mage";
$class_combinations[RACE_HIGH_ELF][] = "Healer";
$class_combinations[RACE_HIGH_ELF][] = "New Bard";

// Hill Dwarf
$class_combinations[RACE_HILL_DWARF] = array();
$class_combinations[RACE_HILL_DWARF][] = "Fighter";
$class_combinations[RACE_HILL_DWARF]["Fighter"] = array();
$class_combinations[RACE_HILL_DWARF]["Fighter"][] = "Cleric";
$class_combinations[RACE_HILL_DWARF]["Fighter"][] = "Thief";

$class_combinations[RACE_HILL_DWARF][] = "Cleric";
$class_combinations[RACE_HILL_DWARF]["Cleric"] = array();
$class_combinations[RACE_HILL_DWARF]["Cleric"][] = "Fighter";
$class_combinations[RACE_HILL_DWARF]["Cleric"][] = "Thief";

$class_combinations[RACE_HILL_DWARF][] = "Thief";
$class_combinations[RACE_HILL_DWARF]["Thief"] = array();
$class_combinations[RACE_HILL_DWARF]["Thief"][] = "Fighter";
$class_combinations[RACE_HILL_DWARF]["Thief"][] = "Cleric";
$class_combinations[RACE_HILL_DWARF][] = "Healer";
$class_combinations[RACE_HILL_DWARF][] = "New Bard";

// Human
$class_combinations[RACE_HUMAN] = array();
$class_combinations[RACE_HUMAN][] = "Cleric";
$class_combinations[RACE_HUMAN][] = "Druid";
$class_combinations[RACE_HUMAN][] = "Fighter";
$class_combinations[RACE_HUMAN][] = "Paladin";
$class_combinations[RACE_HUMAN][] = "Ranger";
$class_combinations[RACE_HUMAN][] = "Magic-User";
$class_combinations[RACE_HUMAN][] = "Illusionist";
$class_combinations[RACE_HUMAN][] = "Thief";
$class_combinations[RACE_HUMAN][] = "Assassin";
$class_combinations[RACE_HUMAN][] = "Monk";
$class_combinations[RACE_HUMAN][] = "Cavalier";
$class_combinations[RACE_HUMAN][] = "Barbarian";
$class_combinations[RACE_HUMAN][] = "Thief-Acrobat";
$class_combinations[RACE_HUMAN][] = "Oriental Barbarian";
$class_combinations[RACE_HUMAN][] = "Bushi";
$class_combinations[RACE_HUMAN][] = "Kensai";
$class_combinations[RACE_HUMAN][] = "OA Monk";
$class_combinations[RACE_HUMAN][] = "Samurai";
$class_combinations[RACE_HUMAN][] = "Shukenja";
$class_combinations[RACE_HUMAN][] = "Sohei";
$class_combinations[RACE_HUMAN][] = "Wu Jen";
$class_combinations[RACE_HUMAN][] = "Yakuza";
$class_combinations[RACE_HUMAN][] = "Anti-Paladin";
$class_combinations[RACE_HUMAN][] = "Archer";
$class_combinations[RACE_HUMAN][] = "Berserker";
$class_combinations[RACE_HUMAN][] = "Mariner";
$class_combinations[RACE_HUMAN][] = "Piao Shih";
$class_combinations[RACE_HUMAN][] = "Sentinal";
$class_combinations[RACE_HUMAN][] = "Smith";
$class_combinations[RACE_HUMAN][] = "Sumotori";
$class_combinations[RACE_HUMAN][] = "Greater Mage";
$class_combinations[RACE_HUMAN][] = "Bandit";
$class_combinations[RACE_HUMAN][] = "Duelist";
$class_combinations[RACE_HUMAN][] = "Escrima";
$class_combinations[RACE_HUMAN][] = "New Monk";
$class_combinations[RACE_HUMAN][] = "Healer";
$class_combinations[RACE_HUMAN][] = "Merchant";
$class_combinations[RACE_HUMAN][] = "Bard";
$class_combinations[RACE_HUMAN][] = "New Bard";
$class_combinations[RACE_HUMAN][] = "Archer-Ranger";

$class_combinations[RACE_HUMAN]["Bushi"] = array();
$class_combinations[RACE_HUMAN]["Bushi"][] = "Ninja";
$class_combinations[RACE_HUMAN]["Sohei"] = array();
$class_combinations[RACE_HUMAN]["Sohei"][] ="Ninja";
$class_combinations[RACE_HUMAN]["Wu Jen"] = array();
$class_combinations[RACE_HUMAN]["Wu Jen"][] = "Ninja";
$class_combinations[RACE_HUMAN]["Yakuza"] = array();
$class_combinations[RACE_HUMAN]["Yakuza"][] = "Ninja";


// Mountain Dwarf
$class_combinations[RACE_MOUNTAIN_DWARF] = array();
$class_combinations[RACE_MOUNTAIN_DWARF][] = "Fighter";
$class_combinations[RACE_MOUNTAIN_DWARF]["Fighter"] = array();
$class_combinations[RACE_MOUNTAIN_DWARF]["Fighter"][] = "Cleric";
$class_combinations[RACE_MOUNTAIN_DWARF]["Fighter"][] = "Thief";

$class_combinations[RACE_MOUNTAIN_DWARF][] = "Cleric";
$class_combinations[RACE_MOUNTAIN_DWARF]["Cleric"] = array();
$class_combinations[RACE_MOUNTAIN_DWARF]["Cleric"][] = "Fighter";
$class_combinations[RACE_MOUNTAIN_DWARF]["Cleric"][] = "Thief";

$class_combinations[RACE_MOUNTAIN_DWARF][] = "Thief";
$class_combinations[RACE_MOUNTAIN_DWARF]["Thief"] = array();
$class_combinations[RACE_MOUNTAIN_DWARF]["Thief"][] = "Fighter";
$class_combinations[RACE_MOUNTAIN_DWARF]["Thief"][] = "Cleric";
$class_combinations[RACE_MOUNTAIN_DWARF][] = "Healer";
$class_combinations[RACE_MOUNTAIN_DWARF][] = "New Bard";

// Oompa Loompa
$class_combinations[RACE_OOMPA_LLOOMPA] = array();
$class_combinations[RACE_OOMPA_LLOOMPA][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Magic-User"][] = "Thief";

$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Fighter"][] = "Illusionist";

$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"][] = "Illusionist";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Illusionist"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Illusionist"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Illusionist"][] = "Thief";

$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Thief"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"]["Thief"][] = "Illusionist";

$class_combinations[RACE_OOMPA_LLOOMPA]["Cleric"][] = "Ranger";

$class_combinations[RACE_OOMPA_LLOOMPA][] = "Druid";
$class_combinations[RACE_OOMPA_LLOOMPA]["Druid"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Druid"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Druid"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Druid"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Druid"][] = "Illusionist";

$class_combinations[RACE_OOMPA_LLOOMPA][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"][] = "Druid";

$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Thief"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Thief"][] = "Illusionist";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Thief"][] = "Cleric";

$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Magic-User"][] = "Cleric";

$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"][] = "Illusionist";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Illusionist"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Illusionist"][] = "Cleric";

$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Fighter"]["Cleric"][] = "Illusionist";

$class_combinations[RACE_OOMPA_LLOOMPA][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"][] = "Druid";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"][] = "Ranger";

$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Fighter"][] = "Thief";

$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Cleric"][] = "Thief";

$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Magic-User"]["Thief"][] = "Cleric";

$class_combinations[RACE_OOMPA_LLOOMPA][] = "Illusionist";
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Thief"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Thief"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Thief"][] = "Cleric";

$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Cleric"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Cleric"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Cleric"][] = "Thief";

$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Fighter"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Fighter"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Illusionist"]["Fighter"][] = "Thief";

$class_combinations[RACE_OOMPA_LLOOMPA][] = "Thief";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"][] = "Druid";

$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Fighter"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Fighter"][] = "Illusionist";

$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Cleric"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Cleric"][] = "Illusionist";

$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Magic-User"][] = "Cleric";

$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"][] = "Illusionist";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Illusionist"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Illusionist"][] = "Fighter";
$class_combinations[RACE_OOMPA_LLOOMPA]["Thief"]["Illusionist"][] = "Cleric";

$class_combinations[RACE_OOMPA_LLOOMPA][] = "Ranger";
$class_combinations[RACE_OOMPA_LLOOMPA]["Ranger"] = array();
$class_combinations[RACE_OOMPA_LLOOMPA]["Ranger"][] = "Cleric";
$class_combinations[RACE_OOMPA_LLOOMPA]["Ranger"][] = "Magic-User";
$class_combinations[RACE_OOMPA_LLOOMPA]["Ranger"][] = "Illusionist";

// Surface Gnome
$class_combinations[RACE_SURFACE_GNOME] = array();
$class_combinations[RACE_SURFACE_GNOME][] = "Cleric";
$class_combinations[RACE_SURFACE_GNOME]["Cleric"] = array();
$class_combinations[RACE_SURFACE_GNOME]["Cleric"][] = "Fighter";
$class_combinations[RACE_SURFACE_GNOME]["Cleric"][] = "Illusionist";
$class_combinations[RACE_SURFACE_GNOME]["Cleric"][] = "Thief";

$class_combinations[RACE_SURFACE_GNOME][] = "Fighter";
$class_combinations[RACE_SURFACE_GNOME]["Fighter"] = array();
$class_combinations[RACE_SURFACE_GNOME]["Fighter"][] = "Cleric";
$class_combinations[RACE_SURFACE_GNOME]["Fighter"][] = "Illusionist";
$class_combinations[RACE_SURFACE_GNOME]["Fighter"][] = "Thief";

$class_combinations[RACE_SURFACE_GNOME][] = "Illusionist";
$class_combinations[RACE_SURFACE_GNOME]["Illusionist"] = array();
$class_combinations[RACE_SURFACE_GNOME]["Illusionist"][] = "Cleric";
$class_combinations[RACE_SURFACE_GNOME]["Illusionist"][] = "Fighter";
$class_combinations[RACE_SURFACE_GNOME]["Illusionist"][] = "Thief";

$class_combinations[RACE_SURFACE_GNOME][] = "Thief";
$class_combinations[RACE_SURFACE_GNOME]["Thief"] = array();
$class_combinations[RACE_SURFACE_GNOME]["Thief"][] = "Cleric";
$class_combinations[RACE_SURFACE_GNOME]["Thief"][] = "Fighter";
$class_combinations[RACE_SURFACE_GNOME]["Thief"][] = "Illusionist";

// Valley Elf
$class_combinations[RACE_VALLEY_ELF] = array();
$class_combinations[RACE_VALLEY_ELF][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Cleric"] = array();
$class_combinations[RACE_VALLEY_ELF]["Cleric"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Cleric"][] = "Ranger";
$class_combinations[RACE_VALLEY_ELF]["Cleric"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Cleric"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Thief"] = array();
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Cleric"][] = "Assassin";

$class_combinations[RACE_VALLEY_ELF][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Fighter"] = array();
$class_combinations[RACE_VALLEY_ELF]["Fighter"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Fighter"][] = "Druid";
$class_combinations[RACE_VALLEY_ELF]["Fighter"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Fighter"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Thief"] = array();
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Fighter"][] = "Assassin";

$class_combinations[RACE_VALLEY_ELF][] = "Ranger";
$class_combinations[RACE_VALLEY_ELF]["Ranger"] = array();
$class_combinations[RACE_VALLEY_ELF]["Ranger"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Ranger"][] = "Druid";
$class_combinations[RACE_VALLEY_ELF]["Ranger"][] = "Magic-User";

$class_combinations[RACE_VALLEY_ELF][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"] = array();
$class_combinations[RACE_VALLEY_ELF]["Magic-User"][] = "Archer";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"][] = "Druid";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"][] = "Ranger";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Magic-User"][] = "Assassin";

$class_combinations[RACE_VALLEY_ELF][] = "Thief";
$class_combinations[RACE_VALLEY_ELF]["Thief"] = array();
$class_combinations[RACE_VALLEY_ELF]["Thief"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Cleric"] = array();
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Thief"][] = "Druid";
$class_combinations[RACE_VALLEY_ELF]["Thief"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Fighter"] = array();
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Thief"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_VALLEY_ELF][] = "Assassin";
$class_combinations[RACE_VALLEY_ELF]["Assassin"] = array();
$class_combinations[RACE_VALLEY_ELF]["Assassin"][] = "Cleric";
$class_combinations[RACE_VALLEY_ELF]["Assassin"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Assassin"][] = "Magic-User";

$class_combinations[RACE_VALLEY_ELF][] = "Druid";
$class_combinations[RACE_VALLEY_ELF]["Druid"] = array();
$class_combinations[RACE_VALLEY_ELF]["Druid"][] = "Fighter";
$class_combinations[RACE_VALLEY_ELF]["Druid"][] = "Ranger";
$class_combinations[RACE_VALLEY_ELF]["Druid"][] = "Magic-User";
$class_combinations[RACE_VALLEY_ELF]["Druid"][] = "Thief";

$class_combinations[RACE_VALLEY_ELF][] = "Archer";
$class_combinations[RACE_VALLEY_ELF]["Archer"] = [];
$class_combinations[RACE_VALLEY_ELF]["Archer"][] = "Magic-User";

$class_combinations[RACE_VALLEY_ELF][] = "Cavalier";
$class_combinations[RACE_VALLEY_ELF][] = "Greater Mage";
$class_combinations[RACE_VALLEY_ELF][] = "Healer";
$class_combinations[RACE_VALLEY_ELF][] = "New Bard";

// Wild Elf
$class_combinations[RACE_WILD_ELF] = array();
$class_combinations[RACE_WILD_ELF][] = "Fighter";
$class_combinations[RACE_WILD_ELF]["Fighter"] = array();
$class_combinations[RACE_WILD_ELF]["Fighter"][] = "Thief";

$class_combinations[RACE_WILD_ELF][] = "Thief";
$class_combinations[RACE_WILD_ELF]["Thief"] = array();
$class_combinations[RACE_WILD_ELF]["Thief"][] = "Fighter";

// Wood Elf
$class_combinations[RACE_WOOD_ELF] = array();
$class_combinations[RACE_WOOD_ELF][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Cleric"] = array();
$class_combinations[RACE_WOOD_ELF]["Cleric"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Cleric"][] = "Ranger";
$class_combinations[RACE_WOOD_ELF]["Cleric"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Cleric"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Thief"] = array();
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Cleric"][] = "Assassin";

$class_combinations[RACE_WOOD_ELF][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Fighter"] = array();
$class_combinations[RACE_WOOD_ELF]["Fighter"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Fighter"][] = "Druid";
$class_combinations[RACE_WOOD_ELF]["Fighter"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Fighter"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Thief"] = array();
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Fighter"][] = "Assassin";

$class_combinations[RACE_WOOD_ELF][] = "Ranger";
$class_combinations[RACE_WOOD_ELF]["Ranger"] = array();
$class_combinations[RACE_WOOD_ELF]["Ranger"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Ranger"][] = "Druid";
$class_combinations[RACE_WOOD_ELF]["Ranger"][] = "Magic-User";

$class_combinations[RACE_WOOD_ELF][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Magic-User"] = array();
$class_combinations[RACE_WOOD_ELF]["Magic-User"][] = "Archer";
$class_combinations[RACE_WOOD_ELF]["Magic-User"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Magic-User"][] = "Druid";
$class_combinations[RACE_WOOD_ELF]["Magic-User"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Magic-User"][] = "Ranger";
$class_combinations[RACE_WOOD_ELF]["Magic-User"][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Magic-User"][] = "Assassin";

$class_combinations[RACE_WOOD_ELF][] = "Thief";
$class_combinations[RACE_WOOD_ELF]["Thief"] = array();
$class_combinations[RACE_WOOD_ELF]["Thief"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Thief"]["Cleric"] = array();
$class_combinations[RACE_WOOD_ELF]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Thief"][] = "Druid";
$class_combinations[RACE_WOOD_ELF]["Thief"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Thief"]["Fighter"] = array();
$class_combinations[RACE_WOOD_ELF]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Thief"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_WOOD_ELF]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_WOOD_ELF][] = "Assassin";
$class_combinations[RACE_WOOD_ELF]["Assassin"] = array();
$class_combinations[RACE_WOOD_ELF]["Assassin"][] = "Cleric";
$class_combinations[RACE_WOOD_ELF]["Assassin"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Assassin"][] = "Magic-User";

$class_combinations[RACE_WOOD_ELF][] = "Druid";
$class_combinations[RACE_WOOD_ELF]["Druid"] = array();
$class_combinations[RACE_WOOD_ELF]["Druid"][] = "Fighter";
$class_combinations[RACE_WOOD_ELF]["Druid"][] = "Ranger";
$class_combinations[RACE_WOOD_ELF]["Druid"][] = "Magic-User";
$class_combinations[RACE_WOOD_ELF]["Druid"][] = "Thief";

$class_combinations[RACE_WOOD_ELF][] = "Archer";
$class_combinations[RACE_WOOD_ELF]["Archer"] = [];
$class_combinations[RACE_WOOD_ELF]["Archer"][] = "Magic-User";

$class_combinations[RACE_WOOD_ELF][] = "Cavalier";
$class_combinations[RACE_WOOD_ELF][] = "Greater Mage";
$class_combinations[RACE_WOOD_ELF][] = "Healer";
$class_combinations[RACE_WOOD_ELF][] = "New Bard";

// Gray Elf (Half)
$class_combinations[RACE_HALF_ELF_GRAY] = array();
$class_combinations[RACE_HALF_ELF_GRAY][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Cleric"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"][] = "Druid";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Fighter"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Ranger";
$class_combinations[RACE_HALF_ELF_GRAY]["Ranger"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Ranger"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Ranger"][] = "Druid";
$class_combinations[RACE_HALF_ELF_GRAY]["Ranger"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"][] = "Archer";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"][] = "Druid";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Magic-User"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Thief";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"][] = "Druid";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Assassin";
$class_combinations[RACE_HALF_ELF_GRAY]["Assassin"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Assassin"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_GRAY]["Assassin"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Assassin"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Druid";
$class_combinations[RACE_HALF_ELF_GRAY]["Druid"] = array();
$class_combinations[RACE_HALF_ELF_GRAY]["Druid"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_GRAY]["Druid"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_GRAY]["Druid"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_GRAY]["Druid"][] = "Thief";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Archer";
$class_combinations[RACE_HALF_ELF_GRAY]["Archer"] = [];
$class_combinations[RACE_HALF_ELF_GRAY]["Archer"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_GRAY][] = "Cavalier";
$class_combinations[RACE_HALF_ELF_GRAY][] = "Greater Mage";
$class_combinations[RACE_HALF_ELF_GRAY][] = "Healer";
$class_combinations[RACE_HALF_ELF_GRAY][] = "New Bard";
$class_combinations[RACE_HALF_ELF_GRAY][] = "Archer-Ranger";


// Valley Elf (Half)
$class_combinations[RACE_HALF_ELF_VALLEY] = array();
$class_combinations[RACE_HALF_ELF_VALLEY][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Cleric"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"][] = "Druid";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Fighter"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Ranger";
$class_combinations[RACE_HALF_ELF_VALLEY]["Ranger"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Ranger"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Ranger"][] = "Druid";
$class_combinations[RACE_HALF_ELF_VALLEY]["Ranger"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"][] = "Archer";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"][] = "Druid";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Magic-User"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Thief";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"][] = "Druid";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Assassin";
$class_combinations[RACE_HALF_ELF_VALLEY]["Assassin"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Assassin"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_VALLEY]["Assassin"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Assassin"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Druid";
$class_combinations[RACE_HALF_ELF_VALLEY]["Druid"] = array();
$class_combinations[RACE_HALF_ELF_VALLEY]["Druid"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_VALLEY]["Druid"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_VALLEY]["Druid"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_VALLEY]["Druid"][] = "Thief";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Archer";
$class_combinations[RACE_HALF_ELF_VALLEY]["Archer"] = [];
$class_combinations[RACE_HALF_ELF_VALLEY]["Archer"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_VALLEY][] = "Cavalier";
$class_combinations[RACE_HALF_ELF_VALLEY][] = "Greater Mage";
$class_combinations[RACE_HALF_ELF_VALLEY][] = "Healer";
$class_combinations[RACE_HALF_ELF_VALLEY][] = "New Bard";
$class_combinations[RACE_HALF_ELF_VALLEY][] = "Archer-Ranger";

// Wild Elf (Half)
$class_combinations[RACE_HALF_ELF_WILD] = array();
$class_combinations[RACE_HALF_ELF_WILD][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WILD]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_WILD]["Fighter"][] = "Thief";

$class_combinations[RACE_HALF_ELF_WILD][] = "Thief";
$class_combinations[RACE_HALF_ELF_WILD]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_WILD]["Thief"][] = "Fighter";

// Wood Elf (Half)
$class_combinations[RACE_HALF_ELF_WOOD] = array();
$class_combinations[RACE_HALF_ELF_WOOD][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Cleric"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"][] = "Druid";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Fighter"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Ranger";
$class_combinations[RACE_HALF_ELF_WOOD]["Ranger"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Ranger"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Ranger"][] = "Druid";
$class_combinations[RACE_HALF_ELF_WOOD]["Ranger"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"][] = "Archer";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Cleric"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"][] = "Druid";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Fighter"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Magic-User"][] = "Assassin";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Thief";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Cleric"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Cleric"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Cleric"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"][] = "Druid";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Fighter"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Fighter"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Fighter"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Magic-User"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Magic-User"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Thief"]["Magic-User"][] = "Fighter";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Assassin";
$class_combinations[RACE_HALF_ELF_WOOD]["Assassin"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Assassin"][] = "Cleric";
$class_combinations[RACE_HALF_ELF_WOOD]["Assassin"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Assassin"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Druid";
$class_combinations[RACE_HALF_ELF_WOOD]["Druid"] = array();
$class_combinations[RACE_HALF_ELF_WOOD]["Druid"][] = "Fighter";
$class_combinations[RACE_HALF_ELF_WOOD]["Druid"][] = "Ranger";
$class_combinations[RACE_HALF_ELF_WOOD]["Druid"][] = "Magic-User";
$class_combinations[RACE_HALF_ELF_WOOD]["Druid"][] = "Thief";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Archer";
$class_combinations[RACE_HALF_ELF_WOOD]["Archer"] = [];
$class_combinations[RACE_HALF_ELF_WOOD]["Archer"][] = "Magic-User";

$class_combinations[RACE_HALF_ELF_WOOD][] = "Cavalier";
$class_combinations[RACE_HALF_ELF_WOOD][] = "Greater Mage";
$class_combinations[RACE_HALF_ELF_WOOD][] = "Healer";
$class_combinations[RACE_HALF_ELF_WOOD][] = "New Bard";
$class_combinations[RACE_HALF_ELF_WOOD][] = "Archer-Ranger";

?>