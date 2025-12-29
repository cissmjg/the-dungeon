<?php
require_once 'requiredParameter.php';

function getIsPreferred(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'isPreferred');
}