<?php

class TwoWeaponFightingConfigurationSet implements IteratorAggregate, JsonSerializable {
    /** @var TwoWeaponFightingConfiguration[] */
    private array $twoWeaponFightingConfigurationList = [];

    public function init(\PDO $pdo, $player_name, $character_name, &$errors) {
        $two_weapon_fighting_configuration_list =  $this->getPlayerCharacterTwoWeaponConfigurations($pdo, $player_name, $character_name, $errors);
        if (count($errors) > 0) {
            die(json_encode($errors));
        }

        foreach($two_weapon_fighting_configuration_list AS $two_weapon_configuration) {
            $two_weapon_fighting_configuration = new TwoWeaponFightingConfiguration();
            $two_weapon_fighting_configuration->init($two_weapon_configuration);
            $this->add($two_weapon_fighting_configuration);
        }
    }

    private function getPlayerCharacterTwoWeaponConfigurations(\PDO $pdo, $player_name, $character_name, &$errors) {
        $sql_exec = "CALL getPlayerCharacterTwoWeaponConfigurations(:playerName, :characterName)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);

        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getPlayerCharacterTwoWeaponConfigurations : " . $e->getMessage();
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->twoWeaponFightingConfigurationList);
    }

    /** @return TwoWeaponFightingConfiguration[] */

    public function getAll(): array {
        return $this->twoWeaponFightingConfigurationList;
    }
    
    public function add(TwoWeaponFightingConfiguration $twoWeaponFightingConfiguration): void {
        $this->twoWeaponFightingConfigurationList[] = $twoWeaponFightingConfiguration;
    }
}