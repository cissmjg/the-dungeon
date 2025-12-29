<?php
require_once 'requiredParameter.php';

function getSpellSlotId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellSlotId');
}