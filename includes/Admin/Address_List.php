<?php

namespace Tu\Academy\Admin;

use WP_List_Table;

if(!class_exists('WP_List_Table')){
    require_once ABSPATH .'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List Table handle
 */
class Address_List extends \WP_List_Table {
    function __construct(){
        parent::__construct([
            'singular' => 'Contact',
            'plural'   => 'Contacts',
            'ajax'     => false
        ]);
    }

    public function get_columns(){
            return [
            'cb'         => '<input       type = "checkbox"/>',
            'name'       => __('Name',    'tu-academy'),
            'address'    => __('Address', 'tu-academy'),
            'phone'      => __('Phone',   'tu-academy'),
            'created_at' => __('Date', 'tu-academy'),
        ];
    }
    /**
     * Message to show if no designation found
     */
    public function no_items(){
        _e('No Address Found', 'tu-academy');
    }


    /**
     * Get sortable columns
     */
    public function get_sortable_columns(){
        
        $sortable_columns = [
            'name'       => ['name', true],
            'created_at' => ['created_at', true],
        ];
        return $sortable_columns;
    }

    /**
     * Default column values
     */
    protected function column_default($item, $column_name){
        
        switch ($column_name) {
            case 'created_at':
                return wp_date( get_option( 'date_format' ), strtotime( $item->created_at ) );
            default:
                return isset($item->$column_name) ? $item->$column_name : '';
        }

    }

    /**
     * Render the name column
     */
    public function column_name($item){

        $actions = [];

        $actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=tu-academy&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'tu-academy' ), __( 'Edit', 'tu-academy' ) );
        // $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=tu-ac-delete-address&id=' . $item->id ), 'tu-ac-delete-address' ), $item->id, __( 'Delete', 'tu-academy' ), __( 'Delete', 'tu-academy' ) );
        $actions['delete'] = sprintf( '<a href="#" class="submitdelete" data-id="%s">%s</a>', $item->id, __( 'Delete', 'tu-academy' ) );

        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a>%3$s', admin_url('admin.php?page=tu-academy&action=view&id='.$item->id), $item->name, $this->row_actions($actions)
        );
    }

    /**
     * Render the cb column
     */
    protected function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="address_id[] value="%d"/>', $item->id
        );
    }

    /**
     * Prepare the address items
     */
    public function prepare_items(){
        $column   = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$column, $hidden, $sortable];
        
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if(isset($_REQUEST['orderby']) && isset($_REQUEST['order'])){
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'];
        }

        $this->items = tu_ac_get_addresses($args);
        
        $this->set_pagination_args([
            'total_items' => tu_ac_addresses_count(),
            'per_page'    => $per_page
        ]);

    }

     /**
     * Set the bulk actions
     *
     */
    public function get_bulk_actions() {
        $actions = array(
            'trash'  => __( 'Move to Trash', 'tu-academy' ),
        );

        return $actions;
    }



}