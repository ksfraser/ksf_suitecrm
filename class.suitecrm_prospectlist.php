<?php

require_once( 'class.suitecrm.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class suitecrm_prospectlist extends suitecrm
{
	protected $id;
	protected $name;
	protected $date_entered;
	protected $date_modified;
	protected $description;
	protected $deleted;
	protected $approval_issue;
	protected $billing_account;
	protected $billing_contact;
i	protected $billing_address_street;	//!< text
	protected $billing_address_city;	//!< text
	protected $billing_address_postalcode;	//!< text
	protected $billing_address_state;	//!< text
	protected $billing_address_country;	//!< text

	protected $shipping_address_street;	//!< text
	protected $shipping_address_city;	//!< text
	protected $shipping_address_postalcode;	//!< text
	protected $shipping_address_state;	//!< text
	protected $shipping_address_country;	//!< text


	protected $team_count;
	protected $team_name;
	protected $date_qupte_expected_closed;
	protected $quote_type;
	protected $quote_stage;
	protected $qupte_num;
	protected $currency_id;
	protected $subtotal;
	protected $subtotal_usdollar;
	//protected $quote_url;	//url in Suite, need to convert for send/update/get
	//protected $contact;


	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "ProspectList" );
	}
