<?php
require_once __DIR__ . '/requiredParameter.php';
const SPELL_DURATION = 'spellDuration';

function getSpellDuration(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_DURATION);
}