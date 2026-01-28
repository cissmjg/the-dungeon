<?php
require_once __DIR__ . '/requiredParameter.php';
const SPELL_TYPE_ID = 'spellTypeId';

function getSpellTypeId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_TYPE_ID);
}