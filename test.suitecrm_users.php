<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrm_users.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/******************************************************************
*	20231120 This creates a user when LOGGED IN with an admin.
******************************************************************/


class test_suitecrm extends suitecrm_users
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
	function set_test_vars()
	{
		$this->set( "module_name", "Users" );
		$this->set( "user_name", "Test" . date( 'YmdHis' ) );
		$this->set( "first_name", "Test user " . date( 'YmdHis' ) );
		$this->set( "last_name", "Test user " . date( 'YmdHis' ) );
		//$this->set( "description", "Test user " . date( 'YmdHis' ) );
		//$this->set( "revision", "1" );
		//$this->set( "filename", "example.create_user.php" );
		//$this->set( "file_upload_path", "example.create_user.php" );
		$this->set( "debug_level", "1" );
		//$this->set( "assigned_user_id", $this->get( 'user_id' ) );
	}
	function create()
	{
		$this->set_test_vars();
		parent::create();

			/**********RESPONSE*************/
			/*
			object(stdClass)#159 (2) {
				  ["id"]=>
				  string(36) "3173d6d7-5010-d826-674e-655bf7760472"
				  ["entry_list"]=>
				  object(stdClass)#161 (3) {
				    ["user_name"]=>
				    object(stdClass)#160 (2) {
				      ["name"]=>
				      string(9) "user_name"
				      ["value"]=>
				      string(18) "Test20231121001725"
				    }
				    ["first_name"]=>
				    object(stdClass)#162 (2) {
				      ["name"]=>
				      string(10) "first_name"
				      ["value"]=>
				      string(24) "Test user 20231121001725"
				    }
				    ["last_name"]=>
				    object(stdClass)#163 (2) {
				      ["name"]=>
				      string(9) "last_name"
				      ["value"]=>
				      string(24) "Test user 20231121001725"
				    }
				  }
				}
			/***************************/

	}
}

$params = array( "url"=> $url, "username" => $username, "password" =>$password, "module_name" => "login" );
$test = new test_suitecrm( null, $params );
$test->login();
/**/

$test->set( "search_string", "Kevin" );
$res = $test->search();
echo "************************************";
echo "\n\r";
echo "      Dump Search Results           ";
echo "\n\r";
echo "************************************";
echo "\n\r";
var_dump( $res );
echo "**********END DUMP******************";
echo "\n\r";
echo "************************************";
echo "\n\r";

 /**/

$test->set_test_vars();
try
{
	$test->set( "debug_level", 1 );
	$test->create();
	/*
	$result = $test->upload_file();	
	var_dump( $result );
	/**/
	//$res = $test->get( "result" );
	//var_dump( $res );
}
catch( Exception $e )
{
	echo "************************************";
	echo "\n\r";
	echo "      End of test.suitecrm_users    ";
	echo "\n\r";
	echo "************************************";
	echo "\n\r";
	var_dump( $e->getMessage() );
	echo "********END MSG DUMP****************";
	echo "\n\r";
	echo "************************************";
//	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}

