<?php
    require_once 'characterWeapon.php';
    class CharacterWeaponTalents implements JsonSerializable {

        private $character_weapon_talents = [];

        public function init(\PDO $pdo, $player_name, $character_name, &$errors) {
            $weapon_talents = $this->initWeaponTalents($pdo, $player_name, $character_name, $errors);
            foreach($weapon_talents AS $weapon_talent) {
                $character_weapon_talent = new CharacterWeaponTalent();
                $character_weapon_talent->init($weapon_talent);
                $this->character_weapon_talents[] = $character_weapon_talent;
            }
        }
        
        private function initWeaponTalents(\PDO $pdo, $player_name, $character_name, &$errors) {
            $sql_exec = "CALL getWeaponTalentsForPlayerCharacter(:playerName, :characterName)";
            
            $statement = $pdo->prepare($sql_exec);
            $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
            $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        
            try {
                $statement->execute();
            } catch(Exception $e) {
                $errors[] = "Exception in CharacterWeaponTalents.initWeaponTalents : " . $e->getMessage();
            }
        
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getWeaponTalents() {
            return $this->character_weapon_talents;
        }

       	// function called when encoded with json_encode
        public function jsonSerialize()
        {
            return get_object_vars($this);
        }
    }
?>