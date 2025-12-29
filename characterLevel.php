<?php
require_once 'requiredParameter.php';

function getCharacterLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'characterLevel');
}