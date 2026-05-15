<?php

require_once 'twoWeaponFightingConfiguration.php';

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

    public function fromJSON($two_weapon_fighting_configuration_set_json) {
        $two_weapon_config_list = $two_weapon_fighting_configuration_set_json->twoWeaponFightingConfigurationList;
        for ($i = 0; $i < count($two_weapon_config_list); $i++) {
            $two_weapon_fighting_configuration_json = $two_weapon_config_list[$i];

            $two_weapon_configuration = [];
            $two_weapon_configuration['player_character_two_weapon_fighting_id'] = $two_weapon_fighting_configuration_json->two_weapon_fighting_id;
            $two_weapon_configuration['player_character_weapon1_id'] = $two_weapon_fighting_configuration_json->player_character_weapon1_id;
            $two_weapon_configuration['player_character_weapon2_id'] = $two_weapon_fighting_configuration_json->player_character_weapon2_id;
            $two_weapon_configuration['player_character_weapon1_description'] = $two_weapon_fighting_configuration_json->weapon1_description;
            $two_weapon_configuration['player_character_weapon1_location'] = $two_weapon_fighting_configuration_json->weapon1_location;
            $two_weapon_configuration['player_character_weapon2_description'] = $two_weapon_fighting_configuration_json->weapon2_description;
            $two_weapon_configuration['player_character_weapon2_location'] = $two_weapon_fighting_configuration_json->weapon2_location;

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