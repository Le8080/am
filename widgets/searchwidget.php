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
        $page = apply_filters( 'widget_page', $instance['page'] );
        $search = apply_filters( 'widget_page', $instance['search'] );
        extract($args);
        echo $before_Widget;
        require_once plugin_dir_path( __FILE__ ).'amitem_obj.php';
        $amitemobj = new amitem_obj();
        $datas = $amitemobj->get_values();
        if(!$instance['page']){
            echo '<label class="Warning>No search redirection Link</label>';
        }
       
        if(!empty($search)){
            $search = base64_encode(serialize($search)); 
        }else {
            $ptags = get_terms('Automeanstag',array('parent'=>0));
            $pcateg = get_terms('AutomeansCateg',array('parent'=>0));
            $search[] = 'post_title';
            if(!empty($ptags)){
                foreach($ptags as $p){
                    $search[] = $p->term_id;
                }
            }
            if(!empty($pcateg)){
                foreach($pcateg as $p){
                    $search[] = $p->term_id;
                }
            }
            $search =base64_encode(serialize($search)) ;
        }
        ?>
        <!-- <div class="amitem-search search-wrap">
        <form role="search" method="get" id="amitem_search" action="<?php echo $instance['page']; ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="row"><input type="text" name="keywords" placeholder="Enter Keywords (Car wash, Shop, Shop Name)" class="search-field amitemsearch"></div>
                    </div>
                    <input type="hidden" name="searchin"  value='<?php echo esc_html($search);?>'>

                    <div class="col-md-6">
                        <div class="row">
                            <div id="locationField">
                            <input id="autocomplete" placeholder="Enter Location" class="amitemsearch"
                                onFocus="geolocate()" type="text" name="location"></input>
                            </div>
                            <button class="search-icon" type="submit"></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div> -->
        <div class="amitem-search search-wrap">
            <nav class="col s12 am-search am-center-elem">
                <div class="nav-wrapper">
                <form role="search" method="get" id="amitem_search" action="<?php echo $instance['page']; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="row"><input type="text" name="keywords" placeholder="Enter Keywords (Car wash, Shop, Shop Name)" class="search-field amitemsearch"></div>
                            </div>
                            <input type="hidden" name="searchin"  value='<?php echo esc_html($search);?>'>
        
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="locationField">
                                    <input id="autocomplete" placeholder="Enter Location" class="amitemsearch"
                                        onFocus="geolocate()" type="text" name="location"></input>
                                    </div>
                                    <button class="label-icon search-am" type="submit"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </nav>
        </div>
    <?php
    }
    //output widget form fields
    public function form($instance){
        $id = $this->get_field_id('page');
        $name = $this->get_field_name('page');
        $searchid = $this->get_field_id('search');
        $searchname= $this->get_field_name('search');
        $label = __('Automeans Search Settings:','amitem_search_widget');
        if(!isset($instance['page']))
            $instance['page'] = '';
        
        if(!isset($instance['search']))
            $instance['search'] = array();
        if(isset($instance['page']) && !empty($instance['page'])){
            $markup = $instance['page'];
        }
       
        $pages = get_pages();
        echo '<h2>'.$label.'</h2>';
        echo '<p><h3><label>Search Redirection Page :</h3></label>';
        echo '<select name="'.$name.'" id="'.$id.'">';
        foreach($pages as $page){
            echo '<option value="'.get_page_link( $page->ID ).'" '.selected($instance['page'],get_page_link( $page->ID )).' >'.$page->post_title.'</option>';
        }
        echo '</select></p>';
    
        echo '<p><h3><label>Automeans Field to Look up</label></h3></p>';
        echo '<input type="checkbox" name="'.$searchname.'[]" id="'.$id.'[]" value="post_title"  '.(in_array('post_title',$instance['search']) ?'checked="checked"': '').'> Automeans Name';
        $pcateg = get_terms('AutomeansCateg',array('parent'=>0));
        foreach($pcateg as $p){
            echo '<p><input type="checkbox" name="'.$searchname.'[]" id="'.$id.'[]" value="'.$p->term_id.'" 
            '.(in_array($p->term_id,$instance['search']) ?'checked="checked"': '').'>'.$p->name.'</p>';
        }
        $ptags = get_terms('Automeanstag',array('parent'=>0));
        foreach($ptags as $p){
            echo '<p><input type="checkbox" name="'.$searchname.'[]" id="'.$id.'[]" value="'.$p->term_id.'"
            '.(in_array($p->term_id,$instance['search']) ?'checked="checked"': '').'>'.$p->name.'<p>';

        }


    }

    //process widget options
    public function update($new_instance, $old_instace){
        $instance = array();
        $instance['page'] = '';
        if(isset($new_instance['page'])){
            $instance['page'] = $new_instance['page'];
        }
        if(isset($new_instance['search'])){
            $instance['search'] = $new_instance['search'];
        }
        return $instance;
    }
    

}    
