<?php

require_once __DIR__ . '/accountClassSummary.php';
const ICON_FILE_DIR = 'icons/';

class AccountCharacterSummary implements JsonSerializable, Stringable {

	private $character_name;
	private $portrait_file_location;
	private $account_class_summaries = [];

	public function init(\PDO $pdo, $player_name, $character_name) {
		$this->character_name = $character_name;

		$character_classes = $this->getCharacterClasses($pdo, $player_name, $character_name);
		foreach($character_classes AS $character_class) {
			$spell_classes = [];
			$spell_casting_classes = $this->getSpellClassesForCharacterClass($pdo, $player_name, $character_name, $character_class['class_name']);
			if ($spell_casting_classes['spellClass1'] != null) {
				$spell_classes[] = $spell_casting_classes['spellClass1'];
			}

			if ($spell_casting_classes['spellClass2'] != null) {
				$spell_classes[] = $spell_casting_classes['spellClass2'];
			}

			$account_class_summary = new AccountClassSummary($character_class['class_name'], $character_class['character_level'], $spell_classes);
			$account_class_summary->setClassIconFileLocation(ICON_FILE_DIR . $character_class['class_icon_file_location']);
			$this->account_class_summaries[] = $account_class_summary;

		}
	}

	public function getPlayerName() {
		return $this->player_name;
	}

	public function getCharacterName() {
		return $this->character_name;
	}

	public function getAccountClassSummaries() {
		return $this->account_class_summaries;
	}

	public function getCharacterPortrait() {
		return $this->portrait_file_location;
	}

	public function setCharacterPortrait($portrait_file_location) {
		$this->portrait_file_location = $portrait_file_location;
	}
		
	private function getCharacterClasses(\PDO $pdo, $player_name, $character_name) {
		$sql_exec = "CALL getCharacterClasses(:playerName, :characterName)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	private function getSpellClassesForCharacterClass(\PDO $pdo, $player_name, $character_name, $character_class_name) {
		$sql_exec = "CALL getSpellClassesForCharacterClass(:playerName, :characterName, :characterClassName)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
		$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
		$statement->execute();

		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	// function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
	
	public function __toString() {
		return $this->account_class_summaries;
	}
}