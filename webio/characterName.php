<?php
require_once __DIR__ . '/requiredParameter.php';
const CHARACTER_NAME = 'characterName';

function getCharacterName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, CHARACTER_NAME);
}

function getOptionalCharacterName(&$errors, &$input, $calling_module) {
    return getOptionalStringParameter($errors, $input, $calling_module, CHARACTER_NAME, '');
}
