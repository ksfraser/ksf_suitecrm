<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/


/***************************************
*	20231120 This creates a product!
****************************************/

class suitecrm_aos_products extends suitecrm_base
{
	protected $name;
	protected $description;
	protected $deleted;
	protected $maincode;
	protected $part_number;
	protected $category;		//Relationship aos_product_categories.
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
	protected $quote_id;	//Quote
	protected $status;	//Quote
	protected $purchases;		//Sub Panel

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "AOS_Products" );
	}
	function login()
	{
		parent::login();
		//Product records have URL.  However, by default the URL passed in
		//is the REST API url, so not appropriate.  We don't want to attach
		//that URL to products in case we publish them to an online store
		//and provide the pathway into the CRM
		$this->unset( "url" );
	}
	function create()
	{
		parent::create();		
		/**/
		if( isset( $this->result->id ) )
		{
			$this->set( "id", $this->result->id );
		}
		else
		{
			var_dump( $this->result );
			throw new Exception( "suitecrm_aos_products Record not created!" );
		}
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

