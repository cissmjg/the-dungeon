<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getWeaponDamage(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'weaponDamage');
}