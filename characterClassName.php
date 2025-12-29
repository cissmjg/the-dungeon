<?php
require_once 'requiredParameter.php';

function getCharacterClassName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'characterClassName');
}