<?php
require_once 'requiredParameter.php';

function getWeaponMediumRange(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'weaponMediumRange', $default_value);
}