<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const CHARACTER_LEVEL = CHARACTER_LEVEL;

function getCharacterLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, CHARACTER_LEVEL);
}