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
class Amitem_Result_Widget extends WP_Widget{

    //set up widget
    public function __construct(){

        $options = array(
            'classname' => 'amitem_result_widget',
            'description' => 'Automeans Details'
        );
        parent::__construct('amitem_result_widget', 'Automeans Details', $options);
    }

    //output widget content
    public function widget($args, $instance){
        $page = apply_filters( 'widget_page', $instance['lidisplay'] );
        extract($args);
        echo $before_Widget;
        
        require_once plugin_dir_path( __FILE__ ).'amitem_obj.php';
        $amitemobj = new amitem_obj();
        $value = $_GET['ref'];
        $result = $amitemobj->get_amitem_obj($value,'ref',1);
        if(!empty($result)){
            $otherinfo = get_post_meta($result->post_id, '_amitem_details_meta_key', true);
            $re = (array)$result;
            $res = array_merge($otherinfo,$re);
        }
        switch ($page){
            case 'mobilenumber' : $show ='[wpfa icon=mobile size=2x] <span>'.$res[$page].'</span>'; break;
            case 'phonenumber' : $show ='[wpfa icon=phone size=3x] <span>'.$res[$page].'</span>'; break;
            case 'emailaddress' : $show ='[wpfa icon=envelope size=3x] <span>'.$res[$page].'</span>'; break;
            case 'emailaddress' : $show ='[wpfa icon=envelope size=3x] <span>'.$res[$page].'</span>'; break;
            default : $show = ' '.$res[$page];
            break;
        }
        

        echo $page;
        ?>
        
        <?php
    }

    //output widget form fields
    public function form($instance){
        $id = $this->get_field_id('lidisplay');
        $name = $this->get_field_name('lidisplay');
        $label = __('Display Widget Details:','amitem_result_widget');
        if(isset($instance['lidisplay']) && !empty($instance['lidisplay'])){
            $markup = $instance['lidisplay'];
        }
        $info = amitem_get_all_object();
        echo '<h1>Display Auotomean info</h1> ';
        echo '<h2>Use the shortcode below to show automean details</h2> ';
        echo '<h3>Ex [amitem item="post_title"] </h3> ';
        
        $nem = '';
        foreach($info as $fo){
                $nem .= $fo.', ';
        }
        echo "<p><span>Available item name:</span> $nem</p>";
        // echo '<select name="'.$name.'" id="'.$id.'">';
        //     echo '<option value="" >Select Item</option>';
        // foreach($info as $i){
        //     echo '<option value="'.$i.'" '.selected($instance['lidisplay'], $i).' >'.$i.'</option>';
        // }
   
        // echo '</select>';
        echo '<textarea name="'.$name.' id="'.$id.'">'.$instance['lidisplay'].'</textarea>';
        

    }

    //process widget options
    public function update($new_instance, $old_instace){
        $instance = array();
        $instance['lidisplay'] = '';
        if(isset($new_instance['lidisplay'])){
            $instance['lidisplay'] = $new_instance['lidisplay'];
        }
        return $instance;
    }
    

}    
