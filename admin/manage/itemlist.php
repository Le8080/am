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


//am list table - admn
class AM_item_table extends WP_LIST_Table{
   var $test_data = array(
        array('cb'=>'<input type="checkbox" />','id'=>1, 'item'=>'Test','location'=>'Location'),
        array('cb'=>'<input type="checkbox" />','id'=>2, 'item'=>'Test2','location'=>'Location'),
    
    );
    //get columns
    public function get_columns(){
        $columns = array(
            'cb'=> '<input type="checkbox">',
            'id'=> 'ID',
            'item'=> 'Item',
            'location'=> 'Location'
        );
        return $columns;
    }
    //
    public function column_default( $item, $column_name ) {
        switch( $column_name ) { 
          case 'cb':
          case 'id':
          case 'item':
          case 'location':
            return $item[ $column_name ];
          default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
      }
    //prepare items
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        usort( $this->test_data, array( &$this, 'usort_reorder' ) );
        $this->items = $this->test_data;
    }
    //
    public function get_sortable_columns() {
        $sortable_columns = array(
          'id'  => array('id',true),
          'item' => array('item',true),
          'location'   => array('location',true)
        );
        return $sortable_columns;
    }
    //sort data
    public function usort_reorder( $a, $b ) {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'item';
        // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }
    //bulk actions
    public function get_bulk_actions() {
        $actions = array(
          'delete'    => 'Delete'
        );
        return $actions;
    }
    //
    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', $item['ID']
        );    
    }
      
}


