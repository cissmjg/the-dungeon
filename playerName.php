<?php
require_once 'requiredParameter.php';

function getPlayerName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'playerName');
}