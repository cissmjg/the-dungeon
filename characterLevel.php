<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getCharacterLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'characterLevel');
}