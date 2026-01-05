<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MISSILE_ATTACKS_PER_ROUND = 'missileAttacksPerRound';

function getMissileAttacksPerRound(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_ATTACKS_PER_ROUND, OPTIONAL_STRING_PARAMETER);
}