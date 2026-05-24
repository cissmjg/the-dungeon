<?php

class PlayerCharacterPoolSpell implements JsonSerializable {

    private $spell_type_name;
    private $spell_pool_id;
    private $spell_catalog_id;
    private $spell_name;
    private $spell_link;
    private $spell_level;
    private $character_class_name;

    public function init($pool_spell) {
        $this->spell_type_name = $pool_spell['spell_type_name'];
        $this->spell_pool_id = $pool_spell['spell_pool_id'];
        $this->spell_catalog_id = $pool_spell['spell_catalog_id'];
        $this->spell_name = $pool_spell['spell_name'];
        $this->spell_link = $pool_spell['spell_level'];
        $this->spell_level = $pool_spell['spell_level'];
        $this->character_class_name = $pool_spell['character_class_name'];
    }

    public function fromJSON($pool_spell_json) {
        $this->spell_type_name = $pool_spell_json->spell_type_name;
        $this->spell_pool_id = $pool_spell_json->spell_pool_id;
        $this->spell_catalog_id = $pool_spell_json->spell_catalog_id;
        $this->spell_name = $pool_spell_json->spell_name;
        $this->spell_link = $pool_spell_json->spell_level;
        $this->spell_level = $pool_spell_json->spell_level;
        $this->character_class_name = $pool_spell_json->character_class_name;
    }
    
    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public function getSpellTypeName() {
       return $this->spell_type_name;
    }

    public function getSpellPoolId() {
       return $this->spell_pool_id;
    }

    public function getSpellCatalogId() {
       return $this->spell_catalog_id;
    }

    public function getSpellName() {
       return $this->spell_name;
    }

    public function getSpellLink() {
       return $this->spell_link;
    }

    public function getSpellLevel() {
       return $this->spell_level;
    }

    public function getCharacterClassName() {
       return $this->character_class_name;
    }
}
?>