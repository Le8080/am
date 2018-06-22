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

class amitem_obj{
    var $id;
    public function __construct($id = NULL){
        if($id)
            $this->id = $id;

    }
    public function get_values(){
       global $wpdb;
        $pms = $this->get_postmeta_data();
        $result = array();
        foreach ($pms as $p =>$pm){
          $result[$pm->post_id] = array_merge(get_post_meta($pm->post_id, '_amitem_details_meta_key', true),
                                    array('meta_id'=>$pm->meta_id,
                                        'meta_key'=>$pm->meta_key,
                                        'post_date'=>$pm->post_date,
                                        'post_content'=>$pm->post_content,
                                        'post_title'=>$pm->post_title,
                                        'post_name'=>$pm->post_name)
                                   );
        }
        return $result;
    }
    public function get_postmeta_data(){
        global $wpdb;
        $rs = $wpdb->get_results("
        SELECT pm.*,p.*
        FROM {$wpdb->posts} p join {$wpdb->postmeta} pm on pm.post_id=p.ID
        where p.post_type='amitem' and pm.meta_key ='_amitem_details_meta_key'");
        return $rs;
    }
    public function get_item_obj_pt($output = ''){

       return $obj = get_post_type_object( 'amitem' ,$output);
    }
    public function list_results_values( $key = '',$filter = array() ,$location = 'All') { 
        global $wpdb;
        if(empty($filter)){
            $filter = array('post_title');
        }
        $i = 0;
        $fii = '';
        $taxonomies = '';
        foreach($filter as $fi){
            if(preg_match('/(post)/',$fi)){
                if(!$i)
                $fii = " ".$fi." LIKE '%".$key."%'";
                else $fii .= " OR ".$fi." LIKE '%".$key."%'";
                $i++;
            }else{
                $p = get_terms('Automeanstag');
                $j = get_terms('AutomeansCateg');
                $customtax = array_merge($p,$j);
                if(!empty($customtax)){
                    foreach($customtax as $t){
                        if(stripos($t->name,$key) !== false){
                            $taxonomies .= $t->term_id.',';
                        }
                    } 
                }

            }
        }
        $taxonomies = trim($taxonomies,',');
        if(!empty($taxonomies)){
            $tax = ' p.ID IN ( SELECT object_id FROM automea.wp_term_relationships where term_taxonomy_id IN ('.$taxonomies.'))';
        }
        $i = 0;
        $locations = '';  
        if($location == 'All' OR !$location){
            $location = ' ';
        }else{
            $location = explode(',', $location);
            foreach($location as $lo){
                $lo = preg_replace('/\s+/', '', $lo);
                if(!$i)
                    $locations .= "(pm.meta_value LIKE '%\"".$lo."\"%'";
                else $locations .= "AND pm.meta_value LIKE '%\"".$lo."\"%'";

                $i++;
            }
            $locations = ' AND '. $locations.')';
        }
      
        if($fii AND $tax){
            $query = 'AND ('.$fii.' OR '.$tax.')';
        }else if($fii AND !$tax){
            $query = 'AND ('.$fii.')';
        }else if(!$fii AND $tax){
            $query = 'AND ('.$tax.')';
        }
        $sql ="SELECT p.*
                FROM {$wpdb->posts} p 
                      left join {$wpdb->postmeta} as pm on pm.post_id = p.ID
                where p.post_type='amitem' AND p.post_status ='publish'  AND pm.meta_key = '_amitem_details_meta_key' ".$query.$locations; 
              
        $rs = $wpdb->get_results($sql);
        return $rs;

    }
    public function get_amitem_obj($value = '',$field = '',$iscustom = 0){
        global $wpdb;
        $filter = '';
        if($value AND $field){
            if(!$iscustom){
                if(is_string($value))
                        $value = '"'.$value.'"';
                $filter = ' AND '.$field.' ='.$value;
            }else{
                $filter = ' AND pm.meta_value LIKE "%'.$value.'%"';
            }
        }
        $sql = "SELECT pm.*,p.*
                FROM {$wpdb->posts} p join {$wpdb->postmeta} pm on pm.post_id=p.ID
                where p.post_type='amitem' and pm.meta_key ='_amitem_details_meta_key' and post_status ='publish' ".$filter."";
        
        $rs = $wpdb->get_results($sql);
        foreach($rs as $r){
            $meta  = get_post_meta($r->post_id, '_amitem_details_meta_key', true);
            if($value == $meta[$field]){
                return $r;
            }

        }
        return;
    }

}