<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_DAMAGE_BONUS = 'missileDamageBonus';

function getMissileDamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_DAMAGE_BONUS, OPTIONAL_STRING_PARAMETER);
}