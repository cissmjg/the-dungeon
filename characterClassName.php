<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getCharacterClassName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'characterClassName');
}