<?php

class HtmlHelper {

const JQUERY_LIB = "https://code.jquery.com/jquery-3.7.1.min.js";
const JQUERY_UI_LIB = "js/jquery-ui.min.js";

const FONT_AWESOME_LIB = "https://kit.fontawesome.com/4295d6f264.js";

    public static function buildHiddenTag($name, $value) {
        return HtmlHelper::buildHiddenTagWithId($name, $name, $value);
    }

    public static function buildHiddenTagWithId($name, $id, $value) {
        $hidden_tag = '<input type="hidden" ';
        $hidden_tag_id_name = 'id="' . $id . '" name="' . $name . '" ';
        $hidden_tag .= $hidden_tag_id_name;
        $hidden_value = 'value="' . $value . '"';
        $hidden_tag .= $hidden_value;
        $hidden_tag .= '>';

        return $hidden_tag;
    }
    public static function formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels) {
        $output_html  = '<!DOCTYPE html>' . PHP_EOL;
        $output_html .= '<html lang="en">' . PHP_EOL;
        $output_html .= '<head>' . PHP_EOL;
        $output_html .= '    <meta charset="UTF-8">' . PHP_EOL;
        $output_html .= '    <meta name="viewport" content="width=device-width, initial-scale=1.0">' . PHP_EOL;
        $output_html .= '    <meta name="Cache-Control" content="no-store">' . PHP_EOL . PHP_EOL;
        $output_html .= '    <title>' . $page_title . '</title>' . PHP_EOL . PHP_EOL;
        $output_html .= '    <script src="' . HtmlHelper::JQUERY_LIB . '" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>' . PHP_EOL;
        $output_html .= '    <script src="' . HtmlHelper::JQUERY_UI_LIB . '"></script>' . PHP_EOL;
        $output_html .= '    <script src="' . HtmlHelper::FONT_AWESOME_LIB . '" crossorigin="anonymous"></script>' . PHP_EOL . PHP_EOL;
        $output_html .= '    <link rel="stylesheet" href="css/' . $site_css_file .'">' . PHP_EOL . PHP_EOL;
        if ($enable_toggle_panels) {
            $output_html .= '    <link rel="stylesheet" href="togglePanel.css">' . PHP_EOL;
            $output_html .= '    <script type="module" src="js/togglePanel.js"></script>' . PHP_EOL . PHP_EOL;
        }

        if (!empty($page_specific_js)) {
            $output_html .= '    <script src="js/' . $page_specific_js . '" type="module"></script>' . PHP_EOL;
        }

        if (!empty($page_specific_css)) {
             $output_html .= '    <link href="css/' . $page_specific_css .'" rel="stylesheet">' . PHP_EOL;
        }

        $output_html .= '</head>' . PHP_EOL;

        return $output_html;
    }
}

?>
