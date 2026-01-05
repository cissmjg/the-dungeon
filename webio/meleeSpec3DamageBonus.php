<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_SPEC3_DAMAGE_BONUS = 'meleeSpec3DamageBonus';

function getMeleeSpec3DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_SPEC3_DAMAGE_BONUS, OPTIONAL_STRING_PARAMETER);
}