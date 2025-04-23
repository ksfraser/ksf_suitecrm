<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class SuitecrmAccounts extends suitecrmBase
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
	    $this->set( "module_name", "Accounts" );
    }

}



/* END OF TEST SECTION*/
//0_crm_persons
//id
//ref
//name
//name2
//address
//phone
//phone2
//fax
//email
//lang
//notes
//inactive
//
//0_debtors_master
//debtor_no
//name
//address
//tax_id
//curr_code
//sales_type
//dimension_id
//dimension2_id
//credit_status
//payment_terms
//discount
//pymy_discout
//credit_limit
//notes
//inactive
//debtor_ref
//
//cust_branch
//branch_code
//debtor_no
//br_name
//br_address
//area
//salesman
//contact_name
//default_location
//tax_group_id
//sales_account
//sales_discount_account
//receivables_account
//payment_discount_account
//default_ship_via
//disable_trans
//br_post_address
//group_no
//notes
//inactivity
//branch_ref
//
//SUITE
/*
*/

class front2suite
{
	var $url;
	var $username;
	var $password;
	function __construct( $url, $username, $password )
	{
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
	}
	function convert( $crm_persons, $debtors_master )
	{
		$suite = new suitecrm_contact( $this->url, $this->username, $this->password );
		$suite->first_name = $crm_persons->name;
		$suite->last_name = $crm_persons->name2;
	//	$suite->id;
		$suite->date_entered;
	//	$suite->date_modified;
		$suite->description = $crm_persons->notes;
		$suite->deleted= $crm_persons->inactive;
	//	$suite->salutation;
	//	$suite->title;
	//	$suite->photo;
		$suite->department;
	//	$suite->do_not_call;
		$suite->phone_home = $crm_persons->phone;
		$suite->phone_mobile = $crm_persons->phone2;
		$suite->phone_work;
		$suite->phone_other;
		$suite->phone_fax = $crm_persons->fax;
		$suite->email1 = $crm_persons->email;
		$suite->billing_address_street = $crm_persons->address;
	//	$suite->billing_address_city;
	//	$suite->billing_address_postalcode;
	//	$suite->billing_address_state;
	//	$suite->billing_address_country;
		$suite->shipping_address_street = $debtors_master->address;
	//	$suite->shipping_address_city;
	//	$suite->shipping_address_postalcode;
	//	$suite->shipping_address_state;
	//	$suite->shipping_address_country;
		$suite->assistant;
		$suite->assistant_phone;
		$suite->lead_source = "frontaccounting";
	//	$suite->birthdate;
	//	$suite->joomla_account_id;
	//	$suite->portal_user_type;
	}
}
