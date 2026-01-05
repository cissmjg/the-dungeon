<?php
require_once __DIR__ . '/requiredParameter.php';
const SPELL_TYPE_ID = 'spellRTypeId';

function getSpellTypeId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_TYPE_ID);
}