<?php

require_once 'characterSummary.php';

class CharacterSummaryRenderer
{
    private $character_name;
	function __construct($character_name) {
        $this->character_name = $character_name;
    }

	public function render(\CharacterSummary $character_summary) {
        $output_html = $this->character_name . ' &nbsp; ';
        $output_html .= '<strong>';
		$output_html .= 'S: ' . $character_summary->formatStrength();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'I: ' . $character_summary->formatIntelligence();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'W: ' . $character_summary->formatWisdom();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'D: ' . $character_summary->formatDexterity();
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

    public function renderCharacterDetails(\CharacterDetails $character_details) {
        $output_html = $this->character_name . ' &nbsp; ';
        $output_html .= '<strong>';
		$output_html .= 'S: ' . $character_details->formatStrength();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'I: ' . $character_details->formatIntelligence();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'W: ' . $character_details->formatWisdom();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'D: ' . $character_details->formatDexterity();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'Cn: ' . $character_details->getCharacterConstitution();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'Ch: ' . $character_details->getCharacterCharisma();
        $output_html .= '</strong>';

        $output_html .= ' | <strong>';
        $output_html .= 'Cm: ' . $character_details->getCharacterComeliness();
        $output_html .= '</strong>';

        $output_html .= ' | AC: ' . $character_details->getArmorClass();
        $output_html .= ' | HP: ' . $character_details->getHitPoints();

        return $output_html;
    }
}
