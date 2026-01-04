<?php
require_once __DIR__ . '/env.php';

$errors = [];
$input = [];

$pdo = require_once __DIR__ . '/dbio/DBConnection.php';
require_once __dir__ . '/webio/characterClassId.php';
require_once 'characterClasses.php';

$all_classes = getAllCharacterClasses($pdo);

$character_class = '';
$weapons = [];
if (!empty($_GET[CHARACTER_CLASS_ID])) {
    getCharacterClassId($errors, $input);    
} else {
	$input[CHARACTER_CLASS_ID] = CLERIC;
}

$weapons = getWeaponsByClass($pdo, $input[CHARACTER_CLASS_ID], $errors);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weapons By Class</title>
	<link rel="stylesheet" href="dnd-default.css">
	<script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
</head>
<body>
<h3>Weapons by Character Class</h3>
<form action="<?php STARTING_URL . htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
	<label for="characterClassId">Class</label>
	<select name=CHARACTER_CLASS_ID id=CHARACTER_CLASS_ID>
		<?php
			foreach($all_classes AS $character_class) {
				$selected = $character_class['character_class_id'] == $input[CHARACTER_CLASS_ID] ? ' selected' : '';
				echo '<option value="' . $character_class['character_class_id'] . '"' . $selected . '>' . $character_class['character_class_name'] . '</option>'; 
			}
		?>
	</select>
	<button type="submit">Go</button> 
</form>
<?php if(count($weapons) > 0): ?>	
	<table>
		<tr><td>Weapon Type</td><td>Weapon</td><td>Range</td></tr>
	<?php
		$prev_type_name = '';
		$weapon_type_name = '';
		foreach($weapons AS $weapon) {
			$range_text = $weapon['weapon_range'] == NULL ? '&nbsp;' : $weapon['weapon_range'];
			$weapon_type_name = $weapon['weapon_subtype'];
			if ($prev_type_name == $weapon_type_name) {
				$weapon_type_name = '&nbsp;';
			} else {
				$prev_type_name = $weapon_type_name;
			}
			
			echo '<tr><td>' . $weapon_type_name . '</td><td>' . $weapon['weapon'] . '</td><td>' . $range_text . '</td></tr>' . PHP_EOL;
		}
		?>
	</table>
<?php endif ?>
</body>
</html>

<?php
function getAllCharacterClasses(\PDO $pdo) {
	$sql_exec = "CALL getAllCharacterClasses()";
	
	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".getAllCharacterClasses : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);

}

function getWeaponsByClass(\PDO $pdo, $character_class_id, &$errors) {
	$sql_exec = "CALL getWeaponsByClass(:characterClassId)";

	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->bindParam(':characterClassId', $character_class_id, PDO::PARAM_INT);
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".getAllCharacterClasses : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>