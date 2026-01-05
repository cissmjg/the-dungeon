<?php
require_once __DIR__ . '/requiredParameter.php';
const SPELL_POOL_SLOT_ID = 'spellPoolSlotId';

function getSpellPoolSlotId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_POOL_SLOT_ID);
}