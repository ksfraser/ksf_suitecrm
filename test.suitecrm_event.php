<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrmEvent.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/



class TestSuitecrm extends suitecrmEvent
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
	function set_test_vars()
	{
		$this->set( "module_name", "Event" );
		$this->set( "description", "Test event " . date( 'YmdHis' ) );
		$this->set( "name", "Test event " . date( 'YmdHis' ) );
		/*
		$this->set( "last_name",  date( 'YmdHis' ) );
		$this->set( "first_name", "Test event " );
		$this->set( "phone_fax", "403-912-1654" );
		$this->set( "phone_home", "587-600-0013" );
		$this->set( "phone_work", "587-600-0022" );
		$this->set( "phone_mobile", "587-830-1654" );
		$this->set( "email1", "test@ksfraser.com" );
		 */
		//$this->set( "revision", "1" );
		//$this->set( "filename", "example.create_event.php" );
		//$this->set( "file_upload_path", "example.create_event.php" );
		$this->set( "debug_level", "1" );
		$this->set( "assigned_user_id", $this->get( 'user_id' ) );
	}
}

$params = array( "url"=> $url, "username" => $username, "password" =>$password, "module_name" => "login" );
$test = new TestSuitecrm( null, $params );
$test->login();
/**/
$res = $test->search2("%");
var_dump( $res );
 /**/
$test->set_test_vars();
try
{
	$test->create();
	$test->set( "debug_level", 1 );
	/*
	$result = $test->upload_file();	
	var_dump( $result );
	/**/
	//$res = $test->get( "result" );
	//var_dump( $res );
}
catch( Exception $e )
{
	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
	var_dump( $test->result );
}

