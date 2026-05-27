<?php

require_once 'readySpellFormIdLookup.php';
require_once 'playerCharacterReadySpell.php';
require_once 'rowClassManager.php';

require_once __DIR__ . '/../fa/faCancelIcon.php';
require_once __DIR__ . '/../fa/faCastSpellIcon.php';
require_once __DIR__ . '/../fa/faEditIcon.php';
require_once __DIR__ . '/../fa/faRefreshIcon.php';
require_once __DIR__ . '/../fa/faRunSpellIcon.php';
require_once __DIR__ . '/../fa/faMemorizeSpellIcon.php';
require_once __DIR__ . '/../fa/faPraySpellIcon.php';
require_once __DIR__ . '/../fa/faHealSpellIcon.php';
require_once __DIR__ . '/../fa/faNatureIcon.php';
require_once __DIR__ . '/../fa/faStopSpellIcon.php';
require_once __DIR__ . '/../fa/faReclaimCantripIcon.php';

require_once __DIR__ . '/../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../dbio/constants/emptySpellSlot.php';
require_once __DIR__ . '/../dbio/constants/cantripSpellSlot.php';

class PlayerCharacterReadySpellRenderer {

    private $ready_spell_form_id_lookup;
    public function getReadySpellFormIdLookup() {
        return $this->ready_spell_form_id_lookup;
    }

    private $player_character_ready_spell;
    public function getPlayerCharacterReadySpell() {
        return $this->player_character_ready_spell;
    }

    private $player_character_spell_pool;
    public function getPlayerCharacterSpellPool() {
        return $this->player_character_spell_pool;
    }

    private $row_class_manager;
    public function getRowClassManager() {
        return $this->row_class_manager;
    }

    public function __construct(PlayerCharacterReadySpell $player_character_ready_spell, PlayerCharacterSpellPool $player_character_spell_pool, ReadySpellFormIdLookup $ready_spell_form_id_lookup, RowClassManager $row_class_manager) {
        $this->player_character_ready_spell = $player_character_ready_spell;
        $this->player_character_spell_pool =  $player_character_spell_pool;
        $this->ready_spell_form_id_lookup = $ready_spell_form_id_lookup;
        $this->row_class_manager = $row_class_manager;
    }

    public function render() {
        $slot_id = $this->getPlayerCharacterReadySpell()->getSpellSlotId();
        $row_slot_id = 'row-slot-' . $slot_id;
        $row_update_slot_id = 'row-change-' . $slot_id;
        $row_bg_class = $this->getRowClassManager()->getClassName();

        $output_html = '';
        if ($this->getPlayerCharacterReadySpell()->getSpellName() == EMPTY_SLOT_SPELL_NAME) {
            $output_html .= $this->renderNoneRow($row_bg_class, $row_slot_id, $row_update_slot_id);
        } else if ($this->getPlayerCharacterReadySpell()->getSpellName() == CANTRIP_SLOT_SPELL_NAME) {
            $output_html .= $this->renderCantripRow($row_bg_class, $row_slot_id);
        } else if ($this->getPlayerCharacterReadySpell()->getSpellState() == ReadySpellState::Ready) {
            $output_html .= $this->renderReadySpellRow($row_bg_class, $row_slot_id, $row_update_slot_id);
        } else if ($this->getPlayerCharacterReadySpell()->getSpellState() == ReadySpellState::AlreadyCast) {
            $output_html .= $this->renderAlreadyCastSpellRow($row_bg_class, $row_slot_id, $row_update_slot_id);
        } else if ($this->getPlayerCharacterReadySpell()->getSpellState() == ReadySpellState::Running) {
            $output_html .= $this->renderRunningSpellRow($row_bg_class, $row_slot_id, $row_update_slot_id);
        } else if ($this->getPlayerCharacterReadySpell()->getSpellState() == ReadySpellState::Casting) {
            $output_html .= $this->renderCastingSpellRow($row_bg_class, $row_slot_id, $row_update_slot_id);
        }

        if ($this->getPlayerCharacterReadySpell()->getSpellName() != CANTRIP_SLOT_SPELL_NAME) {
            $output_html .= $this->renderUpdateSlotRow($row_slot_id, $row_update_slot_id);
        }

        return $output_html;
    }

