<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getSpellSlotId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellSlotId');
}