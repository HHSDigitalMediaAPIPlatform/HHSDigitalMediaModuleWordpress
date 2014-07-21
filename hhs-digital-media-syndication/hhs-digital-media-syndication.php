<?php

/*
Plugin Name: HHS Digital Media Syndication
Description: This is a Wordpress module that can be used to search and embed HHS Digital Media content. It has been built and tested on Wordpress 3.9.1.
Author: David Cummo

Version: 1.0
*/

function hhsSyndGetEmbedCode($url, $id, $contentid, $hidetitle, $hidedescription) {
    

    $embed_code = '<script type="text/javascript">
                    //'.$hidetitle.'
                    function mediaCallback'.$id.'(response) {
                        if (response && response.results) {
                            jQuery(\'#'.$id.'\').html(response.results.content);';
    
    if ($hidetitle == "true") {
        $embed_code .= '    jQuery("span[id=\'cdc_title_' . $contentid . '\']").hide();';
        $embed_code .= '    jQuery("#' . $id . ' h1").hide();';
    }
    if ($hidedescription == "true") {
        $embed_code .= '    jQuery(\'p[id="cdc_description_' . $contentid . '"]\').hide();';
    }

    $embed_code .= '    }
                    }
                    jQuery(document).ready(function() {
                        jQuery.ajaxSetup({cache:false});
                        jQuery.ajax({
                            url: "'.$url.'",
                            dataType: "jsonp",
                            success: mediaCallback'.$id.',
                            error: function(xhr, ajaxOptions, thrownError) {}
                        });
                    });
                </script>';
    return $embed_code;
}

function hhsSyndGetEmptyDiv($id) {
    $embed_div = '<div id="';
    $embed_div .= $id;
    $embed_div .= '"></div>';
    return $embed_div;
}

function hhsSyndication( $atts, $content = null ) {  
    extract(shortcode_atts(array('url' => '', 'contentid' => '', 'hidetitle' => '', 'hidedescription' => ''), $atts, 'hhs-syndication'));
    $id = uniqid('mod_content_synd_', false);

    $output = hhsSyndGetEmbedCode($url, $id, $contentid, $hidetitle, $hidedescription );
    $output .= hhsSyndGetEmptyDiv($id);

    return $output;
}  

add_shortcode('hhs-syndication', 'hhsSyndication'); 

function add_hhssynd_button() {
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
   {
     add_filter('mce_external_plugins', 'hhsSynd_add_plugin');
     add_filter('mce_buttons', 'hhsSynd_register_button');
   }
}
add_action('init', 'add_hhssynd_button');

function hhsSynd_register_button($buttons) {
   array_push($buttons, "hhssynd");
   return $buttons;
}

function hhsSynd_add_plugin($plugin_array) {
    $plugin_array['hhssynd'] = plugins_url( 'hhs-digital-media-syndication/customcodes.php?pluginsurl='.urlencode(plugins_url()) , dirname(__FILE__) );
   return $plugin_array;
}

?>