    private function renderUpdateSlotRow($row_slot_id, $row_update_slot_id) {
        $background_style = $this->getChangeSlotBackgroundStyle($this->getPlayerCharacterReadySpell()->getSpellTypeName());
        $spell_slot_id = $this->getPlayerCharacterReadySpell()->getSpellSlotId();
        $select_id = 'update-spell-slot-' . $spell_slot_id;

        $option_list = $this->buildOptionList($this->getPlayerCharacterReadySpell()->getPlayerSlotLevel());
        $class_icon = $this->buildClassSpecificSpellIcon($this->getPlayerCharacterReadySpell()->getSpellTypeName());
        $change_slot_icon = $this->decorateUpdateSloticon($class_icon, $spell_slot_id, $select_id, $this->getPlayerCharacterReadySpell()->getCharacterClassName(),  $this->getPlayerCharacterReadySpell()->getPlayerSlotLevel());
        $formatted_spell_name = '<span>' . $this->getPlayerCharacterReadySpell()->getSpellName() . '</span>';
        $left_arrow = '<span class="fa-solid fa-arrow-left"></span>';
        $cancel_change_slot_icon = $this->buildCancelChangeSlotIcon($row_slot_id, $row_update_slot_id);

        $output_html  = '<tr id="' . $row_update_slot_id . '" class="' . $background_style . '" hidden>' . PHP_EOL;
        $output_html .= '<td colspan="7">' . PHP_EOL;

        if (strlen(trim($option_list)) > 0) {
            $output_html .= $formatted_spell_name;
            $output_html .= '&nbsp;';
            $output_html .= $left_arrow;
            $output_html .= '&nbsp;' . PHP_EOL;
            $output_html .= '<select id="' . $select_id . '" style="font-size: 18px;">' . PHP_EOL;
            $output_html .= $option_list;
            $output_html .= '</select>' . PHP_EOL;
            $output_html .= '&nbsp;';
            $output_html .= $change_slot_icon . PHP_EOL;
        } else {
            $output_html .= '<span style="font-weight: bold">No Spells available for this level</span>' . PHP_EOL;
        }

        $output_html .= '&nbsp;';
        $output_html .= $cancel_change_slot_icon . PHP_EOL;
        $output_html .= '</td>' . PHP_EOL;
        $output_html .= '</tr>' . PHP_EOL;

        return $output_html;
    }

    private function buildOptionList($spell_level) {
        $character_class_name = $this->getPlayerCharacterReadySpell()->getCharacterClassName();
        $character_class_id = getClassID($character_class_name);
        $spell_type_name = $this->getPlayerCharacterReadySpell()->getSpellTypeName();
        $cantrip_option = '<option value="' . CANTRIP_SLOT_SPELL_CATALOG_ID . '">' . CANTRIP_SLOT_SPELL_NAME . '</option>' . PHP_EOL;
        
        if ($spell_level == '0') {
            return implode(' ', $this->getPlayerCharacterSpellPool()->getOptions($character_class_name, $spell_type_name, 'Cantrip'));
        }

        $option_list = '';
        for($i = $spell_level; $i >= 1; $i--) {
            $option_list .= implode(' ', $this->getPlayerCharacterSpellPool()->getOptions($character_class_name, $spell_type_name, $i));
            
            if (!isArcherType($character_class_id)) {
                $option_list .= $cantrip_option;
            }
        }

        return $option_list;
    }

    private function renderNoneRow($row_bg_class, $row_slot_id, $row_update_slot_id) {
        $blank_cell = '<td>&nbsp;</td>' . PHP_EOL;
        $show_change_slot_icon = $this->buildShowChangeSlotIcon($row_slot_id, $row_update_slot_id);

        $output_html  = '<tr id="' . $row_slot_id . '" class="' . $row_bg_class . '">' . PHP_EOL;
        $output_html .= $blank_cell;
        $output_html .= '<td><span class="spell_slot_none">' . EMPTY_SLOT_SPELL_NAME . '</span></td>' . PHP_EOL;
        $output_html .= '<td class="spell_slot_center">' . $show_change_slot_icon . '</td>' . PHP_EOL;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= '</tr>' . PHP_EOL;

        return $output_html;
    }

