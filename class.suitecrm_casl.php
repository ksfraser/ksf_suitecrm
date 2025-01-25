<?php

require_once( 'class.suitecrm.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class suitecrm_casl extends suitecrm
{
	var $id;
//	protected $casl_id;
	protected $name;
	protected $description;
	protected $contact;
	protected $submittedbyemail;
	protected $publishedPublically;
	protected $submittedbyform;
	protected $givenbusinesscard;
	protected $existingrelationship;
	protected $lead;

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Casl" );
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

