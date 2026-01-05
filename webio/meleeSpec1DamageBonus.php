<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_SPEC1_DAMAGE_BONUS = 'meleeSpec1DamageBonus';

function getMeleeSpec1DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_SPEC1_DAMAGE_BONUS, OPTIONAL_STRING_PARAMETER);
}