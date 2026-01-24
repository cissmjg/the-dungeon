<?php
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/pageAction.php';

$errors = [];
$input = [];

const PORTRAIT_DIR = "portraits/";
const UNKNOWN_PORTRAIT = "Unknown.jpg";

getPlayerName($errors, $input);
$player_name = $input[PLAYER_NAME];

$params = [];
$params[PLAYER_NAME] = $player_name;

$url = CurlHelper::buildUrlDbioDirectory('getAccountSummary');
$raw_results = CurlHelper::performGetRequest($url, $params);

$account_character_summaries = json_decode($raw_results);

$page_title = 'Character List';
$site_css_file = 'dnd-default.css';
$page_specific_js = '';
$page_specific_css = '';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
<div class="player_portraits">
<?php
	foreach($account_character_summaries AS $account_character_summary) {
		$portrait_html = buildCharacterPortrait($account_character_summary, $player_name);
		
		echo $portrait_html;
	}

	echo buildNewCharacterPortrait($player_name);

	function buildCharacterPortrait($account_character_summary, $player_name) {
		$img_tag = buildPortraitImage($account_character_summary);
		$anchor_tag = buildPortraitAnchor($account_character_summary, $img_tag, $player_name);
        $character_name = $account_character_summary->character_name;
        $output_html = '<div class="character_portrait_container">';
        $output_html .= '<div class="character_portrait_title">';
		$output_html .= $character_name;
		$output_html .= buildClassIconImgTags($account_character_summary);
		$output_html .= '</div>';
		$output_html .= '<div>';
		$output_html .= $anchor_tag;
		$output_html .= '</div></div>' . PHP_EOL;

		return $output_html;
	}

	function buildNewCharacterPortrait($player_name) {
		$new_character_image_tag = buildNewCharacterPortraitImage();
		$new_character_anchor_tag = buildNewCharacterPortraitAnchor($new_character_image_tag, $player_name);
        $output_html = '<div class="character_portrait_container">';
        $output_html .= '<div class="character_portrait_title">New Character</div>';
		$output_html .= '<div class="character_portrait_image">';
		$output_html .= $new_character_anchor_tag;
		$output_html .= '</div></div>' . PHP_EOL;

		return $output_html;
	}

	function buildNewCharacterPortraitImage() {
		$new_character_portrait_file_loc = PORTRAIT_DIR . "New Character.jpg";
		$output_html = '<img class="character_portrait_image" src="' . $new_character_portrait_file_loc . '" ';
		$output_html .= 'title="Create New Character" ';
		$output_html .= 'alt="Create New Character"';
		$output_html .= '>';

		return $output_html;
	}

	function buildNewCharacterPortraitAnchor($img_tag, $player_name) {
		$create_character_url = CurlHelper::buildUrl('characterCreation1');
		$create_character_url = CurlHelper:: addParameter($create_character_url, PLAYER_NAME, $player_name);
		$create_character_url = CurlHelper:: addParameter($create_character_url, PAGE_ACTION, 'validate');
	
		$output_html = '<a href="' . $create_character_url . '" target="_blank">';
		$output_html .= $img_tag;
		$output_html .= '</a>';

		return $output_html;
	}

	function buildPortraitImage($account_character_summary) {
		$character_portrait = PORTRAIT_DIR . $account_character_summary->portrait_file_location;
		$output_html = '<img class="character_portrait_image" src="' . $character_portrait . '" ';
		$output_html .= 'title="' . $account_character_summary->character_name . '" ';
		$output_html .= 'alt="' . $account_character_summary->character_name . '"';
		$output_html .= '>';

		return $output_html;
	}

	function buildPortraitAnchor($account_character_summary, $img_tag, $player_name) {
		$url_character_view = buildViewCharacterUrl($player_name, $account_character_summary->character_name); 
		$output_html = '<a href="' . $url_character_view . '" target="_blank">';
		$output_html .= $img_tag;
		$output_html .= '</a>';

		return $output_html;
	}

	function buildClassIconImgTags($account_character_summary) {
		$output_html = '';
		foreach($account_character_summary->account_class_summaries as $account_class_summary) {
			$output_html .= '&nbsp;';
			$output_html .= '<img src="' . $account_class_summary->class_icon_file_location . '" ';
			$output_html .= 'alt="' . $account_class_summary->class_name . '" ';
			$output_html .= 'title="' . $account_class_summary->class_name . '" ';
			$output_html .= 'width="64" height="64"';
			$output_html .= '>';
		}

		return $output_html;
	}

	function buildViewCharacterUrl($player_name, $character_name) {
		$url = CurlHelper::buildCharacterActionRouterUrl();
		$url = CurlHelper::addParameter($url, CHARACTER_ACTION, 'viewCharacter');
		$url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
		$url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);

		return $url;
	}
?>
</div>
</body>
</html>