<?php

function getCharacterStats(\PDO $pdo, string $player_name, string $character_name) {
	$sql_exec = "CALL getCharacterSummary(:playerName, :characterName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->execute();

	return $statement->fetch(PDO::FETCH_ASSOC);
}