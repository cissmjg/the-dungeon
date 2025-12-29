<?php
require_once 'requiredParameter.php';

function getSpellDuration(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellDuration');
}