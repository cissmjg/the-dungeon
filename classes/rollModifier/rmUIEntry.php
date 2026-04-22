<?php
require_once 'rmFactor.php';
require_once __DIR__ . '/rmCategory.php';

require_once __DIR__ . '/../../helper/HtmlHelper.php';

class RmUIEntry {
    private $rmFactor;
    private const DEFAULT_CELL_DESC_STYLE = "rmEntryDesc";
    private const DEFAULT_CELL_DATA_STYLE = "rmEntryData";
    private const DEFAULT_ROW_STYLE = "rmRowDefault";
    private const RM_DATA_BONUS_STYLE = "rmEntryBonus";
    private const RM_DATA_PENALTY_STYLE = "rmEntryPenalty";

    private $cellDescStyle = RmUIEntry::DEFAULT_CELL_DESC_STYLE;
    public function getCellDescStyle() {
        return $this->cellDescStyle;
    }

    public function setCellDescStyle($cell_desc_style) {
        $this->cellDescStyle = $cell_desc_style;
    }

    private $cellDataStyle = RmUIEntry::DEFAULT_CELL_DATA_STYLE;
    public function getCellDataStyle() {
        return $this->cellDataStyle;
    }

    public function setCellDataStyle($cell_data_style) {
        $this->cellDataStyle = $cell_data_style;
    }

    private $rowStyle = RmUIEntry::DEFAULT_ROW_STYLE;
    public function getRowStyle() {
        return $this->rowStyle;
    }

    public function setRowStyle($row_style) {
        $this->rowStyle = $row_style;
    }

    function __construct(\RmFactor $rm_factor) {
        $this->rmFactor = $rm_factor;
    }

    public function build() {
        $output_html  = '<tr class="' . $this->getRowStyle() . '">';
        $output_html .= '    <td class="' . $this->getCellDescStyle() .'">' . $this->rmFactor->getRMDescription() . '</td>';

        $cell_style = $this->getCellDataStyle();
        if ($this->rmFactor->getRmCategory() == ROLL_MODIFIER_PENALTY) {
            $cell_style .= ' ' . RmUIEntry::RM_DATA_PENALTY_STYLE;
        } else {
            $cell_style .= ' ' . RmUIEntry::RM_DATA_BONUS_STYLE;
        }

        $output_html .= '<td class="' . $cell_style . '">' . $this->formatRmData($this->rmFactor->getRMData()) . '</td>';
        $output_html .= '</tr>' . PHP_EOL;

        return $output_html;
    }

    private function formatRmData($rm_data) {
        return sprintf("%+d", $rm_data);
    }
}
?>