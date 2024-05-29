<?php
/**
 * Plugin Name: Gear Product Button REL
 * Description: This plugin will inject rel="sponsor" into those buttons in posts whose class is sponsor
 * Version: 1.0
 * Author Name: WingMan
 */

add_action(
	'plugins_loaded',
	function () {
		add_filter('the_content', 'add_rel_attribute_to_gear_buttons');
	}
);

function add_rel_attribute_to_gear_buttons($content) {
    if (has_category('gear')) {
        $content = get_the_content();
        if(!empty($content)) {
            $dom = new DOMDocument();
            $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            libxml_clear_errors(); 
            $xpath = new DOMXPath($dom);
            $anchorTags = $xpath->query("//div[contains(@class, 'sponsored')]//a[contains(@class, 'wp-element-button')]");

            foreach ($anchorTags as $anchorTag) {
                $anchorTag->setAttribute('rel', 'sponsored');
            }

            // Remove self-closing tags from the generated HTML
            $html = '';
            foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
                $html .= $dom->saveHTML($node);
            }

            // Update the content with the modified HTML
            $content = $html;
        }
    }

    return $content;
}
