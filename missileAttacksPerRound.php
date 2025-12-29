<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMissileAttacksPerRound(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileAttacksPerRound', OPTIONAL_STRING_PARAMETER);
}