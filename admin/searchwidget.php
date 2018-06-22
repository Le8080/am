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
class Amitem_Search_Widget extends WP_Widget{

    //set up widget
    public function __construct(){

        $options = array(
            'classname' => 'amitem_search_widget',
            'description' => 'Search Automeans Item'
        );
        parent::__construct('amitem_search_widget', 'Search Automeans', $options);
    }

    //output widget content
    public function widget($args, $instance){
        extract($args);
        echo $before_Widget;
        
        require_once plugin_dir_path( __FILE__ ).'amitem_obj.php';
        $amitemobj = new amitem_obj();
        $datas = $amitemobj->get_values();
        ?>
        <form role="search" method="get" id="searchform" action="<?php echo home_url('/');?>">
        <input type="search" name="s">
        <input type="hidden" name="amitemm-search">
        <select name="location">
            
        </select><button>search</button>
        </form>
        <?php
    }

    //output widget form fields
    public function form($instance){

    }

    //process widget options
    public function update($new_instance, $old_instace){

    }
    

}    

function amitem_register_widget(){
    register_widget('Amitem_Search_Widget');
}
add_action('widgets_init', 'amitem_register_widget');
// $get_query_var = apply_filters( 'get_search_query', $get_query_var ); 

// if ( !empty( $get_query_var ) ) { 

// // everything has led up to this point... 

// } 

//add_action('init', 'search_query');

// define the get_search_query callback 
function filter_get_search_query($query ) { 
    // make filter magic happen here... 
    $query->set('post_type', array('amitem'));
    return $query;
}; 
         
// add the filter 
//add_filter( 'get_search_query', 'filter_get_search_query', 10, 1 ); 
add_action( 'pre_get_posts', 'filter_get_search_query' );
