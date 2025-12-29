<?php
require_once 'requiredParameter.php';

function getIsReady(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'isReady');
}