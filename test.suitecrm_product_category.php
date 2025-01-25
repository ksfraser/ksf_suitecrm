<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrm_aos_product_categories.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/



class test_suitecrm extends suitecrm_aos_product_categories
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		//$this->set( "module_name", "AOS_Products" );
	}
	function set_test_vars()
	{
		//set by the class...
		//$this->set( "module_name", "AOS_Products" );
		$this->set( "name", "Test Product Cat" . date( 'YmdHis' ) );
		//$this->set( "revision", "1" );
//		$this->set( "filename", "class.suitecrm_product.php" );
//		$this->set( "file_upload_path", "class.suitecrm_product.php" );
		$this->set( "debug_level", "1" );
		$this->set( "assigned_user_id", $this->get( 'user_id' ) );
	}
}

$params = array( "url"=> $url, "username" => $username, "password" =>$password, "module_name" => "login" );
$test = new test_suitecrm( null, $params );
$test->login();
/*
$res = $test->search2("%");
var_dump( $res );
 /**/
$test->set_test_vars();
try
{
	$test->set( "debug_level", 1 );
	$res = $test->create();
//	var_dump( $res );
	//var_dump( $test->result );
	/*
	$result = $test->upload_file();	
	/**************************************************************
		object(stdClass)#4 (2) {
		  ["id"]=>
		  string(36) "bbf7f207-9426-b363-5f2d-655c1a8505f6"
		  ["entry_list"]=>
		  object(stdClass)#18 (2) {
		    ["name"]=>
		    object(stdClass)#6 (2) {
		      ["name"]=>
		      string(4) "name"
		      ["value"]=>
		      string(30) "Test Product Cat20231121025014"
		    }
		    ["assigned_user_id"]=>
		    object(stdClass)#17 (2) {
		      ["name"]=>
		      string(16) "assigned_user_id"
		      ["value"]=>
		      string(36) "46bcf7a3-9a6c-659f-5344-65488a3a4106"
		    }
		  }
		}
	****************************************************************/
	//var_dump( $res->entry_list->name->value );
	$test->set( "search_string", $res->entry_list->name->value );
	echo "************************************";
	echo "\n\r";
	echo "SEARCH STRING: " . $test->get( "search_string" );
	echo "\n\r";
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
	//$res = $test->get( "result" );
	//var_dump( $res );
}
catch( Exception $e )
{
	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}


