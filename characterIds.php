<?php

function getCharacterIds(\PDO $pdo, string $player_name, string $character_name, string $character_class_name) {
	$sql_exec = "CALL getCharacterIds(:playerName, :characterName, :characterClassName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	$statement->execute();

	return $statement->fetch(PDO::FETCH_ASSOC);
}