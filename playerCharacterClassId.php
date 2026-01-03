<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getPlayerCharacterClassId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'playerCharacterClassId');
}