<?php

namespace Tu\Academy\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

/**
 * Addressbook API handler class
 */
class Addressbook extends WP_REST_Controller {

    public function __construct(){
        $this->namespace = 'academy/v1';
        $this->rest_base = 'contacts';
    }

    /**
     * Registers the routes for the objects of the addressbook.
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route( 
            $this->namespace,
            '/'.$this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'get_items_permissions_check' ],
                    'args'                => $this->get_collection_params(),
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_item' ],
                    'permission_callback' => [ $this, 'create_item_permissions_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
                ],
                'schema' => $this->get_item_schema(),
            ],
        );

        register_rest_route( 
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                'args' => [
                    'id' => [
                        'description' => __( 'Unique identifier for the object.' ),
                        'type'        => 'integer',
                    ],
                ],
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_item' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => $this->get_collection_params( [ 'default' => 'view' ] ),
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_item' ],
                    'permission_callback' => [ $this, 'update_item_permissions_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'delete_item' ],
                    'permission_callback' => [ $this, 'delete_item_permissions_check' ],
                ],
                'schema' => [ $this, 'get_item_schema' ],
            ]
        );
    }

     /**
     * Get the address, if the ID is valid.
     *
     * @param int $id Supplied ID.
     *
     * @return Object|\WP_Error
     */
    protected function get_contact( $id ) {
        $contact = tu_ac_get_address( $id );

        if ( ! $contact ) {
            return new WP_Error(
                'rest_contact_invalid_id',
                __( 'Invalid contact ID.' ),
                [ 'status' => 404 ]
            );
        }

        return $contact;
    }

