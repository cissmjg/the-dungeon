<?php
require_once 'requiredParameter.php';

const PLAYER_NAME = 'playerName';

function getPlayerName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, PLAYER_NAME);
}