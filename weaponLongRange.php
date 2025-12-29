<?php
require_once 'requiredParameter.php';

function getWeaponLongRange(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'weaponLongRange', $default_value);	
}