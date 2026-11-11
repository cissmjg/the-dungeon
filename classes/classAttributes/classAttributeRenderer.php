<?php
require_once __DIR__ . '/../../helper/HtmlHelper.php';

require_once 'playerCharacterSkillSet.php';
require_once 'characterDetails.php';
require_once 'attributeMetadata.php';
require_once 'rowClassManager.php';
require_once 'classAttributeBuilder.php';
require_once 'noClassAttributeRenderer.php';

const CLASS_ATTRIBUTE_DIV_ID = 'ca-';

class ClassAttributeRenderer {
    
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

    public function getClassName() {
        return $this->getClassAttributeBuilder()->getAccountClassSummary()->getClassName();
    }

    private $class_attribute_div_id;
    protected function getClassAttributeDivId() {
        return $this->class_attribute_div_id;
    }

    public function __construct(PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, ClassAttributeBuilder $class_attribute_builder, RowClassManager $row_class_manager) {
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
        $this->class_attribute_builder = $class_attribute_builder;
        $this->row_class_manager = $row_class_manager;

        $this->class_attribute_div_id = CLASS_ATTRIBUTE_DIV_ID . $this->getClassName();
    }

    public function render() {
        $output_html  = HtmlHelper::buildDivStartTag($this->getClassAttributeDivId());
        $output_html .= HtmlHelper::buildSpanTagWithText('csSectionHeader', $this->getClassName());
        $output_html .= $this->renderClassAttributes();
        $output_html .= HtmlHelper::buildDivEndTag();

        return $output_html;
    }

    private function renderClassAttributes() {
        $output_html = '';
        $this->class_attribute_builder->init();
        if ($this->getClassAttributeBuilder()->isEmpty()) {
            $no_class_renderer = new NoClassAttributeRenderer();
            $output_html .= $no_class_renderer->render($this->getRowClassManager());
        } else {
            $class_attribute_list = $this->class_attribute_builder->getClassAttributeSet()->getAll();
            foreach($class_attribute_list AS $class_attribute) {
                $div_style_class_list = $this->buildClassAttributeContainerClasses('caContainer');
                $output_html .= HtmlHelper::buildDivStartTag($div_style_class_list) . PHP_EOL;
                $output_html .= HtmlHelper::buildDivTag('caDescription', $class_attribute->getDescription());
                if (!empty($class_attribute->getFrequency())) {
                    $output_html .= HtmlHelper::buildDivTag('caValue', $class_attribute->getFrequency());
                } else if (!empty($class_attribute->getTotal())) {
                    if ($class_attribute->isCollectionEmpty()) {                        
                        $output_html .= HtmlHelper::buildDivTag('caValue', $class_attribute->getTotal());
                    }
                    else {
                        // TODO Render RmCollection Javascript
                        $output_html .= HtmlHelper::buildDivTag('caValue', $class_attribute->getTotal());
                    }
                } else {
                    $output_html .= HtmlHelper::buildDivTag('', '&nbsp;');
                }
    
                $output_html .= HtmlHelper::buildDivEndTag() . PHP_EOL;
            }

        }

        return $output_html;
    }

    private function buildClassAttributeContainerClasses($container_class) {
        $row_class_name = $this->getRowClassManager()->getClassName();

        return $container_class . ' ' . $row_class_name;
    }
}

?>