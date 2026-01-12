<?php
declare(strict_types=1);
require_once __DIR__ . '/dbio/constants/characterRaces.php';
require_once 'characterAttributes.php';

function adjustCharacterAttributes(&$errors, &$input, $calling_module) {
    $race = $input[CHARACTER_RACE_ID];
    switch ($race) {
        case RACE_DARK_ELF:
            adjustForElf($input, $errors);
            break;
        case RACE_GRAY_DWARF:
            noAttributeAdjustments($input, $errors);
            break;
        case RACE_GRAY_ELF:
            adjustForGrayElf($input, $errors);
            break;
        case RACE_HALF_DROW:
            adjustForElf($input, $errors);
            break;
        case RACE_HALF_ELF:
            noAttributeAdjustments($input, $errors);
            break;
        case RACE_HALF_ORC:
            adjustForHalfOrc($input, $errors);
            break;
        case RACE_HALFLING:
            adjustForHalfling($input, $errors);
            break;
        case RACE_HIGH_ELF:
            adjustForElf($input, $errors);
            break;
        case RACE_HILL_DWARF:
            adjustForDwarf($input, $errors);
            break;
        case RACE_HUMAN:
            noAttributeAdjustments($input, $errors);
            break;
        case RACE_MOUNTAIN_DWARF:
            adjustForDwarf($input, $errors);
            break;
        case RACE_OOMPA_LLOOMPA:
            adjustForOompaLoompa($input, $errors);
            break;
        case RACE_SURFACE_GNOME:
            noAttributeAdjustments($input, $errors);
            break;
        case RACE_VALLEY_ELF:
            adjustForGrayElf($input, $errors);
            break;
        case RACE_WILD_ELF:
            adjustForWildElf($input, $errors);
            break;
        case RACE_WOOD_ELF:
            adjustForWoodElf($input, $errors);
            break;
        case RACE_HALF_ELF_GRAY:
            noAttributeAdjustments($input, $errors);
            break;
        case RACE_HALF_ELF_VALLEY:
            noAttributeAdjustments($input, $errors);
            break;
        case RACE_HALF_ELF_WILD:
            noAttributeAdjustments($input, $errors);
            break;
        case RACE_HALF_ELF_WOOD:
            noAttributeAdjustments($input, $errors);
            break;        
    }
}

function adjustForElf(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW];
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW];
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW] + 1;
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW] - 1;
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW];
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}

function adjustForGrayElf(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW];
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW] + 1;
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW];
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW] - 1;
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW];
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}

function adjustForHalfOrc(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW] + 1;
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW];
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW];
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW] + 1;
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW];
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW] - 2;
}

function adjustForHalfling(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW] - 1;
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW];
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW] + 1;
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW];
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW];
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}

function adjustForDwarf(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW];
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW];
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW];
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW] + 1;
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW] - 1;
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}

function adjustForOompaLoompa(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW];
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW] - 1;
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW] + 1;
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW];
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW];
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW] + 1;
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}

function adjustForWoodElf(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW] + 1;
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW] - 1;
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW];
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW];
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW];
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}

function adjustForWildElf(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW] + 2;
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW];
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW];
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW];
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW];
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}

function noAttributeAdjustments(&$input, &$errors) {
    $input[CHARACTER_STRENGTH] = $input[CHARACTER_STRENGTH_RAW];
    $input[CHARACTER_INTELLIGENCE] = $input[CHARACTER_INTELLIGENCE_RAW];
    $input[CHARACTER_WISDOM] = $input[CHARACTER_WISDOM_RAW];
    $input[CHARACTER_DEXTERITY] = $input[CHARACTER_DEXTERITY_RAW];
    $input[CHARACTER_CONSTITUTION] = $input[CHARACTER_CONSTITUTION_RAW];
    $input[CHARACTER_CHARISMA] = $input[CHARACTER_CHARISMA_RAW];
    $input[CHARACTER_COMELINESS] = $input[CHARACTER_COMELINESS_RAW];
}
?>