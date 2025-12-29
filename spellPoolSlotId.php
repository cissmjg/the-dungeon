<?php
require_once 'requiredParameter.php';

function getSpellPoolSlotId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellPoolSlotId');
}