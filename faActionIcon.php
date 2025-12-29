<?php
abstract class FaActionIcon {

    abstract protected function getFaClassList();

    private $element_id;
    public function getElementId() {
        return $this->element_id;
    }

    public function setElementId($element_id) {
        $this->element_id = $element_id;
    }

    private $is_hidden = false;
    public function isHidden() {
        return $this->is_hidden;
    }

    public function setHidden($is_hidden) {
        $this->is_hidden = $is_hidden;
    }

    private $onclick_js_function;
    public function getOnClickJsFunction() {
        return $this->onclick_js_function;
    }

    public function setOnClickJsFunction($onclick_js_function) {
        $this->onclick_js_function = $onclick_js_function;
    }

    private $onclick_js_params = [];
    public function addOnclickJsParameter($onclick_js_param) {
        $this->onclick_js_params[] = $this->wrapJSParameterWithSingleQuotes($onclick_js_param);
    }
    public function addUnquotedOnclickJsParameter($onclick_js_param) {
        $this->onclick_js_params[] = $onclick_js_param;
    }

    public function getOnClickJsParameters() {
        return $this->onclick_js_params;
    }

    private $icon_styles = [];
    public function addStyle($icon_style) {
        $this->icon_styles[] = $icon_style;
    }

    public function getStyles() {
        return $this->icon_styles;
    }

    private $hover_text = "";
    public function getHoverText() {
        return $this->hover_text;
    }

    public function setHoverText($hover_text) {
        $this->hover_text = $hover_text;
    }

    public function build() {
        $onclick = $this->buildOnClick();
        $fa_class = $this->buildFaClass();
        $style_list = $this->buildStyles();
        $hover_text = $this->buildHoverText();

        $output_html = '';
        if (!empty($this->getElementId())) {
            $output_html .= '<span id="' . $this->getElementId() . '"';
            if ($this->isHidden()) {
                $output_html .= " hidden";
            }
            $output_html .= '>';
        }

        $output_html .=  '<span' . $fa_class . $style_list . $onclick . $hover_text . '></span>';
        
        if (!empty($this->getElementId())) {
            $output_html .= '</span>';
        }

        return $output_html;
    }

    private function buildFaClass() {
        return ' class="' . $this-> getFaClassList() . '"';
    }

    function buildStyles() {
        $this->addStyle('cursor: pointer;');

        $final_style_list = ' style="';
        foreach($this->getStyles() AS $style) {
            $final_style_list .= $style . ' ';
        }

        $final_style_list .= '"';
        return $final_style_list;
    }

    function buildHoverText() {
        $final_hover_text = "";
        if(strlen($this->getHoverText()) > 0) {
            $final_hover_text = ' title="' . $this->hover_text . '"';
        }

        return $final_hover_text;
    }

    private function buildOnClick() {
        $parameter_list = implode(',', $this->getOnClickJsParameters());

        $output_html = ' onclick="';
        $output_html .= 'event.preventDefault(); ';
        $output_html .= $this->getOnClickJsFunction();
        $output_html .= '(';
        $output_html .= $parameter_list;
        $output_html .= ');"';

        return $output_html;
    }

    private function wrapJSParameterWithSingleQuotes($parameter_value) {
        return "'" . $parameter_value . "'";
    }
}
?>