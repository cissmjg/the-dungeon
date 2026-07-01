<?php
require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
require_once __DIR__ . '/../helper/CurlHelper.php';

require_once __DIR__ . '/../webio/characterAction.php';
require_once __DIR__ . '/../characterActionRoutes.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/displayCount.php';
require_once __DIR__ . '/../webio/sectionName.php';

$input = [];
$log = [];
$errors = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);
getDisplayCount($errors, $input);
getSectionName($errors, $input);

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];
$display_count = $input[DISPLAY_COUNT];
$section_name = $input[SECTION_NAME];

$prefix = 'display-order-';

$renderers_to_swap = extractParametersForPrefix($errors, $prefix, $display_count);
if (count($renderers_to_swap) < 2) {
    $errors[] = "Insufficient number of renderers";
    $errors[] = __FILE__;
    die(json_encode($errors));
}

$renderer_id_1 = '';
$renderer_id_2 = '';
$display_order_1 = 0;
$display_order_2 = 0;

// The key will be the display order, the value is the renderer ID
foreach ($renderers_to_swap as $display_order => $renderer_id) {
    if (strlen($renderer_id_1) == 0) {
        $display_order_1 = extractNumericDisplayOrder($display_order);
        $renderer_id_1 = $renderer_id;
    } else {
        $display_order_2 = extractNumericDisplayOrder($display_order);
        $renderer_id_2 = $renderer_id;
        break;
    }
}

updateWeaponOrderForRenderers($pdo, $player_name, $character_name, $section_name, $renderer_id_1, $display_order_1, $renderer_id_2, $display_order_2, $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

function extractNumericDisplayOrder($display_order) {
    $display_order_elements = explode("-", $display_order);

    // The numeric portion is the last element of the array
    return array_pop($display_order_elements);
}

function updateWeaponOrderForRenderers(PDO $pdo, $player_name, $character_name, $section_name, $renderer_id_1, $weapon_order_1, $renderer_id_2, $weapon_order_2, &$errors) {
        $sql_exec = "CALL updateWeaponOrderForRenderers(:playerName, :characterName, :sectionName, :rendererId1, :weaponOrder1, :rendererId2, :weaponOrder2)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        $statement->bindParam(':sectionName', $section_name, PDO::PARAM_STR);
        $statement->bindParam(':rendererId1', $renderer_id_1, PDO::PARAM_STR);
        $statement->bindParam(':weaponOrder1', $weapon_order_1, PDO::PARAM_INT);
        $statement->bindParam(':rendererId2', $renderer_id_2, PDO::PARAM_STR);
        $statement->bindParam(':weaponOrder2', $weapon_order_2, PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in updateWeaponOrderForRenderers : " . $e->getMessage();
        }
}

?>