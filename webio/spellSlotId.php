<?php
require_once __DIR__ . '/requiredParameter.php';
const SPELL_SLOT_ID = 'spellSlotId';

function getSpellSlotId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_SLOT_ID);
}