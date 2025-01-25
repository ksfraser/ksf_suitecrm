<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrm_accounts.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/


/*

array ->call
->logiN			Do we get an exception on bad credentials?
string set_entry
string set_relationshiP
string update		calls set_entry
string create		calls set_entry
string upload_file	calls call
null get_entry_list	no native throws
null search		no native throws.  Looks incomplete.
arrayfunction objectvars2array()
array get_field_list	calls call.
bool setNoteAttachment

set/geT
*/
/*
 function __construct( $url, $username, $password )
        function create()
    function update()
        function __construct( $url, $username, $password )
        function convert( $crm_persons, $debtors_master )
*/

class test_suitecrm extends suitecrm_accounts
{
	protected $connection;
	protected $loggedin;
	protected $name;			//!< name
	protected $description;			//!< text
	protected $account_type;		//!< dropdown
	protected $industry;			//!< dropdown
	protected $annual_revenue;		//!< text
	protected $phone_fax;			//!< phone
	protected $billing_address_street;	//!< text
	protected $billing_address_city;	//!< text
	protected $billing_address_postalcode;	//!< text
	protected $billing_address_state;	//!< text
	protected $billing_address_country;	//!< text
	protected $rating;			//!< text
	protected $phone_office;		//!< phone
	protected $phone_alternate;		//!< phone
	protected $website;			//!< URL
	protected $ownership;			//!< text
	protected $employees;			//!< text
	protected $ticker_symbol;		//!< text
	protected $shipping_address_street;	//!< text
	protected $shipping_address_city;	//!< text
	protected $shipping_address_postalcode;	//!< text
	protected $shipping_address_state;	//!< text
	protected $shipping_address_country;	//!< text
	protected $email1;			//!< text
	protected $sic_code;			//!< text
	protected $jjwg_maps_address_c;		//!< text
	protected $jjwg_maps_geocode_c;		//!< text
	protected $jjwg_maps_lat_c;		//!< float
	protected $jjwg_maps_lng_c;		//!< float

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
    /**//**
     *
     *
     * */
	function test_create_account()
	{
		$this->set( "module_name", "Accounts" );
		$this->set( "name_value_list" ,
				array( 
					array("name" => "name", "value" => "Test Account " . date( 'YmdHis' ) ) ,
					array("name" => "billing_address_street", "value" => "747 Windridge Road SW") ,
					array("name" => "billing_address_city", "value" => "Airdrie") ,
					array("name" => "billing_address_state", "value" => "Alberta") ,
					array("name" => "billing_address_country", "value" => "Canada") ,
					array("name" => "assigned_user_name", "value" => "admin") ,
					array("name" => "assigned_user_id", "value" => $this->user_id ) ,
					array("name" => "phone_office", "value" => "587-600-0013") ,
					array("name" => "phone_fax", "value" => "403-912-1654") ,
				) 
		);
		$this->create();
		echo "<pre>";
		//var_dump( $this->result );
    		print_r($this->result);
    		echo "</pre>";
	}
    /**//**
     *
     *
     * */
	function test_create_account2()
	{
		$this->set( "module_name", "Accounts" );
		//$this->set( "name_value_list" ,
		$vars = 		array( 
					array("name" => "name", "value" => "Test Account " . date( 'YmdHis' ) ) ,
					array("name" => "billing_address_street", "value" => "747 Windridge Road SW") ,
					array("name" => "billing_address_city", "value" => "Airdrie") ,
					array("name" => "billing_address_state", "value" => "Alberta") ,
					array("name" => "billing_address_country", "value" => "Canada") ,
					array("name" => "assigned_user_name", "value" => "admin") ,
					array("name" => "assigned_user_id", "value" => $this->user_id ) ,
					array("name" => "phone_office", "value" => "587-600-0013") ,
					array("name" => "phone_fax", "value" => "403-912-1654") ,
				);
		foreach( $vars as $val )
		{
			//Should be a 2d array
			$this->set( $val["name"], $val["value"], false );
		}
		//This should create the NVL!
		$this->create();
		echo "<pre>";
		//var_dump( $this->result );
    		print_r($this->result);
    		echo "</pre>";
	}
	/*******************************************//**
	 * Update an account record
	 *
	 * Requires that the ID field has been set previously
	 * by either a search or a create.
	 *
	 * */
	function test_update_account()
	{
		$this->id = $this->result->id;
		$this->unset( "name_value_list" );
		$this->set( "name", "_Test Account " . $this->id );
		$this->update();
	}
    /**//**
     *
     *
     * */
	function search2( $searchstring )
	{
		$this->set( "search_string", $searchstring ); 
		$this->set( "search_modules_array", array( "Accounts", "Contacts", "Leads" ) ); 
		$this->set( "search_offset", 0 ); 
		$this->set( "search_max_results", 25); 
		//$this->set( "search_return_fields_array", array( "id", "name", "assigned_user" ) ); 
		$this->set( "search_return_fields_array", array( ) ); //Return ALL fields
		//$this->unset( "search_return_fields_array" ); 
		$this->set( "unified_search_only", false ); 
		$this->set( "search_favorites_only", false ); 
		$res = parent::search(); 
		//var_dump( $res );
	}
	function search3( $searchstring )
	{
		$this->set( "search_string", $searchstring ); 
		$this->set( "search_modules_array", array( "Accounts" ) ); 
		$this->set( "search_offset", 0 ); 
		$this->set( "search_max_results", 25); 
		//$this->set( "search_return_fields_array", array( "id", "name", "assigned_user" ) ); 
		$this->set( "search_return_fields_array", array( ) ); //Return ALL fields
		//$this->unset( "search_return_fields_array" ); 
		$this->set( "unified_search_only", false ); 
		$this->set( "search_favorites_only", false ); 
		$res = parent::search(); 
		//var_dump( $res );
	}
	function search4( )
	{
		//THis search/filter by ID doesn't work :(
		$this->set( "search_string", ""  ); 
		//$this->set( "search_string", "d86c0b70-9ed2-85d8-233b-62c741d02848"  ); 
		$this->set( "search_modules_array", array( "Accounts" ) ); 
		$this->set( "search_offset", 0 ); 
		$this->set( "search_max_results", 25); 
		//$this->set( "search_return_fields_array", array( "id", "name", "assigned_user" ) ); 
		$this->set( "search_return_fields_array", array( ) ); //Return ALL fields
		//$this->unset( "search_return_fields_array" ); 
		$this->set( "unified_search_only", false ); 
		$this->set( "search_favorites_only", false ); 
		$this->set( "search_id", "d86c0b70-9ed2-85d8-233b-62c741d02848" );
		$this->set( "id", "d86c0b70-9ed2-85d8-233b-62c741d02848" );
		$res = parent::search(); 
		//var_dump( $res );
	}

}
$params = array( "url"=> $url, "username" => $username, "password" =>$password, "module_name" => "login" );
$test = new test_suitecrm( null, $params );
$test->login();
$test->set_debug( 1 );
//$test->test_create_account();
//$test->test_create_account2();
//$test->test_update_account();
$test->unset( "id" );
$test->search2( "%ev%" );
//$test->search2( "Test%" );
/*
$test->search4( "Test%" );
$test->get_fields_from_search_results();
var_dump( $test );
 */
//$test->search2( "%Rollerman%" );
//$test->search2( "%Account%" );
//
/*
$test->unset( "id" );
$test->search3( "%Fras%" );
$test->get_fields_from_search_results();
var_dump( $test );
 /**/

