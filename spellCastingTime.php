<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const SPELL_CASTING_TIME = 'spellCastingTime';

function getSpellCastingTime(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_CASTING_TIME);
}