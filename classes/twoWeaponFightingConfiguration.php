<?php

class TwoWeaponFightingConfiguration {
    private $two_weapon_fighting_id;
    private $player_character_weapon1_id;
    private $player_character_weapon2_id;
    private $weapon1_description;
    private $weapon1_location;
    private $weapon2_description;
    private $weapon2_location;

    public function init($two_weapon_configuration) {
        $this->two_weapon_fighting_id = $two_weapon_configuration['player_character_two_weapon_fighting_id'];
		$this->player_character_weapon1_id = $two_weapon_configuration['player_character_weapon1_id'];
		$this->player_character_weapon2_id = $two_weapon_configuration['player_character_weapon2_id'];
		$this->weapon1_description = $two_weapon_configuration['player_character_weapon1_description'];
		$this->weapon1_location = $two_weapon_configuration['player_character_weapon1_location'];
		$this->weapon2_description = $two_weapon_configuration['player_character_weapon2_description'];
		$this->weapon2_location = $two_weapon_configuration['player_character_weapon2_location'];
    }

    public function getTwoWeaponConfigurationId() {
        return $this->two_weapon_fighting_id;
    }

    public function getWeapon1Id() {
        return $this->player_character_weapon1_id;
    }

    public function getWeapon2Id() {
        return $this->player_character_weapon2_id;
    }

    public function getWeapon1Description() {
        return $this->weapon1_description;
    }

    public function getWeapon1Location() {
        return $this->weapon1_location;
    }

    public function getWeapon2Description() {
        return $this->weapon2_description;
    }

    public function getWeapon2Location() {
        return $this->weapon2_location;
    }
}
?>