    private function renderCantripRow($row_bg_class, $row_slot_id) {
        $blank_cell = '<td>&nbsp;</td>' . PHP_EOL;
        $reclaim_cantrips_icon = $this->buildReclaimCantripsIcon($this->getPlayerCharacterReadySpell()->getSpellSlotId());

        $output_html  = '<tr id="' . $row_slot_id . '" class="' . $row_bg_class . '">' . PHP_EOL;
        $output_html .= $blank_cell;
        $output_html .= '<td><span class="spell_slot_cantrip">' . CANTRIP_SLOT_SPELL_NAME . '</span></td>' . PHP_EOL;
        $output_html .= '<td class="spell_slot_center">' . PHP_EOL;
        $output_html .= $reclaim_cantrips_icon . PHP_EOL;
        $output_html .= '</td>' . PHP_EOL;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= '</tr>' . PHP_EOL;

        return $output_html;
    }

    private function renderReadySpellRow($row_bg_class, $row_slot_id, $row_update_slot_id) {
        $spell_duration = $this->getPlayerCharacterReadySpell()->getSpellDuration();
        $spell_duration_in_rounds = empty($this->getPlayerCharacterReadySpell()->getSpellDurationInRounds()) ? "0" : $this->getPlayerCharacterReadySpell()->getSpellDurationInRounds();
        $spell_casting_time = $this->getPlayerCharacterReadySpell()->getSpellCastingTime();
        $spell_casting_time_in_rounds = empty($this->getPlayerCharacterReadySpell()->getSpellCastingTimeInRounds()) ? "0" : $this->getPlayerCharacterReadySpell()->getSpellCastingTimeInRounds();
        $spell_slot_id = $this->getPlayerCharacterReadySpell()->getSpellSlotId();
        $cast_spell_icon = $this->buildCastSpellIcon($spell_duration_in_rounds, $spell_casting_time_in_rounds, $spell_slot_id);
        $cast_spell_css_class = $this->getSpellClass(ReadySpellState::Ready);
        $show_change_slot_icon = $this->buildShowChangeSlotIcon($row_slot_id, $row_update_slot_id);

        $output_html  = '<tr id="' . $row_slot_id . '" class="' . $row_bg_class . '">' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . '  spell_slot_center">' . $cast_spell_icon . '</td>' . PHP_EOL;
        $output_html .= '<td><a class="' . $cast_spell_css_class . '" href="' . $this->getPlayerCharacterReadySpell()->getSpellLink() . '" target="_blank">' . $this->getPlayerCharacterReadySpell()->getSpellName() . '</a></td>' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . ' spell_slot_center">' . $show_change_slot_icon . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . ' spell_slot_center">' . $spell_casting_time . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . ' spell_slot_center">' . $this->getPlayerCharacterReadySpell()->getSpellRange() . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . ' spell_slot_center">' . $spell_duration . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . ' spell_slot_center">' . $this->getPlayerCharacterReadySpell()->getSpellAreaOfEffect() . '</td>' . PHP_EOL;
        $output_html .= '</tr>' . PHP_EOL;

        return $output_html;
    }

    private function renderAlreadyCastSpellRow($row_bg_class, $row_slot_id, $row_update_slot_id) {
        $blank_cell = '<td>&nbsp;</td>' . PHP_EOL;
        $refresh_icon = $this->buildRefreshIcon($this->getPlayerCharacterReadySpell()->getSpellSlotId());
        $spell_link = $this->getPlayerCharacterReadySpell()->getSpellLink();
        $spell_name = $this->getPlayerCharacterReadySpell()->getSpellName();
        $show_change_slot_icon = $this->buildShowChangeSlotIcon($row_slot_id, $row_update_slot_id);
        $cast_spell_css_class = $this->getSpellClass(ReadySpellState::AlreadyCast);

        $output_html  = '<tr id="' . $row_slot_id . '" class="' . $row_bg_class . '">' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . ' spell_slot_center">' . $refresh_icon . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . '"><a href="' . $spell_link . '" target="_blank">' . $spell_name . '</a></td>' . PHP_EOL;
        $output_html .= '<td class="' . $cast_spell_css_class . ' spell_slot_center">' . $show_change_slot_icon . '</td>' . PHP_EOL;

        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= '</tr>' . PHP_EOL;

        return $output_html;
    }

