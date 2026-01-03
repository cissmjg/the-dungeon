<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getIsPreferred(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'isPreferred');
}