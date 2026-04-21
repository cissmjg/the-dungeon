<?php
require_once 'playerCharacterWeapon.php';
require_once 'playerCharacterSkillSet.php';

class playerCharacterWeaponSet implements IteratorAggregate, JsonSerializable {

    /** @var PlayerCharacterWeapon[] */
    private array $playerCharacterWeaponList = [];
    public function init(\PDO $pdo, $player_name, $character_name, PlayerCharacterSkillSet $player_character_skill_set, &$errors) {
        $weapon_list = $this->getPlayerCharacterWeaponList($pdo, $player_name, $character_name, $errors);
        if(count($errors) > 0) {
            die(json_encode($errors));
        }

        foreach($weapon_list AS $weapon) {
            $player_character_weapon = null;
            $existing_weapon = $this->getWeaponById($weapon['player_character_weapon_id']);
            if (empty($existing_weapon)) {
                $player_character_weapon = new PlayerCharacterWeapon();
                $this->add($player_character_weapon);
            } else {
                $player_character_weapon = $existing_weapon;
            }

            $player_character_weapon->populate($weapon, $player_character_skill_set);
        }
    }

    public function fromJSON($player_character_weapon_set_json, PlayerCharacterSkillSet $player_character_skill_set) {
        for ($i = 0; $i < count($player_character_weapon_set_json); $i++) {
            $player_character_weapon_json = $player_character_weapon_set_json[$i];

            $player_character_weapon = null;
            $existing_weapon = $this->getWeaponById($player_character_weapon_json->weaponId);
            if (empty($existing_weapon)) {
                $player_character_weapon = new PlayerCharacterWeapon();
            } else {
                $player_character_weapon = $existing_weapon;
            }

            $player_character_weapon->fromJSON($player_character_weapon_json, $player_character_skill_set);
            $this->add($player_character_weapon);
        }
    }

    private function getPlayerCharacterWeaponList(\PDO $pdo, $player_name, $character_name, &$errors) {
        $sql_exec = "CALL getPlayerCharacterWeaponList(:playerName, :characterName)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getPlayerCharacterWeaponList : " . $e->getMessage();
        }

		return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public function add(PlayerCharacterWeapon $playerCharacterWeapon): void {
        $this->playerCharacterWeaponList[] = $playerCharacterWeapon;
    }

    /** @return PlayerCharacterWeapon[] */
    public function getAll(): array {
        return $this->playerCharacterWeaponList;
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->playerCharacterWeaponList);
    }

    private function getWeaponById($weapon_id) {
        foreach($this->playerCharacterWeaponList AS $player_character_weapon) {
            if ($player_character_weapon->getWeaponId() == $weapon_id) {
                return $player_character_weapon;
            }
        }

        return null;
    }
}