    /**
     * Checks if a given request has access to get items.
     *
     * @param [type] $request
     * @return boolean
     */
    public function get_items_permissions_check( $request ) {
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }
        return false;
    }

    /**
     * Checks if a given request has access to get a specific item.
     *
     * @param [type] $request
     * @return void
     */
    public function get_item_permissions_check( $request ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return false;
        }

        $contact = $this->get_contact( $request['id'] );

        if ( is_wp_error( $contact ) ) {
            return $contact;
        }

        return true;
    }

    /**
     * Retrieves one item from the collection.
     *
     * @param WP_REST_Request 
     * @return WP_REST_Response|WP_Error
     */
    public function get_item( $request ){
        $contact  = $this->get_contact( $request['id'] );
        $response = $this->prepare_item_for_response( $contact, $request );

        return rest_ensure_response( $response );
    }

    /**
     * Retrieves a collection of items.
     *
     * @param [type] $request
     * @return void
     */
    public function get_items( $request ) {
        $args = [];
        $params = $this->get_collection_params();

        foreach ($params as $key => $value) {
            if ( isset( $request[ $key ] ) ) {
                $args[ $key ] = $request[ $key ];
            }
        }

        $args[ 'number' ] = $args[ 'per_page' ];
        $args[ 'offset' ] = $args[ 'number' ] *  ( $args[ 'page' ] - 1 );

        unset( $args[ 'per_page' ] );
        unset( $args[ 'page' ] );

        $contacts = tu_ac_get_addresses( $args ) ?: [];

        $data = [];

        foreach ( $contacts as $contact ) {
            $response = $this->prepare_item_for_response( $contact, $request );
            $data[]   = $this->prepare_response_for_collection( $response );
        }

        $total     = tu_ac_addresses_count();
        $max_pages = ceil( $total / (int) $args[ 'number' ] );
        
        $response  = rest_ensure_response( $data );

        $response->header( 'X-WP-Total', (int) $total );
        $response->header( 'X-WP-TotalPages', (int) $max_pages );

        return $response;
        
    }

    /**
     * Retrieves the item's schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema() {
        if($this->schema){
            return $this->add_additional_fields_schema( $this->schema );
        }

        $schema = [
            'schema'     => 'https: //json-schema.org/draft-04/schema#',
            'title'      => 'contact',
            'type'       => 'object',
            'properties' => [
                'id' => [
                    'description' => __( 'Unique identifier for the object' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                    'readonly'    => true,
                ],
                'name' => [
                    'description' => __( 'Name of the contact' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'required'    => true,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field'
                    ],
                ],
                'address' => [
                    'description' => __( 'Address of the contact' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_textarea_field'
                    ],
                ],
                'phone' => [
                    'description' => __( 'Phone number of the contact' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field'
                    ],
                ],
                'date' => [
                    'description' => __( 'The date, the object was published in sites time' ),
                    'type'        => 'string',
                    'format'      => 'date-time',
                    'context'     => [ 'view' ],
                    'readonly'    => true
                ],
            ],
        ];

        $this->schema = $schema;
        return $this->add_additional_fields_schema( $this->schema );
    }

    /**
     * Retrieves the query params for the collections.
     *
     * @return array
     */
    public function get_collection_params() {
        $params = parent::get_collection_params();

        unset($params['search']);

        return $params;
    }

    /**
     * Prepares the item for the REST response.
     *
     * @param [mixed] $item
     * @param [object] $request
     * @return void
     */
    public function prepare_item_for_response( $item, $request ) {
        $data = [];
        $fields = $this->get_fields_for_response( $request );
        
        if ( in_array( 'id', $fields, true ) ) {
            $data[ 'id' ] = (int) $item->id;
        }

        if ( in_array( 'name', $fields, true ) ) {
            $data[ 'name' ] = $item->name;
        }

        if ( in_array( 'phone', $fields, true ) ) {
            $data[ 'phone' ] = $item->phone;
        }

        if ( in_array( 'address', $fields, true ) ) {
            $data[ 'address' ] = $item->address;
        }

        if ( in_array( 'date', $fields, true ) ) {
            $data[ 'date' ] = mysql_to_rfc3339( $item->created_at );
        }
        
        $context = ! empty( $request[ 'context' ] ) ? $request[ 'context' ] : 'view';
        $data = $this->filter_response_by_context( $data, $context );

        $response = rest_ensure_response( $data );
        $response->add_links( $this->prepare_links( $item ) );

        return $response;

    }

    /**
     * prepare links for the request
     *
     * @param [type] $item
     * @return array links for the given post
     */
    public function prepare_links( $item ) {
        $base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

        $links = [
            'self' => [
                'href' => rest_url( trailingslashit( $base ) . $item->id ),
            ],
            'collection' => [
                'href' => rest_url( $base ),
            ],
        ];

        return $links;
    }

    /**
     * Checks if a given request has access to get items.
     *
     * @param [type] $request
     * @return boolean
     */
    public function create_item_permissions_check( $request ) {
        return $this->get_items_permissions_check( $request );
    }

    /**
     * Checks if a given request has access to update a specific item.
     *
     * @param [type] $request
     * @return void
     */
    public function update_item_permissions_check( $request ){
        return $this->get_item_permissions_check( $request );
    }

    /**
     * Prepares one item for create or update operation.
     *
     * @param [type] $request
     * @return void
     */
    protected function prepare_item_for_database( $request ){
        $prepared = [];

        if ( isset( $request[ 'name' ] ) ) {
            $prepared[ 'name' ] = $request[ 'name' ];
        }
        if ( isset( $request[ 'address' ] ) ) {
            $prepared[ 'address' ] = $request[ 'address' ];
        }
        if ( isset( $request[ 'phone' ] ) ) {
            $prepared[ 'phone' ] = $request[ 'phone' ];
        }

        return $prepared;
    }

    /**
     * Creates one item from the collection.
     *
     * @param WP_REST_Request
     * @return WP_REST_Response|WP_Error
     */
    public function create_item( $request ){
        $contact = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $contact ) ) {
            return $contact;
        }

        $contact_id = tu_ac_insert_address( $contact );

        if ( is_wp_error( $contact_id ) ) {
            $contact_id->add_data( [ 'status' => 400 ] );
            return $contact_id;
        }

        $contact = $this->get_contact( $contact_id );
        $response = $this->prepare_item_for_response( $contact, $request );

        $response = rest_ensure_response( $response );

        $response->set_status( 201 );
        $response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $contact_id ) ) );

        return $response;
    }

     /**
     * Updates one item from the collection.
     *
     * @param \WP_REST_Request $request
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function update_item( $request ) {
        $contact  = $this->get_contact( $request['id'] );
        $prepared = $this->prepare_item_for_database( $request );

        $prepared = array_merge( (array) $contact, (array) $prepared );

        tu_ac_insert_address( (array) $prepared );

        $contact  = $this->get_contact( $request['id'] );
        $response = $this->prepare_item_for_response( $contact, $request );

        return rest_ensure_response( $response );

    }

    /**
     * Checks if a given request has access to delete a specific item.
     *
     * @param WP_REST_Request
     * @return true|WP_Error True if the request has access to delete the item, WP_Error object otherwise.
     */
    public function delete_item_permissions_check( $request ){
        return $this->get_item_permissions_check( $request );
    }

    /**
     * Deletes one item from the collection.
     *
     * @param WP_REST_Request
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item( $request ){
        $contact  = $this->get_contact( $request[ 'id' ] );
        $previous = $this->prepare_item_for_response( $contact, $request );

        tu_ac_delete_address( $request[ 'id' ] );

        $data = [
            'deleted' => true,
            'previous' => $previous,
        ];
        return $data;
    }

}