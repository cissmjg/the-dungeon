<?php
require_once __DIR__ . '/requiredParameter.php';
const TEXT_INPUT = 'textInput';

function getTextInput(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, TEXT_INPUT);
}