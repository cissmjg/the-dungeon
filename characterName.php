<?php
require_once 'requiredParameter.php';
const CHARACTER_NAME = CHARACTER_NAME;

function getCharacterName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, CHARACTER_NAME);
}