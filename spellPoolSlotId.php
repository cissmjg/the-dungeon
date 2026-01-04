<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const SPELL_POOL_SLOT_ID = SPELL_POOL_SLOT_ID;

function getSpellPoolSlotId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_POOL_SLOT_ID);
}