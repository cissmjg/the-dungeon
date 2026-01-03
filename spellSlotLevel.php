<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getSpellSlotLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'slotLevel');
}