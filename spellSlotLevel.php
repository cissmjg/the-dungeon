<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const SPELL_SLOT_LEVEL = 'spellSlotLevel';

function getSpellSlotLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_SLOT_LEVEL);
}