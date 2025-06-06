<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrm_aos_products_quotes.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/



class test_suitecrm extends suitecrm_aos_products_quotes
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
	function set_test_vars()
	{
		//set by the class...
		$this->set( "name", "Test Product Quote " . date( 'YmdHis' ) );
		//$this->set( "number",  date( 'YmdHis' ) );
		//$this->set( "stage", "Confirmed"  );
		//$this->set( "billing_account", "Test Account"  );
		//$this->set( "billing_contact", "Kevin Fraser"  );
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
/**/
$res = $test->set("search_string", "Test Product Quote ");
$res = $test->search();
$res = $test->search2("%");
var_dump( $res );
 /**/
$test->set_test_vars();
try
{
//	$test->create();
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
}


