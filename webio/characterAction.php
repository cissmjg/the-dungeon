<?php
require_once __DIR__ . '/requiredParameter.php';
const CHARACTER_ACTION = 'characterAction';

function getCharacterAction(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, CHARACTER_ACTION);
}
?>