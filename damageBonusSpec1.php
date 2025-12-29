<?php
require_once 'requiredParameter.php';

function getWeaponDamageBonusSpec1(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'damageBonusSpec1', $default_value);
}