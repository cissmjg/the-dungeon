<?php

    class RowClassManager {

        private $default_class_name = 'rmWeaponContainerDefaultBackground';
        public function getDefaultClassName() {
            return $this->default_class_name;
        }

        public function setDefaultClassName($default_class_name) {
            $this->default_class_name = $default_class_name;
        }

        private $alternate_class_name = 'rmWeaponContainerAltBackground';
        public function getAlternateClassName() {
            return $this->alternate_class_name;
        }

        public function setAlternateClassName($alternate_class_name) {
            $this->alternate_class_name = $alternate_class_name;
        }

        private $current_class_name;
        public function getClassName() {
            if ($this->current_class_name == $this->default_class_name) {
                $this->current_class_name = $this->alternate_class_name;
            } else {
                $this->current_class_name = $this->default_class_name;
            }

            return $this->current_class_name;
        }

        public function __construct() {
            $this->current_class_name = $this->alternate_class_name;
        }
    }
?>