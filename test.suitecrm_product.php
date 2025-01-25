<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrm_aos_products.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/*********************************************************************************/
*	20231120 Creates a Product.  However, we SHOULD set a product category!!
**********************************************************************************/


class test_suitecrm extends suitecrm_aos_products
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "AOS_Products" );
	}
	function set_test_vars()
	{
		//set by the class...
		//$this->set( "module_name", "AOS_Products" );
		$this->set( "name", "Test Product " . date( 'YmdHis' ) );
		$this->set( "price", "3.99"  );
		$this->set( "cost", "2.99"  );
		$this->set( "product_url", "http://fhsws001/boo"  );
		$this->set( "part_number",  "TP" . date( 'YmdHis' ) );
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

$test->set( "search_string", "%" );
$res = $test->search();
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
        echo "************************************";
        echo "\n\r";
        echo "      End of test.suitecrm_products ";
        echo "\n\r";
        echo "************************************";
        echo "\n\r";
        var_dump( $e->getMessage() );
        echo "********END MSG DUMP****************";
        echo "\n\r";
        echo "************************************";
//      throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}

