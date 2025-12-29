<?php
require_once 'requiredParameter.php';

const CRAFT_STATUS_ARTISAN = 0;
const CRAFT_STATUS_MASTERCRAFT = 1;
const CRAFT_STATUS_MAGIC = 2;

function getCraftStatus(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'craftStatus');
}

function getCraftStatusDescription($craft_status) {
	switch($craft_status) {
		case CRAFT_STATUS_ARTISAN:
			return "Artisan";
		case CRAFT_STATUS_MASTERCRAFT:
			return "MasterCraft";
		case CRAFT_STATUS_MAGIC:
			return "Magical";
		default:
			return "UNKNOWN";
	}
}