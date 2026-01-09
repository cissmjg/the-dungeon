<?php

require_once './baseSlotCount.php';

class CharacterSpellInfo implements Stringable {
	
	private $class_name;
	private $player_name;
	private $character_name;
	private $spell_type_id_1;
	private $spell_type_id_2;
	private $base_slot_counts = [];
	
	function __construct($player_name, $character_name, $class_name, $spell_type_id_1, $spell_type_id_2) {
		$this->player_name = $player_name;
		$this->character_name = $character_name;
		$this->class_name = $class_name;
		$this->spell_type_id_1 = $spell_type_id_1;		
		$this->spell_type_id_2 = $spell_type_id_2;		
	}
	
	public function init(\PDO $pdo, &$error, &$log) {

		$result = $this->getBaseSpellSlotCount($pdo, $error);
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$base_slot_count = new BaseSlotCount();
			$base_slot_count->init($row);
			$spell_type_id = $base_slot_count->getSpellTypeId();
			$this->base_slot_counts[$spell_type_id] = $base_slot_count;
		}

		if ($this->spell_type_id_1 != NULL) {
			$this->checkAndAddBlank($this->spell_type_id_1, $log);
		}
		
		if ($this->spell_type_id_2 != NULL) {
			$this->checkAndAddBlank($this->spell_type_id_2, $log);
		}
	}
	
	private function checkAndAddBlank($spell_type_id, &$log) {
		if (!array_key_exists($spell_type_id, $this->base_slot_counts)) {
			$base_slot_count = new BaseSlotCount();
			$this->base_slot_counts[$spell_type_id] = $base_slot_count->addEmptyBaseSlotCountForSpellType($spell_type_id);
		}
	}
	
	private function getBaseSpellSlotCount(\PDO $pdo, &$error) {

		$sql_exec = "CALL getBaseSpellSlotCount(:playerName, :characterName, :characterClassName)";
		
		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $this->player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $this->character_name, PDO::PARAM_STR);
		$statement->bindParam(':characterClassName', $this->class_name, PDO::PARAM_STR);

		try {
			$statement->execute();
		} catch(Exception $e) {
			$error[] = "Exception in getBaseSpellSlotCount : " . $e->getMessage();
		}

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getBaseSlotCounts() {
		return $this->base_slot_counts;
	}
	
	public function addBaseSlotCountClass($spell_type_id, $base_slot_count) {
		$this->base_slot_counts[$spell_type_id] = $base_slot_count;
	}
	
	public function addEmptyBaseSlotCount($spell_type_id) {
		$base_slot_count = new BaseSlotCount();
		$base_slot_count->addEmptyBaseSlotCountForSpellType($spell_type_id);
		$this->base_slot_counts[$spell_type_id] = $base_slot_count;
	}
	
	public function getBaseSlotCountsForSpellType($spell_type_id) {
		return $this->base_slot_counts[$spell_type_id];
	}

	public static function calculateSpellInfoDiff($before_character_spell_info, $after_character_spell_info) {
		$tmp_class_name = $before_character_spell_info->getClassName();
		$tmp_player_name = $before_character_spell_info->getPlayerName();
		$tmp_character_name = $before_character_spell_info->getCharacterName();
		$tmp_spell_type_id_1 = $before_character_spell_info->getSpellType1();
		$tmp_spell_type_id_2 = $before_character_spell_info->getSpellType2();
		
		$diff_character_spell_info = new CharacterSpellInfo($tmp_player_name, $tmp_character_name, $tmp_class_name, $tmp_spell_type_id_1, $tmp_spell_type_id_2);
		foreach($after_character_spell_info->getBaseSlotCounts() AS $spell_type_id => $tmp_after_base_slot_count) {
			$tmp_before_base_slot_count = $before_character_spell_info->getBaseSlotCountsForSpellType($spell_type_id);
			$tmp_base_slot_count = BaseSlotCount::diffBaseSlotCount($tmp_before_base_slot_count, $tmp_after_base_slot_count);
			$diff_character_spell_info->addBaseSlotCountClass($spell_type_id, $tmp_base_slot_count);
		}
		
		return $diff_character_spell_info;
	}
	
	public function getPlayerName() {
		return $this->player_name;
	}
	
	public function getCharacterName() {
		return $this->character_name;
	}
	
	public function getClassName() {
		return $this->class_name;
	}
	
	public function getSpellType1() {
		return $this->spell_type_id_1;
	}
	
	public function getSpellType2() {
		return $this->spell_type_id_2;
	}
	
	public function __toString(): string {
		$output = $this->player_name . ":" . $this->character_name . ":" . $this->class_name . " ";
		foreach ($this->base_slot_counts AS $spell_type_id => $base_slot_count) {
			$output .= $spell_type_id . ":" . $base_slot_count;
		}
		
		return $output;
	}
}