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
//managing item object
class manageitem {

    var $id;
    var $post;
    var $postobj;
    
    //construct 
    public function __construct($id = NULL){
        global $post;
        $this->id = $post->id;
        $this->post = $post;
        //if has id prepare the item object

    }

    public function amitem_metabox(){
 
    }
   
    
    public function amitem_details_metabox($post){
        $value = get_post_meta($post->ID, '_amitem_details_meta_key', true);
        wp_nonce_field (basename(__FILE__), 'amitem_details_metabox_key');
        ?>
        <label for="amitem_details_metabox">Details</label>
        <select id="amitem_details_metabox" name="place">
            <option value="2"></option>
        </select> 
        <?php
    }

    public function amitem_save_object(){

    }

}