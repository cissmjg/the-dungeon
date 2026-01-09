<?php
require_once './rmFactor.php';

class RmUIEntry {
    private const DEFAULT_CELL_DESC_STYLE = "rmEntryDefaultDesc";
    private const DEFAULT_CELL_DATA_STYLE = "rmEntryDefaultData";
    private const DEFAULT_ROW_STYLE = "rmRowDefault";

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

    private $cellDesc;
    public function getCellDesc() {
        return $this->cellDesc;
    }

    public function setCellDesc($cell_desc) {
        $this->cellDesc = $cell_desc;
    }

    private $cellData;
    public function getCellData() {
        return $this->cellData;
    }

    public function setCellData($cell_data) {
        $this->cellData = $cell_data;
    }

    private $rowStyle = RmUIEntry::DEFAULT_ROW_STYLE;
    public function getRowStyle() {
        return $this->rowStyle;
    }

    public function setRowStyle($row_style) {
        $this->rowStyle = $row_style;
    }

    function __construct(\RmFactor $rm_factor) {
        $this->cellDesc = $rm_factor->getRMDescription();
        $this->cellData = $rm_factor->getRMData();
    }

    public function build() {
        $output_html  = '<tr class="' . $this->rowStyle . '">';
        $output_html .= '<td class="' . $this->cellDescStyle . '">';
        $output_html .= $this->cellDesc;
        $output_html .= '</td>';
        $output_html .= '<td class="' . $this->cellDataStyle . '">';
        if ($this->cellData > 0) {
            $output_html .= '+';
        } else if ($this->cellData < 0) {
            $output_html .= '-';
        }
        $output_html .= $this->cellData;
        $output_html .= '</td></tr>' . PHP_EOL;

        return $output_html;
    }
}
?>