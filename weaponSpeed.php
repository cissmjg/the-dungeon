<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getWeaponSpeed(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'weaponSpeed');
}