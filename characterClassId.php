<?php
require_once 'requiredParameter.php';

function getCharacterClassId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'characterClassId');
}