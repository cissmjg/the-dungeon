<?php
    class CharacterWeapon implements JsonSerializable {

        private $player_character_weapon_id;
        private $player_weapon_proficiency_id;
        private $player_character_weapon_description;
        private $weapon_is_ready;
        private $weapon_craft_status;
        private $weapon_hit_bonus;
        private $weapon_damage_bonus;
        private $weapon_speed;
        private $weapon_range;	
        private $weapon_damage;
        private $weapon_attacks_per_round;
        private $weapon_player_note_1;
        private $weapon_player_note_2;	
        private $weapon_player_note_3;

        function init($weapon) {
            $this->player_character_weapon_id = $weapon['player_character_weapon_id'];
            $this->player_weapon_proficiency_id = $weapon['player_weapon_proficiency_id'];	
            $this->player_character_weapon_description = $weapon['player_character_weapon_description'];
            $this->weapon_is_ready = $weapon['weapon_is_ready'];
            $this->weapon_craft_status = $weapon['weapon_craft_status'];	
            $this->weapon_hit_bonus = $weapon['weapon_hit_bonus'];
            $this->weapon_damage_bonus = $weapon['weapon_damage_bonus'];	
            $this->weapon_speed = $weapon['weapon_speed'];
            $this->weapon_range = $weapon['weapon_range'];
            $this->weapon_damage = $weapon['weapon_damage'];
            $this->weapon_attacks_per_round = $weapon['weapon_attacks_per_round'];
            $this->weapon_player_note_1 = $weapon['weapon_player_note_1'];
            $this->weapon_player_note_2 = $weapon['weapon_player_note_2'];
            $this->weapon_player_note_3 = $weapon['weapon_player_note_3'];
        }

        function getPlayerWeaponId() {
            return $this->player_character_weapon_id;
        }

        function getWeaponName() {
            return $this->player_character_weapon_description;
        }

        function getWeaponIsReady() {
            return $this->weapon_is_ready;
        }

        function getWeaponSpeed() {
            return $this->weapon_speed;
        }

        function getWeaponDamage() {
            return $this->weapon_damage;
        }

        function getWeaponRange() {
            return $this->weapon_range;
        }

        function getWeaponNote1() {
            return $this->weapon_player_note_1;
        }

        function getWeaponNote2() {
            return $this->weapon_player_note_2;
        }

        function getWeaponNote3() {
            return $this->weapon_player_note_3;
        }

        function isReady() {
            return $this->weapon_is_ready;
        }
     
       	// function called when encoded with json_encode
        public function jsonSerialize()
        {
            return get_object_vars($this);
        }   
    }
?>