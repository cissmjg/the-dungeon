<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const WEAPON_DESCRIPTION = 'weaponDescription';

function getWeaponDescription(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, WEAPON_DESCRIPTION);
}