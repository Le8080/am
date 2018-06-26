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
if (!defined('AMITEM_PLUGIN'))
    define('AMITEM_PLUGIN', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('AMITEM_PLUGIN_URL'))
    define('SEARCHANDFILTER_PLUGIN_URL', WP_PLUGIN_URL . '/' . AMITEM_PLUGIN);

if (!defined('AMITEM_BASENAME'))
    define('AMITEM_BASENAME', plugin_basename(__FILE__));

function amitem_add_menu(){
    $icon_url = WP_PLUGIN_URL.'/am-item/admin/amfavicon.png';
    //register post
    register_post_type('amitem',
        array(
            'labels'      => array(
                'name'          => 'Automeans',
                'singular_name' => 'Automeans',
                'add_new' => 'Add New', 
                'add_new_item' =>'Add New Automeans Item',
                'edit_item' => 'Edit Automeans Item',
                'new_item' => 'Edit New Automeans Item',
                'view_item' => 'View Automeans Item',
                'search_items' => 'Search Automeans Item',
                'not_found' =>  'No Automeans Item Found',
                'not_found_in_trash' =>  'No Automeans Item Found in the trash'
            ),
            'public'      => true,
            'has_archive' => true,
            'menu_icon' => $icon_url,
            'hierarchical'  => 0,
            'can_export'    => 1,
            'supports' => array('title', 'editor', 'comments', 'excerpt', 'custom-fields', 'thumbnail')
        )
    );
    // Category tax.
    $cat_args = array(
        'labels' => array(
            'name' => __( 'Automeans Categories', 'amcateg' ),
            'singular_name' => __( 'Automeans Category', 'amcateg' ),
            'add_new_item' =>'Add New Automeans Category',
            'edit_item' => 'Edit Automeans Category',
            'new_item' => 'Edit New Automeans Category',
            'view_item' => 'View Automeans Category',
            'search_items' => 'Search Automeans Category',
            'not_found' =>  'No Automeans Category Found',
            'not_found_in_trash' =>  'No Automeans Category Found in the trash'
            ),
        'hierarchical' => true,
        'public' => true,
        'supports' => array('title', 'editor', 'custom-fields', 'thumbnail')
    );
    register_taxonomy('AutomeansCateg', 'amitem', $cat_args );
    // Category tax.
    $tag_args = array(
        'labels' => array(
            'name' => __( 'Automeans Tags', 'amtag' ),
            'singular_name' => __( 'Automeans tag', 'amtag' ),
            'add_new_item' =>'Add New Automeans Tag',
            'edit_item' => 'Edit Automeans Tag',
            'new_item' => 'Edit New Automeans Tag',
            'view_item' => 'View Automeans Tag',
            'search_items' => 'Search Automeans Tag',
            'not_found' =>  'No Automeans Tag Found',
            'not_found_in_trash' =>  'No Automeans Tag Found in the trash'
        ),
        'hierarchical' => true,
        'public' => true
    );
    register_taxonomy('Automeanstag', 'amitem', $tag_args );
}
function amitem_metabox_callback(){
    add_meta_box( 'amitem-info',
    'AutoMeans Details',
     'amitem_metabox',
     'amitem',
     'normal',
     'core' 
    );
}
function amitem_get_object(){

    $postobj = new stdClass();
    $postobj->mobilenumber = "mobilenumber";
    $postobj->phonenumber = "phonenumber";
    $postobj->emailaddress = "emailaddress";
    $postobj->locno = "locno";
    $postobj->locstreet = "locstreet";
    $postobj->locbarangay = "locbarangay";
    $postobj->loccity = "loccity";
    $postobj->locprovince = "locprovince";
    $postobj->loccountry = "loccountry";
    // $postobj->longitude = "longitude";
    // $postobj->latitude = "latitude";
    $postobj->map = "map";
    $postobj->website = "website";
    $postobj->facebook = "facebook";
    $postobj->twitter = "twitter";
    $postobj->isverified = "isverified";
    $postobj->pricerange = "pricerange";
    $postobj->ref = "ref";
    $postobj->shortdesc = "shortdesc";
    return $postobj;
}
function amitem_create_metabox(){
    $metaboxes =array('amitem_details_metabox');
    foreach($metaboxes as $metabox){
        add_meta_box(
            $metabox, //unique id of metabox
            'Automeans Details', //title of metaboc
            $metabox,   // callback function
            'amitem', //post type.
            'normal',
            'core' 
        );
    }

}
function amitem_details_metabox($post){
    $postdata = get_post_meta($post->ID, '_amitem_details_meta_key', true);
    wp_nonce_field (basename(__FILE__), 'amitem_details_metabox_nonce');
    ?>
    <input type="checkbox" name="isverified" id="amitem_details_metabox" value="1"<?php checked(@$postdata['isverified'],'1');?>> <label>Is Verified  </label> 
    <div><label>Price Range : </label>
        <select name="pricerange" >
        <option value="1" <?php selected (@$postdata['pricerange'],1); ?>>1</option>
        <option value="2" <?php selected (@$postdata['pricerange'],2); ?>>2</option>
        <option value="3" <?php selected (@$postdata['pricerange'],3); ?>>3</option>
        <option value="4" <?php selected (@$postdata['pricerange'],4); ?>>4</option>
        <option value="5" <?php selected (@$postdata['pricerange'],5); ?>>5</option>
         </select>
    </div>
    <div><label>Unique Identifier* : &nbsp; </label></div><div><input required="required" name="ref" value="<?php echo @$postdata['ref']; ?>" type="text"></div>
    <div>
        <p>Short Description</p>
        <?php echo wp_editor(@$postdata['shortdesc'],'shortdesc');?>
    </div>
    <div>
        <p>Contact Details</p>
        <div><label>Mobile Number : &nbsp; </label></div><div><input name="mobilenumber" value="<?php echo @$postdata['mobilenumber']; ?>"></div>
        <div><label>Phone Number : &nbsp; </label></div><div><input name="phonenumber" value="<?php echo @$postdata['phonenumber']; ?>"></div>
        <div><label>Email address : &nbsp; </label></div><div><input name="emailaddress" value="<?php echo @$postdata['emailaddress']; ?>" type="email"></div>
    </div>
    <div>
        <p>Location</p>
        <label>For accuracy please search the address here</label>
        <div id="locationField">
        <input id="autocomplete" placeholder="Enter your address"
             onFocus="geolocate()" type="text"></input>
        </div>
        <div><label>Street : &nbsp; </label></div><div><input name="locstreet" value="<?php echo @$postdata['locstreet']; ?>" id="route" class="field" ><input name="locno" value="<?php echo @$postdata['locno']; ?>" id="street_number" class="field"></div>
        <div><label>Barangay : &nbsp; </label></div><div><input name="locbarangay" value="<?php echo @$postdata['locbarangay']; ?>" id="sublocality_level_1"class="field" ></div>
        <div><label>City : &nbsp; </label></div><div><input name="loccity" value="<?php echo @$postdata['loccity']; ?>" id="locality" class="field"></div>
        <div><label>Province : &nbsp; </label></div><div><input name="locprovince" value="<?php echo @$postdata['locprovince']; ?>" id="administrative_area_level_2" class="field" ></div>
        <div><label>Country : &nbsp; </label></div><div><input name="loccountry" value="<?php echo @$postdata['loccountry']; ?>" id="country" class="field" ></div>


        <!-- <div><label>Longitude : &nbsp; </label></div><div><input name="longitude" value="<?php echo @$postdata['longitude']; ?>"></div>
        <div><label>Latitude : &nbsp; </label></div><div><input name="latitude" value="<?php echo @$postdata['latitude']; ?>"></div> -->
        <div><label>Map : &nbsp; </label></div><div><textarea name="map" rows="10" cols="80"> <?php echo @$postdata['map']; ?></textarea></div>
     </div>
    <div>
        <p>Social Links</p>
        <div><label>Website :</label></div><div><input name="website" value="<?php echo @$postdata['website']; ?>"></div>
        <div><label>Facebook : </label></div><div><input name="facebook" value="<?php echo @$postdata['facebook']; ?>"></div>
        <div><label>Twitter : </label></div><div><input name="twitter" value="<?php echo @$postdata['twitter']; ?>"></div>
    </div>
    <div>


    </div>
    <?php
}
function amitem_save_metabox()
{  
    global $post;
    $is_autosave = wp_is_post_autosave($post->ID);
    $is_revision = wp_is_post_revision($post->ID);
    $is_valid_none = false;
  
    if(isset($_POST['isverified'])){
        if(wp_verify_nonce ($POST['amitem_details_metabox_nonce'],basename(__FILE__))){
            $is_valid_none = true;
        }
    }
    
    if($is_autosave || $is_revision || $is_valid_none) return;
    $ampost = [];
    $obj = amitem_get_object();
    foreach($obj as $am){
        $ampost[$am]=$_POST[$am];
    }
    if(array_key_exists('ref',$_POST)){
        update_post_meta($post->ID, '_amitem_details_meta_key', $ampost);
    }
}   
function amitem_get_all_object(){
    $obj = amitem_get_object();
    $obj->post_title = 'post_title';
    $obj->post_date = 'post_date';
    $obj->post_content = 'post_content';
    $obj->post_status = 'post_status';
    $obj->post_name = 'post_name';
    $obj->featuredimage = 'featuredimage';
    $obj->featuredimage = 'featuredimageurl';
    $obj->tags = 'tags';
    $obj->category = 'category';
    return $obj;
}
add_action('init', 'amitem_add_menu');
add_action( 'add_meta_boxes', 'amitem_create_metabox' );
add_action( 'save_post', 'amitem_save_metabox' );   


