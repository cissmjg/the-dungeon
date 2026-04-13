<?php
    require_once 'playerCharacterSkill.php';

    class PlayerCharacterSkillSet implements JsonSerializable {

        private $player_character_skills = [];

        private $skill_catalog_ids = [];

        public function init(\PDO $pdo, $player_name, $character_name, $errors) {
            $player_character_skills_db = $this->getSkillsForPlayerCharacter($pdo, $player_name, $character_name, $errors);
            if (count($errors) > 0) {
                die(json_encode($errors));
            }

            foreach($player_character_skills_db AS $player_character_skill_db) {
                $player_character_skill = new PlayerCharacterSkill();
                $player_character_skill->init($player_character_skill_db);
                $skill_id = $player_character_skill_db['skill_catalog_id'];
                $this->player_character_skills[] = $player_character_skill;
                $this->skill_catalog_ids[] = $skill_id;
            }
        }

        private function getSkillsForPlayerCharacter(\PDO $pdo, $player_name, $character_name, &$errors) {
            $sql_exec = "CALL getSkillsForPlayerCharacter(:playerName, :characterName)";
            
            $statement = $pdo->prepare($sql_exec);
            $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
            $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        
            try {
                $statement->execute();
            } catch(Exception $e) {
                $errors[] = "Exception in PlayerCharacterSkillSet.getSkillsForPlayerCharacter : " . $e->getMessage();
            }
        
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public function jsonSerialize() {
            return get_object_vars($this);
        }

        public function getPlayerCharacterSkillIds() {
            return $this->skill_catalog_ids;
        }

        public function getAllSkillInstances($skill_id) {
            $specific_skill_list = [];
            foreach($this->player_character_skills AS $player_character_skill) {
                if ($player_character_skill->getSkillCatalogId() == $skill_id) {
                    $specific_skill_list[] = $player_character_skill;
                }
            }

            return $specific_skill_list;
        }

        public function getAllSkillInstancesForWeapon($skill_id, $weapon_id) {
            $specific_skill_list = [];
            foreach($this->player_character_skills AS $player_character_skill) {
                if ($player_character_skill->getSkillCatalogId() == $skill_id && $player_character_skill->getWeaponProficiencyId() == $weapon_id) {
                    $specific_skill_list[] = $player_character_skill;
                }
            }

            return $specific_skill_list;
        }
    }
?>