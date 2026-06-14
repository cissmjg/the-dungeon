<?php
require_once 'spellType.php';

class AccountClassSummary implements JsonSerializable, Stringable {
	
	private $player_character_class_id;
	private $class_name;
	private $class_level;
	private $class_id;
	private $number_of_experience_points;
	private $spell_classes;
	private $class_icon_file_location;
	private $spell_type_1;
	private $spell_type_2;
	
	function __construct($class_name, $class_level, $spell_classes) {
		$this->class_name = $class_name;
		$this->class_level = $class_level;
		$this->spell_classes = $spell_classes;
	}

	public function getPlayerCharacterClassId() {
		return $this->player_character_class_id;
	}

	public function setPlayerCharacterClassId($player_character_class_id) {
		$this->player_character_class_id = $player_character_class_id;
	}
	
	public function getClassName() {
		return $this->class_name;
	}
	
	public function getClassLevel() {
		return $this->class_level;
	}

	public function getNumberOfExperiencePoints() {
		return $this->number_of_experience_points;
	}

	public function getSpellClasses() {
		return $this->spell_classes;
	}

	public function setNumberOfExperiencePoints($number_of_experience_points) {
		$this->number_of_experience_points = $number_of_experience_points;
	}

	public function getClassIconFileLocation() {
		return $this->class_icon_file_location;
	}

	public function setClassIconFileLocation($class_icon_file_location) {
		$this->class_icon_file_location = $class_icon_file_location;
	}

	public function getClassId() {
		return $this->class_id;
	}

	public function setClassId($class_id) {
		$this->class_id = $class_id;
	}

	public function getSpellcasterType1() {
		return $this->spell_type_1;
	}

	public function setSpellcasterType1(SpellType $spell_type) {
		$this->spell_type_1 = $spell_type;
	}

	public function getSpellcasterType2() {
		return $this->spell_type_2;
	}

	public function setSpellcasterType2(SpellType $spell_type) {
		$this->spell_type_2 = $spell_type;
	}

	// function called when encoded with json_encode
    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }
	
	public function __toString(): string {
		return 'Class name : ' . $this->class_name . ' Class Level ' . $this->class_level;
	}
}