<?php
defined( 'ABSPATH' ) || exit;


function wpcondi_readmein8($string){    
    $string = esc_html($string);
        // Extracting all text and URL pairs from the string
        preg_match_all('/\[([^\]]+)\]\(([^)]+)\)/', $string, $matches, PREG_SET_ORDER);
       
        if(is_array($matches)){
            foreach ($matches as $match) {
                $fullMatch = $match[0];
                $text = $match[1];
                $url = $match[2];

                // Creating HTML anchor link
                $htmlLink = "<a target=\"_blank\" href=\"$url\">$text</a>";

                // Replacing the original string with HTML link
                $string = str_replace($fullMatch, $htmlLink, $string);
            }
        }
        
    return $string;
}