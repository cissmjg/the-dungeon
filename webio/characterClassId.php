<?php
require_once __DIR__ . '/requiredParameter.php';
const CHARACTER_CLASS_ID = 'characterClassId';

function getCharacterClassId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, CHARACTER_CLASS_ID);
}