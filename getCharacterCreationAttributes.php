<?php
declare(strict_types=1);
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/webio/characterName.php';
require_once 'characterAtributes.php';
require_once __DIR__ . '/webio/raceId.php';

function getCharacterAttributes(&$errors, &$input, $calling_module) {
    getOptionalRaceId($errors, $input, $calling_module);
    getGender($errors, $input, $calling_module);
    getCharacterName($errors, $input, $calling_module);
    getCharacterStrengthRaw($errors, $input, $calling_module);
    getCharacterStrength($errors, $input, $calling_module);
    getCharacterIntelligenceRaw($errors, $input, $calling_module);
    getCharacterIntelligence($errors, $input, $calling_module);
    getCharacterWisdomRaw($errors, $input, $calling_module);
    getCharacterWisdom($errors, $input, $calling_module);
    getCharacterDexterityRaw($errors, $input, $calling_module);
    getCharacterDexterity($errors, $input, $calling_module);
    getCharacterConstitutionRaw($errors, $input, $calling_module);
    getCharacterConstitution($errors, $input, $calling_module);
    getCharacterCharismaRaw($errors, $input, $calling_module);
    getCharacterCharisma($errors, $input, $calling_module);
    getCharacterComelinessRaw($errors, $input, $calling_module);
    getCharacterComeliness($errors, $input, $calling_module);
}

function getCharacterName(&$errors, &$input, $calling_module) {
    return getOptionalStringParameter($errors, $input, $calling_module, CHARACTER_NAME, '');
}

function getCharacterStrength(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_STRENGTH, 3);
}

function getCharacterStrengthRaw(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_STRENGTH_RAW, 3);
}

function getCharacterIntelligence(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_INTELLIGENCE, 3);
}

function getCharacterIntelligenceRaw(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_INTELLIGENCE_RAW, 3);
}

function getCharacterWisdom(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_WISDOM, 3);
}

function getCharacterWisdomRaw(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_WISDOM_RAW, 3);
}

function getCharacterDexterity(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_DEXTERITY, 3);
}

function getCharacterDexterityRaw(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_DEXTERITY_RAW, 3);
}

function getCharacterConstitution(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_CONSTITUTION, 3);
}

function getCharacterConstitutionRaw(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_CONSTITUTION_RAW, 3);
}

function getCharacterCharisma(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_CHARISMA, 3);
}

function getCharacterCharismaRaw(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_CHARISMA_RAW, 3);
}

function getCharacterComeliness(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_COMELINESS, 3);
}

function getCharacterComelinessRaw(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_COMELINESS_RAW, 3);
}

function getGender(&$errors, &$input, $calling_module) {
    return getOptionalStringParameter($errors, $input, $calling_module, CHARACTER_GENDER, 'M');
}

function getOptionalRaceId(&$errors, &$input, $calling_module) {
    return getOptionalIntegerParameter($errors, $input, $calling_module, CHARACTER_RACE_ID, 3);
}

?>