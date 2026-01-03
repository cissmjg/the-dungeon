<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getSpellCastingTime(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellCastingTime');
}