    private function renderRunningSpellRow($row_bg_class, $row_slot_id, $row_update_slot_id) {
        $blank_cell = '<td>&nbsp;</td>' . PHP_EOL;
        $spell_slot_id = $this->getPlayerCharacterReadySpell()->getSpellSlotId();
        $cancel_running_spell_icon = $this->buildCancelRunningSpellIcon($spell_slot_id);
        $spell_link = $this->getPlayerCharacterReadySpell()->getSpellLink();
        $spell_name = $this->getPlayerCharacterReadySpell()->getSpellName();
        $show_change_slot_icon = $this->buildShowChangeSlotIcon($row_slot_id, $row_update_slot_id);
        $spell_runtime_remaining = $this->getPlayerCharacterReadySpell()->getPlayerSlotRunningTimeRemaining();
        $running_spell_css_class = $this->getSpellClass(ReadySpellState::Running);

        $output_html  = '<tr id="' . $row_slot_id . '" class="' . $row_bg_class . '">' . PHP_EOL;
        $output_html .= '<td class="' . $running_spell_css_class . ' spell_slot_center">' . $cancel_running_spell_icon . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $running_spell_css_class . '"><a href="' . $spell_link . '" target="_blank">' . $spell_name . '</a></td>' . PHP_EOL;
        $output_html .= '<td class="' . $running_spell_css_class . ' spell_slot_center">' . $show_change_slot_icon . '</td>' . PHP_EOL;
        $output_html .= $blank_cell;
        $output_html .= $blank_cell;
        $output_html .= '<td class="' . $running_spell_css_class . ' spell_slot_center">' . $spell_runtime_remaining . '</td>';
        $output_html .= '<td class="' . $running_spell_css_class . ' spell_slot_center">' . $this->getPlayerCharacterReadySpell()->getSpellAreaOfEffect() . '</td>' . PHP_EOL;

        return $output_html;
    }

    private function renderCastingSpellRow($row_bg_class, $row_slot_id, $row_update_slot_id) {
        $blank_cell = '<td>&nbsp;</td>' . PHP_EOL;
        $spell_slot_id = $this->getPlayerCharacterReadySpell()->getSpellSlotId();
        $cancel_casting_spell_icon = $this->buildCancelCastingSpellIcon($spell_slot_id);
        $spell_link = $this->getPlayerCharacterReadySpell()->getSpellLink();
        $spell_name = $this->getPlayerCharacterReadySpell()->getSpellName();
        $show_change_slot_icon = $this->buildShowChangeSlotIcon($row_slot_id, $row_update_slot_id);
        $casting_spell_css_class = $this->getSpellClass(ReadySpellState::Casting);
        $spell_casting_time_remaining = $this->getPlayerCharacterReadySpell()->getPlayerSlotCastingTimeRemaining();
        $spell_duration = $this->getPlayerCharacterReadySpell()->getSpellDuration();
        $spell_area_of_effect = $this->getPlayerCharacterReadySpell()->getSpellAreaOfEffect();

        $output_html  = '<tr id="' . $row_slot_id . '" class="' . $row_bg_class . '">' . PHP_EOL;
        $output_html .= '<td class="' . $casting_spell_css_class . ' spell_slot_center">' . $cancel_casting_spell_icon . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $casting_spell_css_class . '"><a href="' . $spell_link . '" target="_blank">' . $spell_name . '</a></td>' . PHP_EOL;
        $output_html .= '<td class="' . $casting_spell_css_class . ' spell_slot_center">' . $show_change_slot_icon . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $casting_spell_css_class . ' spell_slot_center">' . $spell_casting_time_remaining . '</td>' . PHP_EOL;
        $output_html .= $blank_cell;
        $output_html .= '<td class="' . $casting_spell_css_class . ' spell_slot_center">' . $spell_duration . '</td>' . PHP_EOL;
        $output_html .= '<td class="' . $casting_spell_css_class . ' spell_slot_center">' . $spell_area_of_effect . '</td>' . PHP_EOL;

        return $output_html;
    }

    private function getChangeSlotBackgroundStyle($spell_type) {
        if ($spell_type == 'Magic-User' || $spell_type == 'Illusionist'|| $spell_type == 'Wu Jen') {
            return "arcaneMUBackground";
        } else if ($spell_type == 'Healer') {
            return "healerBackground";
        } else if ($spell_type == 'Druid') {
            return "druidBackground";
        } 
        
        return "divineBackground";
    }

