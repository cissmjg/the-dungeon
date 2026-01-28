<?php
require_once __DIR__ . '/requiredParameter.php';
const CHARACTER_CLASS_NAME = 'characterClassName';

function getCharacterClassName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, CHARACTER_CLASS_NAME);
}