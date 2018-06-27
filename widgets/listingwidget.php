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
class Amitem_FilterList_Widget extends WP_Widget{

    //set up widget
    public function __construct(){

        $options = array(
            'classname' => 'amitem_filterlist_widget',
            'description' => 'Automeans Filtered List'
        );
        parent::__construct('amitem_filterlist_widget', 'Filtered List', $options);
    }

    //output widget content
    public function widget($args, $instance){
        $page = apply_filters( 'widget_page', $instance['lipage'] ); 
        if(isset($instance['categ'])){
            $_GET['keywords'] = $instance['categ'];
        }
        extract($args);
        echo $before_Widget;
        require_once plugin_dir_path( __FILE__ ).'amitem_obj.php';
        $searchin = unserialize(base64_decode($_GET['searchin']));
        $amitemobj = new amitem_obj();
        $results = $amitemobj->list_results_values($_GET['keywords'],$searchin,$_GET['location']);
        //echo '<div class="am_search_result">';
        $count =0;
        if(empty($results)){
            ?>
            <div class="panel-layout am_search_result">
                <div class="noresult found">
                <h3>Sorry. No results found.</h3>
                </div>
            </div>
            <?php
        }else{
            foreach($results as $result){
                $thumb = '';
                $thumb =   wp_get_attachment_url( get_post_thumbnail_id($result->ID) );
                //$thumb = get_the_post_thumbnail( $result->ID,'thumbnail');
                if(!$thumb) $thumb = WP_PLUGIN_URL.'/am-item/admin/icon.png';
                $otherinfo = get_post_meta($result->ID, '_amitem_details_meta_key', true);
                $thumb = '<img src="'.$thumb.'" width="200" height="200" sizes="(max-width: 300px) 100vw, 300px" >';
                ?>
                <div class="panel-layout am_search_result">
                    <div class="panel-grid panel-has-style">
                        <div class="sd-container hoverable panel-row-style">
                            <div class="panel-grid-cell">
                                <div class="amitems col-md-12">
                                    <a href="<?php echo $instance['lipage'].'?ref='.$otherinfo['ref']; ?>" title="">
                                        <article class="box">
                                            <div class="col-md-4 col-sm-4">
                                                <figure>
                                                    <?php echo $thumb;?>
                                                </figure>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <div class="details">
                                                <strong>
                                                <?php echo ($otherinfo['isverified'] ? '<label class="verified">VERIFIED</label>' :'' );?>
                                                </strong>
                                                    <h4><?php echo $result->post_title;?></h4>
                                                    <div class="row">
                                                        <div class="pricerange-group col-md-12">
                                                        <div class="pricerange-label col-md-2"><span class="smalldesc">Price Range :</span><br></div>
                                                            <?php
                                                            for($a=$otherinfo['pricerange']; $a>0; $a-- ){
                                                                echo ' <div class="pricerange-border col-md-1" ></div>';
                                                            }
                                                            ?>
                                                        </div>
                                                    <div class="col-md-12"><span class="smalldesc">City/Municipality : <?php echo $otherinfo['loccity'].' '.$otherinfo['locprovince']; ?></span></div>
                                                    <div class="col-md-12"><p class="shortdesc"><?php echo $otherinfo['shortdesc'];?></p></div>
                                                    </div>
                                                </div>
                                        </div>
                                        </article>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
        
      //  echo '</div>';
    }

    //output widget form fields
    public function form($instance){
        $id = $this->get_field_id('lipage');
        $name = $this->get_field_name('lipage');
        $label = __('List Result Page:','amitem_filterlist_widget');
        if(isset($instance['lipage']) && !empty($instance['lipage'])){
            $markup = $instance['lipage'];
        }
        $pages = get_pages();
        echo '<label>'.$label.'</label>';
        echo '<select name="'.$name.'" id="'.$id.'">';
        foreach($pages as $page){
            echo '<option value="'.get_page_link( $page->ID ).'" '.selected($instance['lipage'],get_page_link( $page->ID )).' >'.$page->post_title.'</option>';
        }
        echo '</select>';
    }

    //process widget options
    public function update($new_instance, $old_instace){
        $instance = array();
        $instance['lipage'] = '';
        if(isset($new_instance['lipage'])){
            $instance['lipage'] = $new_instance['lipage'];
        }
        return $instance;
    }
    

}    
