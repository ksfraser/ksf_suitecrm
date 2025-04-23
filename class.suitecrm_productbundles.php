<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/***//*************
 * From Sugar API Example.  Suite says module doesn't exist
 *
 * */

class SuitecrmProductBundles extends suitecrmBase
{
	protected $name;
	protected $bundle_stage;
	protected $tax;
	protected $total;
	protected $subtotal;
	protected $type;
	protected $currency_id;
	protected $contact;
	protected $shipping;
	protected $quote_id;	//Quote
	protected $status;	//Quote

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "ProductBundles" );
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

