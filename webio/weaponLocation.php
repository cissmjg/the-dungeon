<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const WEAPON_LOCATION = 'weaponLocation';

function getWeaponLocation(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, WEAPON_LOCATION, $default_value);
}