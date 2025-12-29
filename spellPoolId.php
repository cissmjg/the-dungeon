<?php
require_once 'requiredParameter.php';

function getSpellPoolId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellPoolId');
}