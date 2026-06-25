<?php
    require_once 'playerCharacterWeaponRendererBase.php';
    require_once 'playerCharacterMeleeWeaponRenderer.php';
    require_once 'playerCharacterMissileWeaponRenderer.php';
    require_once 'playerCharacterSkillSet.php';
    require_once 'playerCharacterWeapon.php';
    require_once 'characterDetails.php';
    require_once 'attributeMetadata.php';
    require_once 'rowClassManager.php';

    require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
    require_once __DIR__ . '/../helper/HtmlHelper.php';

    class PlayerCharacterWeaponRenderer extends PlayerCharacterWeaponRendererBase {
        private $display_id = '';
        public function getId() {
            if (strlen($this->display_id) == 0) {
                $this->display_id = 'csd-' . getMountedCombatModeDescription($this->getCombatMode()) . '-' . $this->getPlayerCharacterWeapon()->getWeaponId() . '-weapon';
            }

            return $this->display_id;
        }
        
        public function __construct(PlayerCharacterWeapon $player_character_weapon, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, RowClassManager $row_class_manager) {
            $this->player_character_weapon = $player_character_weapon;
            $this->player_character_skill_set = $player_character_skill_set;
            $this->character_details = $character_details;
            $this->attribute_metadata = $attribute_metadata;
            $this->row_class_manager = $row_class_manager;
        }

        public function render() {
            $output_html = '';
            $output_html .= HtmlHelper::buildDivStartTagWithId('', $this->getId(), false) . PHP_EOL;
            if ($this->getPlayerCharacterWeapon()->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
                $melee_weapon_renderer = new PlayerCharacterMeleeWeaponRenderer($this->getPlayerCharacterWeapon(), $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
                $melee_weapon_renderer->setCombatMode($this->getCombatMode());
                $output_html .= $melee_weapon_renderer->render();
            }

            if ($this->getPlayerCharacterWeapon()->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
                $missile_weapon_renderer = new PlayerCharacterMissileWeaponRenderer($this->getPlayerCharacterWeapon(), $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
                $missile_weapon_renderer->setCombatMode($this->getCombatMode());
                $output_html .= $missile_weapon_renderer->render();
            }
            $output_html .= HtmlHelper::buildDivEndTag() . PHP_EOL;
            
            return $output_html;
        }
    }
?>