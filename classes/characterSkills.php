<?php

const WEAPON_PROFICIENCY = 179;

require_once './characterSkill.php';

class CharacterSkills implements JsonSerializable {

    private $player_skills = [];

    public function init(\PDO $pdo, $player_name, $character_name, &$errors, &$log) {
        $player_skill_results = $this->getSkillsForPlayerCharacter($pdo, $player_name, $character_name, $errors);
        if (count($errors) == 0) {
            foreach($player_skill_results AS $player_skill_result) {
                $player_skill = new CharacterSkill();
                $player_skill->init($pdo, $player_skill_result, $errors);
                $this->player_skills[] = $player_skill;
            }
        }
    }

    public function getPlayerCharacterSkills() {
        return $this->player_skills;
    }

    public function getSkillsForPlayerCharacter(\PDO $pdo, $player_name, $character_name, &$errors) {
        $sql_exec = "CALL getSkillsForPlayerCharacter(:playerName, :characterName)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);

		try {
			$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getSkillsForPlayerCharacter : " . $e->getMessage();
        }    

		return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

	// function called when encoded with json_encode
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}

?>