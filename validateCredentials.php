<?php

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/env.php';

require_once __DIR__ . '/webio/playerName.php';

function validateSessionCredentials(\PDO $pdo) {
    // Only validate credentials in Production
    if (ENVIRONMENT == PROD_ENVIRONMENT) {

        //If the cookie isn't set, redirect to the login page to go get one
        if(isset($_COOKIE[SESSION_COOKIE_NAME])) {
            $session_ticket = $_COOKIE[SESSION_COOKIE_NAME];
            validateSessionTicket($pdo, $session_ticket);
            return;
        }

        if(isset($_GET[SESSION_COOKIE_NAME])) {
            $session_ticket = $_GET[SESSION_COOKIE_NAME];
            validateSessionTicket($pdo, $session_ticket);
            return;
        }

        if(isset($_POST[SESSION_COOKIE_NAME])) {
            $session_ticket = $_POST[SESSION_COOKIE_NAME];
            validateSessionTicket($pdo, $session_ticket);
            return;
        }

        $cookie_dump = 'Cookies : ' . print_r($_COOKIE, true);
        $get_dump = 'Get: ' . print_r($_GET, true);
        $post_dump = 'Get: ' . print_r($_GET, true);

        error_log("No session ticket found" . PHP_EOL . $cookie_dump . PHP_EOL . $get_dump . PHP_EOL . $post_dump);
        die("No session ticket found");
    }
}

function validateSessionTicket(\PDO $pdo, $session_ticket) {
    $cred_validate_input = [];
    $cred_validate_errors = [];
    getPlayerName($cred_validate_errors, $cred_validate_input);

    $player_name = $cred_validate_input[PLAYER_NAME];
    $cred_query = getSessionTicketTimestamp($pdo, $player_name, $session_ticket, $cred_validate_errors);
    if (count($cred_validate_errors) > 0) {
        RestHeaderHelper::emitRestHeaders();
        $cred_validate_errors[] = "Session Error|";
        $cred_validate_errors[] = __FILE__ . "|";
        $cred_validate_errors[] = 'Timestamp retrieval';
        die(json_encode($cred_validate_errors));    
    }

    // If the ticket has expired, redirect to the login screen
    $current_time = time();
    $session_timestamp = $cred_query['session_timestamp'];
    if ($session_timestamp < $current_time) {
        $url_login_redirect = buildLoginRedirect();
        header($url_login_redirect);
        exit;
    }
}

function getSessionTicketTimestamp($pdo, $player_name, $session_ticket, &$errors) {
	$sql_exec = "CALL getSessionTicketTimestamp(:playerName, :sessionTicket)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':sessionTicket', $session_ticket, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getSessionTicketTimestamp : " . $e->getMessage();
	}

	return $statement->fetch(PDO::FETCH_ASSOC);
}

function buildLoginRedirect() {
	$redirect_url = CurlHelper::buildUrl('login');
	return CurlHelper::buildLocationHeader($redirect_url);
}

?>