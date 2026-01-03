<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getSpellTypeId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellTypeId');
}