    private function buildClassSpecificSpellIcon($spell_type) {
        $spellActionIcon = null;
        if ($spell_type == 'Magic-User' || $spell_type == 'Illusionist'|| $spell_type == 'Wu Jen') {
            $spellActionIcon = new FaMemorizeSpellIcon();
        } else if ($spell_type == 'Healer') {
            $spellActionIcon = new FaHealSpellIcon();
        } else if ($spell_type == 'Druid') {
            $spellActionIcon = new FaNatureIcon();
        } else {
            $spellActionIcon = new FaPraySpellIcon();
        }

        return $spellActionIcon;
    }

    private function decorateUpdateSloticon(FaActionIcon $class_icon, $spell_slot_id, $skill_catalog_select_element_id, $character_class_name, $spell_level) {
        $form_id = $this->getReadySpellFormIdLookup()->getUpdateSpellSlotFormId();
        $skill_catalog_element_id = $this->getReadySpellFormIdLookup()->getUpdateSpellSlotSpellCatalogId();
        $spell_slot_element_id = $this->getReadySpellFormIdLookup()->getUpdateSpellSlotId();
        $character_class_element_id = $this->getReadySpellFormIdLookup()->getUpdateSpellSlotCharacterClassName();
        $spell_level_element_id = $this->getReadySpellFormIdLookup()->getUpdateSpellSlotSpellLevel();

        $class_icon->setOnClickJsFunction('submitUpdateSlotForm');
        $class_icon->addOnclickJsParameter($form_id);
        $class_icon->addOnclickJsParameter($spell_slot_element_id);
        $class_icon->addOnclickJsParameter($spell_slot_id);
        $class_icon->addOnclickJsParameter($skill_catalog_element_id);
        $class_icon->addOnclickJsParameter($skill_catalog_select_element_id);
        $class_icon->addOnclickJsParameter($character_class_element_id);
        $class_icon->addOnclickJsParameter($character_class_name);
        $class_icon->addOnclickJsParameter($spell_level_element_id);
        $class_icon->addOnclickJsParameter($spell_level);
        $class_icon->setHoverText('Update Spell');

        return $class_icon->build();
    }

    private function getSpellClass(ReadySpellState $ready_spell_state) {
        $spell_class = '';
        switch($ready_spell_state) {
            case ReadySpellState::Ready:
                $spell_class = 'spell_slot_available';
                break;
            case ReadySpellState::AlreadyCast:
                $spell_class = 'spell_slot_cast';
                break;
            case ReadySpellState::Casting:
                $spell_class = 'spell_slot_casting';
                break;
            case ReadySpellState::Running:
                $spell_class = 'spell_slot_running';
                break;
            default:
                $spell_class = 'spell_slot_available';
        }

        return $spell_class;
    }

    private function buildCancelChangeSlotIcon($row_slot_id, $row_update_slot_id) {       
        $faCancelIcon = new FaCancelIcon();
        $faCancelIcon->setOnClickJsFunction('showSpellHideChangeForm');
        $faCancelIcon->addOnclickJsParameter($row_slot_id);
        $faCancelIcon->addOnclickJsParameter($row_update_slot_id);

        return $faCancelIcon->build();
    }

    private function buildShowChangeSlotIcon($row_slot_id, $row_update_slot_id) {
        $faEditIcon = new FaEditIcon();
        $faEditIcon->setOnClickJsFunction('hideSpellShowChangeForm');
        $faEditIcon->addOnclickJsParameter($row_slot_id);
        $faEditIcon->addOnclickJsParameter($row_update_slot_id);
        $faEditIcon->setHoverText('Change Spell');

        return $faEditIcon->build();
    }

    private function buildReclaimCantripsIcon($reclaim_cantrips_spell_slot_id) {
        $reclaim_cantrips_form_id = $this->getReadySpellFormIdLookup()->getReclaimCantripsFormId();
        $reclaim_cantrips_slot_element_id = $this->getReadySpellFormIdLookup()->getReclaimCantripsSlotId();

        $reclaimCantripIcon = new FaReclaimCantripIcon();
        $reclaimCantripIcon->setOnClickJsFunction('submitReclaimCantripsForm');
        $reclaimCantripIcon->addOnclickJsParameter($reclaim_cantrips_form_id);
        $reclaimCantripIcon->addOnclickJsParameter($reclaim_cantrips_slot_element_id);
        $reclaimCantripIcon->addOnclickJsParameter($reclaim_cantrips_spell_slot_id);
        $reclaimCantripIcon->setHoverText('Reclaim Cantrips');

        return $reclaimCantripIcon->build();
        
    }

