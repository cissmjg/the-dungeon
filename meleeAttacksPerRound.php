<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeAttacksPerRound(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeAttacksPerRound', OPTIONAL_STRING_PARAMETER);
}