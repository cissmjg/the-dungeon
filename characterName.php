<?php
require_once 'requiredParameter.php';

function getCharacterName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'characterName');
}