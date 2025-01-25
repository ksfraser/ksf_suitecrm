<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class suitecrm_aos_quotes extends suitecrm_base
{
	protected $name;
	protected $team_count;
	protected $team_name;
	protected $date_quote_expected_close;
	protected $stage;
	protected $quote_num;
	protected $number;
	protected $quote_tpe;
	protected $subtotal;
	protected $subtotal_usdollar;
	protected $total_amount;
	protected $expiration;
	protected $description;
	protected $deleted;
	protected $maincode;
	protected $part_number;
	protected $category;
	protected $type;
	protected $cost;
	protected $cost_usdollar;
	protected $currency_id;
	protected $price;
	protected $price_usdollar;
	protected $product_url;	//url in Suite, need to convert for send/update/get
	protected $contact;
	protected $product_image;
	protected $aos_product_category;
 protected $description;
    protected $aos_quotes_type;
    protected $industry;
    protected $annual_revenue;
    protected $phone_fax;
    protected $billing_address_street;
    protected $billing_address_city;
    protected $billing_address_state;
    protected $billing_address_postalcode;
    protected $billing_address_country;
    protected $rating;
    protected $phone_office;
    protected $phone_alternate;
    protected $website;
    protected $ownership;
    protected $employees;
    protected $ticker_symbol;
    protected $shipping_address_street;
    protected $shipping_address_city;
    protected $shipping_address_state;
    protected $shipping_address_postalcode;
    protected $shipping_address_country;
    protected $email1;
    protected $email_addresses_primary;
    protected $approval_issue;
    protected $billing_account_id;
    protected $billing_account;
    protected $billing_contact_id;
    protected $billing_contact;
    protected $expiration;
    protected $number;
    protected $opportunity_id;
    protected $opportunity;
    protected $shipping_account_id;
    protected $shipping_account;
    protected $template_ddown_c;
    protected $shipping_contact_id;
    protected $shipping_contact;
    protected $subtotal_amount;
    protected $tax_amount;
    protected $shipping_amount;
    protected $total_amount;
    protected $stage;
    protected $term;
    protected $terms_c;
    protected $approval_status;
    protected $invoice_status;
	

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "AOS_Quotes" );
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
		if( isset( $this->result->id ) )
		{
		//	$this->set( "AOS_Product_id", $this->result->id );
			$this->set( "id", $this->result->id );
		}
		else
		{
			var_dump( $this->result );
			throw new Exception( "Record not created!" );
		}
		 /*
		var_dump( $this->document_id );
		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		 /**/
	
	}
	/**************************************//**
	 * Attach a document to the AOS_Product
	 *
	 * Assumption that CREATE has already been run
	 * so that AOS_Product_ID is set.
	 *
	 * ***************************************/
		/*
	function upload_file()
	{
	    if( ! isset( $this->id ) )
	    {
		    throw new Exception( "Can't attach a document to a record when the record isn't specified" );
	    }

		if( !isset( $this->file_upload_path ) )
			throw new Exception( "Document path not set" );
		$this->suitecrm->set( "file_upload_path", $this->file_upload_path );
		$this->suitecrm->set( "upload_method", "set_AOS_Product_attachment" );
		$this->suitecrm->set( "attach_to_id", $this->id );
		$this->result = $this->suitecrm->upload_file();
		if( 1 < $this->debug_level )
		{
		    	echo "<pre>" . "::" . __LINE__ . "::" . __METHOD__ . "\n\r";
		    	print_r($this->result);
			echo "</pre>";
		}
		return $this->result;
	}
		/**/

}

