<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getWeaponMediumRange(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'weaponMediumRange', $default_value);
}