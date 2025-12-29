<?php
require_once 'requiredParameter.php';

function getWeaponDamageBonusSpec2(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'damageBonusSpec2', $default_value);
}