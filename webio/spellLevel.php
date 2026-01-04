<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const SPELL_LEVEL = 'spellLevel';

function getSpellLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_LEVEL);
}