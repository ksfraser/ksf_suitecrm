<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrm_note.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/**********************************************************
*	20231120 This creates a NOTE with an ATTACHMENT.
		It doesn't set any "notes" (relationship) data.
***********************************************************/



class test_suitecrm extends suitecrm_note
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
	function set_test_vars()
	{
		$this->set( "module_name", "Notes" );
		$this->set( "name", "Test Upload " . date( 'YmdHis' ) );
		//$this->set( "revision", "1" );
		$this->set( "filename", "test.suitecrm_note.php" );
		$this->set( "file_upload_path", "test.suitecrm_note.php" );
		$this->set( "debug_level", "1" );
		$this->set( "assigned_user_id", $this->get( 'user_id' ) );
		$this->set( "contact_name", "Kevin Fraser" );
		$this->set( "portal_flag", true );
		$this->set( "embed_flag", true );
		$this->set( "note", "This is a text note" );	//Doesn't work.  DESCRIPTION
		$this->set( "description", "This is a text description" );
		$this->set( "notes", "This is a text notes" );	//Relationship?
		
	}
}

$params = array( "url"=> $url, "username" => $username, "password" =>$password, "module_name" => "login" );
$test = new test_suitecrm( null, $params );
$test->login();
/**/
$test->set( "search_string", "" );
$res = $test->search();
var_dump( $res );
 /**/
$test->set_test_vars();
try
{
	$test->create();
	$test->set( "debug_level", 1 );
	$result = $test->upload_file();	
	var_dump( $result );
	//$res = $test->get( "result" );
	//var_dump( $res );
}
catch( Exception $e )
{
	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}

