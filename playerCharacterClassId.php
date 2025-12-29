<?php
require_once 'requiredParameter.php';

function getPlayerCharacterClassId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'playerCharacterClassId');
}