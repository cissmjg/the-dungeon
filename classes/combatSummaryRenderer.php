<?php
    require_once 'playerCharacterWeaponRenderer.php';
    require_once 'playerCharacterSkillSet.php';
    require_once 'playerCharacterWeaponSet.php';
    require_once 'twoWeaponFightingConfigurationSet.php';
    require_once 'twoWeaponFightingRenderer.php';
    require_once 'characterDetails.php';
    require_once 'attributeMetadata.php';
    require_once 'rowClassManager.php';

    require_once __DIR__ . '/../dbio/constants/skills.php';
    require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
    require_once __DIR__ . '/../dbio/constants/characterClasses.php';

    require_once __DIR__ . '/../helper/HtmlHelper.php';

    abstract class CombatSummaryRenderer {

    protected $player_character_weapon_set;
    public function getPlayerCharacterWeaponSet() {
        return $this->player_character_weapon_set;
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

    protected $two_weapon_fighting_configuration_set;
    public function getTwoWeaponFightingConfigurationSet() {
        return $this->two_weapon_fighting_configuration_set;
    }

    protected $row_class_manager;
    protected function getRowClassManager() {
        return $this->row_class_manager;
    }

    public function __construct(PlayerCharacterWeaponSet $player_character_weapon_set, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, TwoWeaponFightingConfigurationSet $two_weapon_fighting_configuration_set) {
        $this->player_character_weapon_set = $player_character_weapon_set;
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
        $this->two_weapon_fighting_configuration_set = $two_weapon_fighting_configuration_set;
        $this->row_class_manager = new RowClassManager();
    }
    
    public function render() {
        $is_mounted_section_needed = $this->isMountedSectionNeeded($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet());
        if ($is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionStart(COMBAT_MODE_MOUNTED);
            echo $this->renderHeader();
            echo $this->renderSection(COMBAT_MODE_MOUNTED);
            echo $this->renderCollapsibleSectionEnd();
            echo HtmlHelper::buildSpacerDivTag();
        }

        if ($is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionStart(COMBAT_MODE_UNMOUNTED);
        }

        echo $this->renderHeader();
        echo $this->renderSection(COMBAT_MODE_UNMOUNTED);
        
        if ($is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionEnd();
        }
    }


    protected function isMountedSectionNeeded(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set) {
        $is_cavalier = $character_details->isCavalierType();
        $mounted_specialist_present = $player_character_skill_set->getAllSkillInstances(MOUNTED_ATTACK_SPECIALIST);

        return ($is_cavalier || $mounted_specialist_present);
    }

    abstract protected function renderSection($combat_mode);

    protected function renderCollapsibleSectionStart($combat_mode) {
        $toggle_panel_text = getMountedCombatModeDescription($combat_mode);

        $toggle_panel_html  = HtmlHelper::buildDivStartTag('togglePanel') . PHP_EOL;
        $toggle_panel_html .= '<a href="#">' . PHP_EOL;
        $toggle_panel_html .= '  <span class="fa fa-plus"></span> ' .  $toggle_panel_text . PHP_EOL;
        $toggle_panel_html .= '</a>' . PHP_EOL;
        $toggle_panel_html .= '<div class="togglePanelContent">' . PHP_EOL;
        return  $toggle_panel_html;
    }

    protected function renderCollapsibleSectionEnd() {
        echo HtmlHelper::buildDivEndTag() . PHP_EOL;
        echo HtmlHelper::buildDivEndTag() . PHP_EOL;
    }

    protected function renderHeader() {
        return $this->formatColumnHeaders();
    }

    protected function formatColumnHeaders() {
        $header  = '  <div class="rmWeaponContainer">' . PHP_EOL;
        $header .= '    <div class="rmWeaponHeaderItem">Weapon</div>' . PHP_EOL;
        $header .= '    <div class="rmWeaponHeaderItem">Spd</div>' . PHP_EOL;
        $header .= '    <div class="rmWeaponHeaderItem">Att</div>' . PHP_EOL;
        $header .= '    <div class="rmWeaponHeaderItem">Dmg</div>' . PHP_EOL;
        $header .= '    <div class="rmWeaponHeaderItem">Range</div>' . PHP_EOL;
        $header .= '    <div class="rmWeaponHeaderItem">Bonus</div>' . PHP_EOL;
        $header .= '    <div class="rmWeaponHeaderItem">Notes</div>' . PHP_EOL;
        $header .= '  </div>' . PHP_EOL;

        return $header;
    }
}
?>