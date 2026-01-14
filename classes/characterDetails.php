<?php

require_once __DIR__ .  '/accountClassSummary.php';
require_once __DIR__ . '/../dbio/constants/characterAttributes.php';

class CharacterDetails implements JsonSerializable
{
    private $characterName;
    private $race;
	private $characterStrength;
	private $characterSuperStrength;
	private $characterIntelligence;
	private $characterSuperIntelligence;
	private $characterWisdom;
	private $characterSuperWisdom;
	private $characterDexterity;
	private $characterSuperDexterity;
	private $characterConstitution;
	private $characterSuperConstitution;
	private $characterCharisma;
	private $characterComeliness;
	private $armorClass;
	private $hitPoints;
	private $genderIn;
	private $movement;
	private $alignment;
	private $religion;
	private $deity;
	private $hometown;
	private $hit_die;
	private $age;
	private $apparent_age;
	private $unnatural_age;
	private $social_class;
	private $height;
	private $weight;
	private $hair;
	private $eyes;
	private $siblings;
    private $character_classes = [];

	public function init(\PDO $pdo, $player_name, $character_name, &$errors) {

		$i = 0;
		$character_stats = $this->getCharacterDetails($pdo, $player_name, $character_name, $errors);
		if (count($errors) > 0) {
			return;
		}
        foreach ($character_stats AS $character_stats_class) {                    
            if ($i == 0) {
                 $this->populateBaseStats($character_stats_class);
            }
			$i++;

			$spell_classes = [];
			$spell_casting_classes = $this->getSpellClassesForCharacterClass($pdo, $player_name, $character_name, $character_stats_class['player_character_class_name'], $errors);
			if (count($errors) > 0) {
				return;
			}

			if ($spell_casting_classes['spellClass1'] != null) {
				$spell_classes[] = $spell_casting_classes['spellClass1'];
			}

			if ($spell_casting_classes['spellClass2'] != null) {
				$spell_classes[] = $spell_casting_classes['spellClass2'];
			}

            $account_class_summary = new AccountClassSummary($character_stats_class['player_character_class_name'], $character_stats_class['player_character_class_level'], $spell_classes);
            $account_class_summary->setNumberOfExperiencePoints($character_stats_class['player_character_class_experience_points']);
			$account_class_summary->setClassId($character_stats_class['character_class_Id']);
			if ($i == 1) {
				$this->character_classes[CHARACTER_PRIMARY_CLASS] = $account_class_summary;
			} else if ($i == 2) {
				$this->character_classes[CHARACTER_SECONDARY_CLASS] = $account_class_summary;
			} else if ($i == 3) {
				$this->character_classes[CHARACTER_TERTIARY_CLASS] = $account_class_summary;
			}
        }
    }
	
	private function getCharacterDetails(\PDO $pdo, $player_name, $character_name, &$errors) {
		$sql_exec = "CALL getCharacterDetails(:playerName, :characterName)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getCharacterDetails : " . $e->getMessage();
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
            $errors[] = "Exception in getSpellClassesForCharacterClass : " . $e->getMessage();
        }    

		return $statement->fetch(PDO::FETCH_ASSOC);
	}

    private function populateBaseStats($character_stats_class) {
        $this->characterName = $character_stats_class['player_character_name'];
        $this->race = $character_stats_class['player_character_race'];
		$this->characterStrength = $character_stats_class['player_character_strength'];
		$this->characterSuperStrength = $character_stats_class['player_character_super_strength'];
		$this->characterIntelligence = $character_stats_class['player_character_intelligence'];
		$this->characterSuperIntelligence = $character_stats_class['player_character_super_intelligence'];
		$this->characterWisdom = $character_stats_class['player_character_wisdom'];
		$this->characterSuperWisdom = $character_stats_class['player_character_super_wisdom'];
		$this->characterDexterity = $character_stats_class['player_character_dexterity'];
		$this->characterSuperDexterity = $character_stats_class['player_character_super_dexterity'];
		$this->characterConstitution = $character_stats_class['player_character_constitution'];
		$this->characterSuperConstitution = $character_stats_class['player_character_super_constitution'];
		$this->characterCharisma = $character_stats_class['player_character_charisma'];
		$this->characterComeliness = $character_stats_class['player_character_comeliness'];
		$this->armorClass = $character_stats_class['player_character_armor_class'];
		$this->hitPoints = $character_stats_class['player_character_hit_points'];
		$this->genderIn = $character_stats_class['player_character_gender'];
		$this->movement = $character_stats_class['player_character_movement'];
		$this->alignment = $character_stats_class['player_character_alignment'];
		$this->religion = $character_stats_class['player_character_religion'];
		$this->deity = $character_stats_class['player_character_deity'];
		$this->hometown = $character_stats_class['player_character_hometown'];
		$this->hit_die = $character_stats_class['player_character_hit_die'];
		$this->age = $character_stats_class['player_character_age'];
		$this->apparent_age = $character_stats_class['player_character_apparent_age'];
		$this->unnatural_age = $character_stats_class['player_character_unnatural_age'];
		$this->social_class = $character_stats_class['player_character_social_class'];
		$this->height = $character_stats_class['player_character_height'];
		$this->weight = $character_stats_class['player_character_weight'];
		$this->hair = $character_stats_class['player_character_hair'];
		$this->eyes = $character_stats_class['player_character_eyes'];
		$this->siblings = $character_stats_class['player_character_siblings'];
    }

	// function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
