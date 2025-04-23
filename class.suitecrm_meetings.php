<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/


/******************************************************
*       20231120 A stub meeting is being created
*       but the CREATE fcn is not getting results back
*       so it is showing a FAILED status/Exception
******************************************************/


class SuitecrmMeetings extends suitecrmBase
{
//	var $id;
//	protected $meeting_id;
	protected $name;
	protected $description;
	protected $location;
	protected $date_start;
	protected $date_end;
	protected $parent_type;
	protected $status;
	protected $outlook_id;
	protected $sequence;


	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Meetings" );
	}
	function createz()
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

/*
 * The following code creates a meeting within SuiteCRM.  It does not associate it to any
 * Accounts, Contacts or other records!
 * */
/*
$cl = new suitecrm_meeting("http://fhsws001/devel/fhs/SuiteCRM/service/v4_1/rest.php", "kevin", "Letmein1" );
$cl->set( "name", "Test Note" );
$cl->set( "description", "This is a Test Note description to ensure we can create a meeting and attachment." );
$cl->set( "revision", "1" );
$cl->set( "save_filename", "class.suitecrm_meeting.php" );
$cl->set( "file_upload_path", "class.suitecrm_meeting.php" );
$cl->set( "debug_level", "1" );
$cl->set( "debug_level", "0" );
try
{
	$cl->login();
}
catch( Exception $e )
{
	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}
try
{
	$cl->create();
	$cl->attach();
	echo "Returned ID is " . $cl->get( "id" );
	$resp = $cl->get( "response" );
	var_dump( $resp );
}
catch( Exception $e )
{
	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}
 /**/
