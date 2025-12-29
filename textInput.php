<?php
require_once 'requiredParameter.php';

function getTextInput(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'textInput');
}