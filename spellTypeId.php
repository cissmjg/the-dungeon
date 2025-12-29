<?php
require_once 'requiredParameter.php';

function getSpellTypeId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellTypeId');
}