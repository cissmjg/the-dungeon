<?php

require_once 'playerCharacterSkillSet.php';
require_once 'characterDetails.php';
require_once 'attributeMetadata.php';
require_once 'rowClassManager.php';
require_once 'classAttributeBuilder.php';
require_once 'accountClassSummary.php';

require_once 'classAttributeRenderer.php';
require_once 'noClassAttributeRenderer.php';

require_once 'archerClassAttributeBuilder.php';
require_once 'archerRangerClassAttributeBuilder.php';
require_once 'barbarianClassAttributeBuilder.php';
require_once 'clericClassAttributeBuilder.php';
require_once 'druidClassAttributeBuilder.php';
require_once 'elvenCavalierClassAttributeBuilder.php';
require_once 'fighterClassAttributeBuilder.php';
require_once 'greaterMageClassAttributeBuilder.php';
require_once 'healerMageClassAttributeBuilder.php';
require_once 'illusionistClassAttributeBuilder.php';
require_once 'magicUserClassAttributeBuilder.php';
require_once 'paladinClassAttributeBuilder.php';
require_once 'rangerClassAttributeBuilder.php';
require_once 'thiefClassAttributeBuilder.php';

require_once __DIR__ . '/../dbio/constants/characterClasses.php';

class PlayerCharacterClassAttributeRenderer {
    protected $class_attribute_builder;
    protected function getClassAttributeBuilder() {
        return $this->class_attribute_builder;
    }

    protected $player_character_skill_set;
    public function getPlayerCharacterSkillSet() {
        return $this->player_character_skill_set;
    }

    protected $character_details;
    public function getCharacterDetails() {
        return $this->character_details;
    }

    protected $attribute_metadata;
    public function getAttributeMetadata() {
        return $this->attribute_metadata;
    }

    protected $row_class_manager;
    public function getRowClassManager() {
        return $this->row_class_manager;
    }

    public function __construct(PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, RowClassManager $row_class_manager) {
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
        $this->row_class_manager = $row_class_manager;
    }

    public function render() {
        $output_html = '';
        $character_classes = $this->getCharacterDetails()->getCharacterClasses();
        foreach($character_classes AS $character_class) {
            $class_id = $character_class->getClassId();
            $character_class_attribute_builder = $this->getCharacterClassAttributeBuilder($class_id, $this->getCharacterDetails());
            if (!empty($character_class_attribute_builder)) {
                $class_attribute_renderer = new ClassAttributeRenderer($this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $character_class_attribute_builder, $this->getRowClassManager());
                $output_html .= $class_attribute_renderer->render();
            } else {
                $no_class_attribute_renderer = new NoClassAttributeRenderer();
                $output_html .=  $no_class_attribute_renderer->render($this->getRowClassManager());
            }
        }

        return $output_html;
    }

    private function getCharacterClassAttributeBuilder($class_id, CharacterDetails $character_details) {
        switch ($class_id) {
            case CLERIC:
                return new ClericClassAttributeBuilder($class_id, $character_details);
            case DRUID:
                return new DruidClassAttributeBuilder($class_id, $character_details);
            case FIGHTER:
                return new FighterClassAttributeBuilder($class_id, $character_details);
            case ILLUSIONIST:
                return new IllusionistClassAttributeBuilder($class_id, $character_details);
            case MAGIC_USER:
                return new MagicUserClassAttributeBuilder($class_id, $character_details);
            case PALADIN:
                return new PaladinClassAttributeBuilder($class_id, $character_details);
            case RANGER:
                return new RangerClassAttributeBuilder($class_id, $character_details);
            case THIEF:
                $thief_class_attribute_builder = new ThiefClassAttributeBuilder($class_id, $character_details);
                $thief_class_attribute_builder->setCharacterDetails($character_details);
                $thief_class_attribute_builder->setPlayerCharacterSkillSet($this->getPlayerCharacterSkillSet());
                $thief_class_attribute_builder->setAttributeMetadata($this->getAttributeMetadata());
                return $thief_class_attribute_builder;
            case GREATER_MAGE:
                return new GreaterMageClassAttributeBuilder($class_id, $character_details);
            case BARBARIAN:
                $barbarian_class_attribute_builder = new BarbarianClassAttributeBuilder(BARBARIAN, $character_details);
                $barbarian_class_attribute_builder->setCharacterDetails($character_details);
                $barbarian_class_attribute_builder->setPlayerCharacterSkillSet($this->getPlayerCharacterSkillSet());
                $barbarian_class_attribute_builder->setAttributeMetadata($this->getAttributeMetadata());
                return $barbarian_class_attribute_builder;
            case ARCHER:
                return new ArcherClassAttributeBuilder(ARCHER, $character_details);
            case ARCHER_RANGER:
                return new ArcherRangerClassAttributeBuilder(ARCHER_RANGER, $character_details);
            case HEALER:
                return new HealerClassAttributeBuilder(HEALER, $character_details);
            case ELVEN_CAVALIER:
                return new ElvenCavalierClassAttributeBuilder(ELVEN_CAVALIER, $character_details);
            default:
                return null;
        }
    }   // Thief-Acrobat, New Bard
}

?>