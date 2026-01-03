<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getWeaponDescription(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'weaponDescription');
}