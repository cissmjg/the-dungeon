<?php

class CharacterSummary implements JsonSerializable
{
	private $strength;
	private $super_strength;
	private $intelligence;
	private $super_intelligence;
	private $wisdom;
	private $super_wisdom;
	private $dexterity;
	private $super_dexterity;
	private $constitution;
	private $super_constitution;
	private $charisma;
	private $comeliness;
	private $armor_class;
	private $hit_points;
	private $spell_points;
	private $character_classes = [];
	private $spell_classes = [];
	
	public function init(\PDO $pdo, $player_name, $character_name, &$errors) {
		
		$character_stats = $this->getCharacterSummary($pdo, $player_name, $character_name, $errors);
		if (count($errors) > 0) {
			die(json_encode($errors));
		}

		$this->strength = $character_stats['strength'];
		$this->super_strength = $character_stats['super_strength'];
		$this->intelligence = $character_stats['intelligence'];
		$this->super_intelligence = $character_stats['super_intelligence'];
		$this->wisdom = $character_stats['wisdom'];
		$this->super_wisdom = $character_stats['super_wisdom'];
		$this->dexterity = $character_stats['dexterity'];
		$this->super_dexterity = $character_stats['super_dexterity'];
		$this->constitution = $character_stats['constitution'];
		$this->super_constitution = $character_stats['super_constitution'];
		$this->charisma = $character_stats['charisma'];
		$this->comeliness = $character_stats['comeliness'];
		$this->armor_class = $character_stats['armor_class'];
		$this->hit_points = $character_stats['hit_points'];
		$this->spell_points = $character_stats['spell_points'];

		$this->character_classes = $this->populateCharacterClasses($pdo, $player_name, $character_name, $errors);
		if (count($errors) > 0) {
			die(json_encode($errors));
		}

		for($i = 0; $i < count($this->character_classes); $i++) {
			$character_class = $this->character_classes[$i];
			$spell_casting_classes = $this->getSpellClassesForCharacterClass($pdo, $player_name, $character_name, $character_class['class_name'], $errors);
			if (count($errors) > 0) {
				die(json_encode($errors));
			}

			if ($spell_casting_classes['spellClass1'] != null) {
				$this->spell_classes[] = $spell_casting_classes['spellClass1'];
			}

			if ($spell_casting_classes['spellClass2'] != null) {
				$this->spell_classes[] = $spell_casting_classes['spellClass2'];
			}
		}
	}
	
	private function getCharacterSummary(\PDO $pdo, $player_name, $character_name, &$errors) {
		$sql_exec = "CALL getCharacterSummary(:playerName, :characterName)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
		try {
			$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CharacterSummary.getCharacterSummary : " . $e->getMessage();
        }    

		return $statement->fetch(PDO::FETCH_ASSOC);
	}
		
	private function populateCharacterClasses(\PDO $pdo, $player_name, $character_name, &$errors) {
		$sql_exec = "CALL getCharacterClasses(:playerName, :characterName)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
		try {
			$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CharacterSummary.populateCharacterClasses : " . $e->getMessage();
        }    

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	private function getSpellClassesForCharacterClass(\PDO $pdo, $player_name, $character_name, $character_class_name, &$errors) {
		$sql_exec = "CALL getSpellClassesForCharacterClass(:playerName, :characterName, :characterClassName)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
		$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
		try {
			$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CharacterSummary.getSpellClassesForCharacterClass : " . $e->getMessage();
        }    

		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	// function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
	
	public function getStrength() {
		return $this->strength;
	}

	public function getSuperStrength() {
		return $this->super_strength;
	}

	public function getIntelligence() {
		return $this->intelligence;
	}

	public function getSuperIntelligence() {
		return $this->super_intelligence;
	}

	public function getWisdom() {
		return $this->wisdom;
	}

	public function getSuperWisdom() {
		return $this->super_wisdom;
	}

	public function getDexterity() {
		return $this->dexterity;
	}

	public function getSuperDexterity() {
		return $this->super_dexterity;
	}

	public function getConstitution() {
		return $this->constitution;
	}

	public function getSuperConstitution() {
		return $this->super_constitution;
	}

	public function getCharisma() {
		return $this->charisma;
	}

	public function getComeliness() {
		return $this->comeliness;
	}

	public function getArmorClass() {
		return $this->armor_class;
	}

	public function getHitPoints() {
		return $this->hit_points;
	}

	public function getSpellPoints() {
		return $this->spell_points;
	}

	public function getCharacterClasses() {
		return $this->character_classes;
	}

	public function getSpellClasses() {
		return $this->spell_classes;
	}
	
    public function formatStrength() {
        $output = $this->getStrength();
		if ($this->getSuperStrength() != null) {
			if ($this->getSuperStrength() == 100) {
				$output .= '/00';
			} else {
				$output .= '/' . sprintf("%02d", $this->getSuperStrength());
			}
		}

        return $output;
    }

    public function formatIntelligence() {
		$output = $this->getIntelligence();
		if ($this->getSuperIntelligence() != null) {
			if ($this->getSuperIntelligence() == 100) {
				$output .= '/00';
			} else {
				$output .= '/' . sprintf("%02d", $this->getSuperIntelligence());
			}
		}

        return $output;
    }
    
    public function formatWisdom() {
		$output = $this->getWisdom();
		if ($this->getSuperWisdom() != null) {
			if ($this->getSuperWisdom() == 100) {
				$output .= '/00';
			} else {
				$output .= '/' . sprintf("%02d", $this->getSuperWisdom());
			}
		}

        return  $output;
    }

    public function formatDexterity() {
		$output = $this->getDexterity();
		if ($this->getSuperDexterity() != null) {
			if ($this->getSuperDexterity() == 100) {
				$output .= '/00';
			} else {
				$output .= '/' . sprintf("%02d", $this->getSuperDexterity());
			}
		}

        return $output;
    }

	public function formatConstitution() {
		$output = $this->getConstitution();
		if ($this->getSuperConstitution() != null) {
			if ($this->getSuperConstitution() == 100) {
				$output .= '/00';
			} else {
				$output .= '/' . sprintf("%02d", $this->getSuperConstitution());
			}
		}
	}
}
