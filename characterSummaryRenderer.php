<?php

include_once 'characterSummary.php';

class CharacterSummaryRenderer
{
    private $character_name;
	function __construct($character_name) {
        $this->character_name = $character_name;
    }

	public function render(\CharacterSummary $character_summary) {
        $output_html = $this->character_name . ' &nbsp; ';
        $output_html .= '<strong>';
		$output_html .= 'S: ' . $character_summary->formatStrength($character_summary);
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'I: ' . $character_summary->formatIntelligence($character_summary);
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'W: ' . $character_summary->formatWisdom($character_summary);
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'D: ' . $character_summary->formatDexterity($character_summary);
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'Cn: ' . $character_summary->getConstitution();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'Ch: ' . $character_summary->getCharisma();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'Cm: ' . $character_summary->getComeliness();
        $output_html .= '</strong>';

        $output_html .= ' | AC: ' . $character_summary->getArmorClass();
        $output_html .= ' | HP: ' . $character_summary->getHitPoints();

        return $output_html;
    }
}
