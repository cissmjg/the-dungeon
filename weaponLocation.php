<?php
require_once 'requiredParameter.php';

function getWeaponLocation(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'weaponLocation', $default_value);
}