<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getTextInput(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'textInput');
}