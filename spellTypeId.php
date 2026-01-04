<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const SPELL_TYPE_ID = SPELL_TYPE_ID;

function getSpellTypeId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_TYPE_ID);
}