require_once plugin_dir_path( __FILE__ ).'/widgets/searchwidget.php';
require_once plugin_dir_path( __FILE__ ).'/widgets/listingwidget.php';
require_once plugin_dir_path( __FILE__ ).'/widgets/resultwidget.php';
require_once plugin_dir_path( __FILE__ ).'/includes/core_function.php';

function amitem_register_widget(){
    register_widget('Amitem_FilterList_Widget');
    register_widget('Amitem_Search_Widget');
    register_widget('Amitem_Result_Widget');
}
add_action('widgets_init', 'amitem_register_widget');

function amitem_shortcode( $atts ) {
    extract( shortcode_atts( array( 'item' => 'post_title', 'ref' => '' ), $atts ) );
    require_once plugin_dir_path( __FILE__ ).'/widgets/amitem_obj.php';

    if(!$ref && !$_GET['ref'])
     return '';
    
    if(!$ref)
       $ref = $_GET['ref'];  
    $amitemobj = new amitem_obj();
    $result = $amitemobj->get_amitem_obj($ref,'ref',1);
    if(!empty($result)){
        $otherinfo = get_post_meta($result->post_id, '_amitem_details_meta_key', true);
        $re = (array)$result;
        $result = array_merge($otherinfo,$re);
    }
    if($item=='featuredimage'){
       return get_the_post_thumbnail($result['post_id'],'thumbnail');
    }else if($item=='featuredimageurl'){
     return get_the_post_thumbnail_url($result['post_id']);
    }else if($item == 'tags'){
        $parent = get_terms('Automeanstag',array('parent'=>0));
        $data = '';
        foreach($parent as $p){
            $data .='<p>'.$p->name;
            $tags = wp_get_post_terms($result['post_id'],
            'Automeanstag' ,array('parent'=>$p->term_id));
            if(!empty($tags)){
                $tg =' :';
                foreach ($tags as $tag){
                    $tg .= ' '.$tag->name . ',';
                }
            }
            
                $data .= rtrim($tg,',').'</p>';
        }
        if(!empty(wp_get_post_terms($result['post_id'],'Automeanstag')))
            return $data;
    }
    else if($item == 'category'){
        $parent = get_terms('AutomeansCateg',array('parent'=>0));
        $data = '';
        foreach($parent as $p){
            $data .='<p>'.$p->name;
            $tags = wp_get_post_terms($result['post_id'],
            'AutomeansCateg' ,array('parent'=>$p->term_id));
            if(!empty($tags)){
                $tg =' :';
                foreach ($tags as $tag){
                    $tg .= ' '.$tag->name . '<br>';
                }
            }
            
                $data .= rtrim($tg,',').'</p>';
        }
        if(!empty(wp_get_post_terms($result['post_id'],'Automeanstag')))
            return $data;
    }else if($item == 'custompricerange'){
        $pricerange = '';
        for($a=$result['pricerange']; $a>0; $a-- ){
            $pricerange .=  ' <div class="pricerange-border col-md-1" ></div>';
        }
        return $pricerange;
    }
    else{ return $result[$item];}
  
}
function amitem_callsearch($atts){
    global $wp_widget_factory;
    extract(shortcode_atts(array(
        'widget_name' => FALSE,
        'page' => ''
    ), $atts));
    $widget_name = wp_specialchars($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    $args = array(
        'before_widget' => '<div class="box widget scheme-' . $scheme . ' ">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget-title">',
        'after_title'   => '</div>',
    );
    
    ob_start();
    the_widget( $widget_name, $atts, $args ); 
    $output = ob_get_clean();
    
    return $output;

}

add_shortcode( 'amitem', 'amitem_shortcode' );
add_shortcode( 'amsearch', 'amitem_callsearch' );
add_action( 'admin_enqueue_scripts', 'amitem_script' );
