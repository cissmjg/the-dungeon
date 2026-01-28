<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MISSILE_SPEC1_HIT_BONUS = 'missileSpec1HitBonus';

function getMissileSpec1HitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC1_HIT_BONUS, OPTIONAL_STRING_PARAMETER);
}