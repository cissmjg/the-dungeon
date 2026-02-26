<?php

class AccountClassSummary implements JsonSerializable, Stringable {
	
	private $class_name;
	private $class_level;
	private $class_id;
	private $number_of_experience_points;
	private $spell_classes;
	private $class_icon_file_location;
	
	function __construct($class_name, $class_level, $spell_classes) {
		$this->class_name = $class_name;
		$this->class_level = $class_level;
		$this->spell_classes = $spell_classes;
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

	// function called when encoded with json_encode
    public function jsonSerialize() {
        return get_object_vars($this);
    }
	
	public function __toString(): string {
		return 'Class name : ' . $this->class_name . ' Class Level ' . $this->class_level;
	}
}