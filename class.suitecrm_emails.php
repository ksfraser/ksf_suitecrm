<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class suitecrm_emails extends suitecrm_base
{
	//var $id;
	protected $date_entered;
	protected $date_modified;
	protected $description;
	protected $deleted;
	protected $salutation;
	protected $first_name;
	protected $last_name;
	protected $name;
	protected $title;
	protected $account_name;
	protected $photo;
	protected $department;
	protected $do_not_call;
	protected $phone_home;
	protected $phone_mobile;
	protected $phone_work;
	protected $phone_other;
	protected $phone_fax;
	protected $email1;
	protected $primary_address_street;
	protected $primary_address_city;
	protected $primary_address_postal;
	protected $primary_address_state;
	protected $primary_address_country;
	protected $alt_address_street;
	protected $alt_address_city;
	protected $alt_address_postal;
	protected $alt_address_state;
	protected $alt_address_country;
	protected $assistant;
	protected $assistant_phone;
	protected $lead_source;
	protected $birthdate;
	protected $joomla_account_id;
	protected $portal_user_type;

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Emails" );
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
		//$this->set( "note_id", $this->result->id );
		if( isset( $this->result->id ) )
			$this->set( "id", $this->result->id );
		else
		{
			throw new Exception( "Failed to create record!" );
			var_dump( $this->result );
		}
		 /*
		var_dump( $this->document_id );
		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		 /**/
	
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

