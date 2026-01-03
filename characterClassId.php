<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getCharacterClassId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'characterClassId');
}