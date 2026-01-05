<?php
require_once __DIR__ . '/requiredParameter.php';
const CHARACTER_LEVEL = 'characterLevel';

function getCharacterLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, CHARACTER_LEVEL);
}