<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MISSILE_SPEC1_DAMAGE_BONUS = 'missileSpec1DamageBonus';

function getMissileSpec1DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC1_DAMAGE_BONUS, OPTIONAL_STRING_PARAMETER);
}