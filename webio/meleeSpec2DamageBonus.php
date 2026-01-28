<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MELEE_SPEC2_DAMAGE_BONUS = 'meleeSpec2DamageBonus';

function getMeleeSpec2DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_SPEC2_DAMAGE_BONUS, OPTIONAL_STRING_PARAMETER);
}