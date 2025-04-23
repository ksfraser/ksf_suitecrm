<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrmMeetings.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/******************************************************
*	20231120 A stub meeting is being created
*	but the CREATE fcn is not getting results back
*	so it is showing a FAILED status/Exception
******************************************************/



class TestSuitecrm extends suitecrmMeetings
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
	function set_test_vars()
	{
		$this->set( "module_name", "Meetings" );
		$this->set( "name", "Test meeting " . date( 'YmdHis' ) );
		$this->set( "description", "Test meeting " . date( 'YmdHis' ) );
		$this->set( "date_start", date( 'YmdHis' ) );
		$this->set( "status", "Held" );
		//$this->set( "revision", "1" );
		//$this->set( "filename", "example.create_meeting.php" );
		//$this->set( "file_upload_path", "example.create_meeting.php" );
		$this->set( "debug_level", "2" );
		$this->set( "assigned_user_id", $this->get( 'user_id' ) );
	}
}

$params = array( "url"=> $url, "username" => $username, "password" =>$password, "module_name" => "login" );
$test = new TestSuitecrm( null, $params );
$test->login();
/**/
$res = $test->search2("Less");
var_dump( $res );
 /**/
$test->set_test_vars();
try
{
	$res = $test->create();
	/*
	$result = $test->upload_file();	
	var_dump( $result );
	/**/
	//$res = $test->get( "result" );
	var_dump( $res );
}
catch( Exception $e )
{
	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}

