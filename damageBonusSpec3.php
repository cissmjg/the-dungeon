<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getWeaponDamageBonusSpec3(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'damageBonusSpec3', $default_value);
}