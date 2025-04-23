<?php

require_once 'Log.php'; // Include PEAR Log module
require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class SuitecrmAopCaseUpdates extends suitecrmBase
{
	var $id;
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
	
    function __construct($debug_level = PEAR_LOG_DEBUG, $param_arr )
    {
	    parent::__construct( $debug_level, $param_arr );
	    $this->set( "module_name", "AOP_Case_Updates" );
    }
	function create()
	{
		parent::create();		
		/*
		echo "\r\n";
		echo "*****************************";
			echo "\r\n";
		echo "Document created: \n\r";
		var_dump( $this->result );
		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		/**/
		 /*

		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		 /**/
	
	}

}



