<?php
require_once 'requiredParameter.php';

function getWeaponDamage(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'weaponDamage');
}