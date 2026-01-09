<?php

class BaseSlotCount implements Stringable {
	
	private $spell_type_id;
	private $level_1_count;
	private $level_2_count;
	private $level_3_count;
	private $level_4_count;
	private $level_5_count;
	private $level_6_count;
	private $level_7_count;
	private $level_8_count;
	private $level_9_count;
	
	function __construct() {
	}
	
	function init($row) {
		$this->spell_type_id = $row['spell_type_id'];	
		$this->level_1_count = $row['number_level_1'];
		$this->level_2_count = $row['number_level_2'];
		$this->level_3_count = $row['number_level_3'];
		$this->level_4_count = $row['number_level_4'];
		$this->level_5_count = $row['number_level_5'];
		$this->level_6_count = $row['number_level_6'];
		$this->level_7_count = $row['number_level_7'];
		$this->level_8_count = $row['number_level_8'];
		$this->level_9_count = $row['number_level_9'];
	}
	
	public function addEmptyBaseSlotCountForSpellType($spell_type_id) {
		$this->spell_type_id = $spell_type_id;	
		$this->level_1_count = 0;
		$this->level_2_count = 0;
		$this->level_3_count = 0;
		$this->level_4_count = 0;
		$this->level_5_count = 0;
		$this->level_6_count = 0;
		$this->level_7_count = 0;
		$this->level_8_count = 0;
		$this->level_9_count = 0;
		
		return $this;
	}
	
	public static function diffBaseSlotCount($before_base_slot_count_class, $after_base_slot_count_class) {
		$diff_base_slot_count_class = new BaseSlotCount();
		$row = [];
		$row['spell_type_id'] = $before_base_slot_count_class->getSpellTypeId();
		$row['number_level_1'] = $after_base_slot_count_class->getBaseSlotCount(1) - $before_base_slot_count_class->getBaseSlotCount(1);
		$row['number_level_2'] = $after_base_slot_count_class->getBaseSlotCount(2) - $before_base_slot_count_class->getBaseSlotCount(2); 
		$row['number_level_3'] = $after_base_slot_count_class->getBaseSlotCount(3) - $before_base_slot_count_class->getBaseSlotCount(3);
		$row['number_level_4'] = $after_base_slot_count_class->getBaseSlotCount(4) - $before_base_slot_count_class->getBaseSlotCount(4);
		$row['number_level_5'] = $after_base_slot_count_class->getBaseSlotCount(5) - $before_base_slot_count_class->getBaseSlotCount(5);
		$row['number_level_6'] = $after_base_slot_count_class->getBaseSlotCount(6) - $before_base_slot_count_class->getBaseSlotCount(6);
		$row['number_level_7'] = $after_base_slot_count_class->getBaseSlotCount(7) - $before_base_slot_count_class->getBaseSlotCount(7);
		$row['number_level_8'] = $after_base_slot_count_class->getBaseSlotCount(8) - $before_base_slot_count_class->getBaseSlotCount(8);
		$row['number_level_9'] = $after_base_slot_count_class->getBaseSlotCount(9) - $before_base_slot_count_class->getBaseSlotCount(9);
		$diff_base_slot_count_class->init($row);
		
		return $diff_base_slot_count_class;
	}
	
	public function getSpellTypeId() {
		return $this->spell_type_id;
	}
	
	public function getBaseSlotCount($level) {
		switch($level) {
			case 1:
				return $this->level_1_count;
				break;
			case 2:
				return $this->level_2_count;
				break;
			case 3:
				return $this->level_3_count;
				break;
			case 4:
				return $this->level_4_count;
				break;
			case 5:
				return $this->level_5_count;
				break;
			case 6:
				return $this->level_6_count;
				break;
			case 7:
				return $this->level_7_count;
				break;
			case 8:
				return $this->level_8_count;
				break;
			case 9:
				return $this->level_9_count;
				break;
		}
	}
	
	 public function __toString(): string {
		 return "spell_type: " . $this->spell_type_id . ", 1st: " . $this->level_1_count . ", 2nd: " . $this->level_2_count . ", 3rd: " . $this->level_3_count . ", 4th: " . $this->level_4_count . ", 5th: " . $this->level_5_count . ", 6th: " . $this->level_6_count . ", 7th: " . $this->level_7_count . ", 8th: " . $this->level_8_count . ", 9th: " . $this->level_9_count;
	 }
}