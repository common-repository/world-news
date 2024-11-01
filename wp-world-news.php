<?php
/**
 * Plugin name:World news plugin
 * Version: 1.0
 * Description: Show news from world famouse news portal.
 * Tags: News,sports,weather
 * Author: RFSOFTLAB
 * License: GPLv2 or later
 */

defined('ABSPATH') or die("Access denied");

include (plugin_dir_path(__FILE__).'/inc/WorldNews.php');

function WorldNewsCSS(){
    wp_enqueue_style('worldnewsCss',plugins_url('/css/worldNews.css',__FILE__));
}
add_action('init','WorldNewsCSS');


