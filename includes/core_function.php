<?php
/*
Plugin Name: AM Item
Description: Create and View AM Item
Plugin URI: https://automeans.com
Author: Leah Fuentes
Version: 1.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html
*/
if(!defined('ABSPATH')){
    exit;
}
add_action('init', 'register_script');
function register_script() {
    wp_register_style( 'am-jquery',plugin_dir_url(dirname(__FILE__) ). 'public/js/jquery-3.3.1.min.js', false, '1.0.0', 'all');
    wp_register_style( 'am-item_style',plugin_dir_url(dirname(__FILE__) ). 'public/css/am-item.css', false, '1.0.0', 'all');
    wp_register_style( 'am-item_dropdownstyle',plugin_dir_url(dirname(__FILE__) ). 'public/css/bootstrap.min.css', false, '3.2.0', 'all');
    wp_register_script( 'am-item_dropdownjsmin',plugin_dir_url(dirname(__FILE__) ). 'public/js/bootstrap.3.3.2.js', false, '3.3.2', 'all');
    wp_register_script( 'geolocate',plugin_dir_url(dirname(__FILE__) ). 'public/js/geolocate.js', false, '', 'all');
    wp_register_script( 'geolocateapi','https://maps.googleapis.com/maps/api/js?key=AIzaSyCs9eITsZNF8-gIwrNJ0JkTOqNxRdDrMgU&libraries=places&callback=initAutocomplete', false, '', 'all');

}

function amitem_script(){
    $styles = false;
    wp_enqueue_style('am-item',plugin_dir_url(dirname(__FILE__) ).
        'public/css/am-item.css');
    wp_enqueue_style('am-item_dropdownstyle',plugin_dir_url(dirname(__FILE__) ). 'public/css/bootstrap.min.css');
    wp_enqueue_script('am-item_dropdownjsmin',plugin_dir_url(dirname(__FILE__) ). 'public/js/bootstrap.3.3.2.js' ,array(), false, true);
    wp_enqueue_script('geolocate',plugin_dir_url(dirname(__FILE__) ). 'public/js/geolocate.js' ,array(), false, true);
    wp_enqueue_script('geolocateapi','https://maps.googleapis.com/maps/api/js?key=AIzaSyCs9eITsZNF8-gIwrNJ0JkTOqNxRdDrMgU&libraries=places&callback=initAutocomplete' ,array(), false, true);

    
}

add_action('wp_enqueue_scripts','amitem_script');