    private function buildCastSpellIcon($spell_duration, $spell_casting_time, $spell_slot_id) {
        $cast_spell_form_id = $this->getReadySpellFormIdLookup()->getCastSpellSlotFormId();
        $cast_spell_duration_element_id = $this->getReadySpellFormIdLookup()->getCastSpellSlotDuration();
        $cast_spell_casting_time_element_id = $this->getReadySpellFormIdLookup()->getCastSpellSlotCastingTime();
        $cast_spell_slot_id = $this->getReadySpellFormIdLookup()->getCastSpellSlotId();

        $cast_spell_icon = new FaCastSpellIcon();
        $cast_spell_icon->setOnClickJsFunction('submitCastSpellForm');
        $cast_spell_icon->addOnclickJsParameter($cast_spell_form_id);
        $cast_spell_icon->addOnclickJsParameter($cast_spell_duration_element_id);
        $cast_spell_icon->addOnclickJsParameter($spell_duration);
        $cast_spell_icon->addOnclickJsParameter($cast_spell_casting_time_element_id);
        $cast_spell_icon->addOnclickJsParameter($spell_casting_time);
        $cast_spell_icon->addOnclickJsParameter($cast_spell_slot_id);
        $cast_spell_icon->addOnclickJsParameter($spell_slot_id);
        $cast_spell_icon->setHoverText('Cast Spell');
        
        return $cast_spell_icon->build();
    }

    private function buildRefreshIcon($spell_slot_id) {
        $refresh_slot_form_id = $this->getReadySpellFormIdLookup()->getResetSlotFormId();
        $refresh_slot_spell_slot_id = $this->getReadySpellFormIdLookup()->getResetSlotId();

        $refreshSlotIcon = new FaRefreshIcon();
        $refreshSlotIcon->setOnClickJsFunction('submitRefreshSpellSlotForm');
        $refreshSlotIcon->addOnclickJsParameter($refresh_slot_form_id);
        $refreshSlotIcon->addOnclickJsParameter($refresh_slot_spell_slot_id);
        $refreshSlotIcon->addOnclickJsParameter($spell_slot_id);
        $refreshSlotIcon->setHoverText('Refresh spell');
        
        return $refreshSlotIcon->build();
    }

    private function buildCancelRunningSpellIcon($spell_slot_id) {
        $cancel_running_slot_form_id = $this->getReadySpellFormIdLookup()->getStopRunningSlotFormId();
        $cancel_running_spell_slot_id = $this->getReadySpellFormIdLookup()->getStopRunningSlotId();

        $cancel_running_spell_icon = new FaRunSpellIcon();
        $cancel_running_spell_icon->setOnClickJsFunction('submitCancelRunningSpellSlotForm');
        $cancel_running_spell_icon->addOnclickJsParameter($cancel_running_slot_form_id);
        $cancel_running_spell_icon->addOnclickJsParameter($cancel_running_spell_slot_id);
        $cancel_running_spell_icon->addOnclickJsParameter($spell_slot_id);
        $cancel_running_spell_icon->setHoverText('Stop Running Spell');

        return $cancel_running_spell_icon->build();
    }

    private function buildCancelCastingSpellIcon($spell_slot_id) {
        $cancel_casting_slot_form_id = $this->getReadySpellFormIdLookup()->getStopCastingSlotFormId();
        $cancel_casting_spell_slot_id = $this->getReadySpellFormIdLookup()->getStopCastingSlotId();
        
        $cancel_casting_spell_icon = new FaStopSpellIcon();
        $cancel_casting_spell_icon->setOnClickJsFunction('submitCancelCastingSpellSlotForm');
        $cancel_casting_spell_icon->addOnclickJsParameter($cancel_casting_slot_form_id);
        $cancel_casting_spell_icon->addOnclickJsParameter($cancel_casting_spell_slot_id);
        $cancel_casting_spell_icon->addOnclickJsParameter($spell_slot_id);
        $cancel_casting_spell_icon->setHoverText('Stop Casting Spell');

        return $cancel_casting_spell_icon->build();
    }
}
?>