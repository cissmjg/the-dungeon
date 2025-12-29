<?php
    class CharacterWeaponTalent implements JsonSerializable {

        private $player_weapon_id;
        private $weapon_name;
        private $weapon_speed; 
        private $weapon_damage; 
        private $weapon_range; 
        private $weapon_notes;
        private $is_preferred;

        function init($weapon) {
            $this->player_weapon_id = $weapon['player_weapon_id'];
            $this->weapon_name = $weapon['weapon_name'];
            $this->weapon_speed = $weapon['weapon_speed'];
            $this->weapon_damage = $weapon['weapon_damage'];
            $this->weapon_range = $weapon['weapon_range'];
            $this->weapon_notes = $weapon['weapon_notes'];
            $this->is_preferred = $weapon['is_preferred'];
        }

        function getPlayerWeaponId() {
            return $this->player_weapon_id;
        }

        function getWeaponName() {
            return $this->weapon_name;
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

        function getWeaponNotes() {
            return $this->weapon_notes;
        }

        function isPreferred() {
            return $this->is_preferred;
        }
     
       	// function called when encoded with json_encode
        public function jsonSerialize()
        {
            return get_object_vars($this);
        }   
    }
?>