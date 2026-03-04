<?php

    class FormIdLookup  implements JsonSerializable {
        private $delete_form_id;
        private $delete_weapon_talent_id;
        private $add_form_id;
        private $add_skill_catalog_element_id;
        private $add_weapon_proficiency_element_id;
        private $add_weapon2_proficiency_element_id;

        public function __construct($delete_form_id, $delete_weapon_talent_id, $add_form_id, $add_skill_catalog_element_id, $add_weapon_proficiency_element_id, $add_weapon2_proficiency_element_id) {
            $this->delete_form_id = $delete_form_id;
            $this->delete_weapon_talent_id = $delete_weapon_talent_id;
            $this->add_form_id = $add_form_id;
            $this->add_skill_catalog_element_id = $add_skill_catalog_element_id;
            $this->add_weapon_proficiency_element_id = $add_weapon_proficiency_element_id;
            $this->add_weapon2_proficiency_element_id = $add_weapon2_proficiency_element_id;
        }

        // function called when encoded with json_encode
        public function jsonSerialize()
        {
            return get_object_vars($this);
        }

        public function getDeleteFormId() {
            return $this->delete_form_id;
        }

        public function getDeleteWeaponTalentId() {
            return $this->delete_weapon_talent_id;
        }

        public function getAddFormId() {
            return $this->add_form_id;
        }

        public function getAddSkillCatalogElementId() {
            return $this->add_skill_catalog_element_id;
        }

        public function getAddWeaponProficiencyElementId() {
            return $this->add_weapon_proficiency_element_id;
        }

        public function getAddWeapon2ProficiencyElementId() {
            return $this->add_weapon2_proficiency_element_id;
        }
    }


?>