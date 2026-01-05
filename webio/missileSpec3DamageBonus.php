<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_SPEC3_DAMAGE_BONUS = 'missileSpec3DamageBonus';

function getMissileSpec3DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC3_DAMAGE_BONUS, OPTIONAL_STRING_PARAMETER);
}