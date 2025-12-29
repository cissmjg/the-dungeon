<?php
require_once 'requiredParameter.php';

function getSpellSlotLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'slotLevel');
}