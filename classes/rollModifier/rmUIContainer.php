<?php

require_once 'rmUIEntry.php';
require_once __DIR__ . '/../../helper/HtmlHelper.php';

class RmUIContainer {

    private const RM_CONTAINER_STYLE = 'rmModifierContainer';

    private $rmCollection;
    public function getRmCollection() {
        return $this->rmCollection;
    }

    private $containerTitle;
    public function getContainerTitle() {
        return $this->containerTitle;
    }

    private $rm_title_style = 'rmModifierContainerTitleStyle';
    public function getTitleStyle() {
        return $this->rm_title_style;
    }

    public function setTitleStyle($title_style) {
        $this->rm_title_style = $title_style;
    }

    public function __construct(RmCollection $rm_collection, $container_title) {
        $this->rmCollection = $rm_collection;
        $this->containerTitle = $container_title;
    }

    public function render() {
        $output_html  = '<table style="width: 50%;">' . PHP_EOL;
        $output_html .= '<tr><td class="' . $this->getTitleStyle() . '" colspan="2">' . $this->getContainerTitle() . '</td></tr>';
        $output_html .= '<tr><th>Description</th><th>Adj</th></tr>' . PHP_EOL;
        
        foreach($this->rmCollection->getAll() AS $rmFactor) {
            $rm_ui_entry = new RmUIEntry($rmFactor);
            $output_html .= $rm_ui_entry->build();
        }

        $output_html .= '</table>' . PHP_EOL;
        return $output_html;
    }
}

?>