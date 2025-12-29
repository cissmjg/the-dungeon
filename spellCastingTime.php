<?php
require_once 'requiredParameter.php';

function getSpellCastingTime(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellCastingTime');
}