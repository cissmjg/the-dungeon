<?php
require_once 'requiredParameter.php';

function getSpellLevel(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellLevel');
}