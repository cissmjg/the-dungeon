<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getWeaponShortRange(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'weaponShortRange', $default_value);
}