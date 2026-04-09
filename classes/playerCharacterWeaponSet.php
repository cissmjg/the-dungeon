<?php
require_once 'playerCharacterWeapon.php';

class playerCharacterWeaponSet implements IteratorAggregate, JsonSerializable {

    /** @var PlayerCharacterWeapon[] */
    private array $playerCharacterWeaponList = [];
    public function init(\PDO $pdo, $player_name, $character_name, PlayerCharacterSkillSet $player_character_skill_set, &$errors) {
        $weapon_list = $this->getPlayerCharacterWeaponList($pdo, $player_name, $character_name, $errors);
        if(count($errors) > 0) {
            die(json_encode($errors));
        }

        foreach($weapon_list AS $weapon) {
            $player_character_weapon = new PlayerCharacterWeapon();
            $player_character_weapon->populate($weapon, $player_character_skill_set);
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

    public function jsonSerialize() {
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
}