<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getSpellPoolId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellPoolId');
}