<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const CHARACTER_NAME = 'characterName';

function getCharacterName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, CHARACTER_NAME);
}