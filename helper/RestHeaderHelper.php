<?php

declare(strict_types=1);

class RestHeaderHelper
{	
	public static function emitRestHeaders() {
		$Access_Control_Origin_Allow = "Access-Control-Allow-Origin: ";
		$Content_Type = "Content-Type: ";
		$Content_Type_Json="application/json";

		header($Access_Control_Origin_Allow . "*");
		header($Content_Type . $Content_Type_Json);
	}
}
?>