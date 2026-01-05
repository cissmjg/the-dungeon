<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MISSILE_HIT_BONUS = 'missileHitBonus';

function getMissileHitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_HIT_BONUS, OPTIONAL_STRING_PARAMETER);
}