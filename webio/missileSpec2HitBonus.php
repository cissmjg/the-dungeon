<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_SPEC2_HIT_BONUS = 'missileSpec2HitBonus';

function getMissileSpec2HitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC2_HIT_BONUS, OPTIONAL_STRING_PARAMETER);
}