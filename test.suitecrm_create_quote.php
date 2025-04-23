<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrmAosQuotes.php' );
require_once( 'class.suitecrmAosProducts.php' );
require_once( 'class.suitecrmAosProductsQuotes.php' );
require_once( 'class.suitecrmProductBundles.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/



class TestQuotes extends suitecrmAosQuotes
{

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "AOS_Quotes" );
	}
	function set_test_vars()
	{
		//set by the class...
		$this->set( "name", "Test Quote " . date( 'YmdHis' ) );
		$this->set( "number",  date( 'YmdHis' ) );
		$this->set( "stage", "Confirmed"  );
		$this->set( "billing_account", "Test Account"  );
		$this->set( "billing_contact", "Kevin Fraser"  );
		//$this->set( "revision", "1" );
//		$this->set( "filename", "class.suitecrm_product.php" );
//		$this->set( "file_upload_path", "class.suitecrm_product.php" );
		$this->set( "debug_level", "1" );
		$this->set( "assigned_user_id", $this->get( 'user_id' ) );
	}
}
class TestProduct extends suitecrmAosProducts
{
	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
	function set_test_vars()
	{
		//set by the class...
		$this->set( "name", "Test Product");
		
		$this->set( "status", "Quotes");
		//$this->set( "revision", "1" );
//		$this->set( "filename", "class.suitecrm_product.php" );
//		$this->set( "file_upload_path", "class.suitecrm_product.php" );
		$this->set( "debug_level", "1" );
		//$this->set( "assigned_user_id", $this->get( 'user_id' ) );
	}
}
class TestProductsQuotes extends suitecrmAosProductsQuotes
{
	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
	}
	function set_test_vars()
	{
		//set by the class...
		$this->set( "name", "Test Product");
		
		//$this->set( "status", "Quotes");
		$this->set( "deleted", "0");
		//$this->set( "revision", "1" );
//		$this->set( "filename", "class.suitecrm_product.php" );
//		$this->set( "file_upload_path", "class.suitecrm_product.php" );
		$this->set( "debug_level", "1" );
		//$this->set( "assigned_user_id", $this->get( 'user_id' ) );
	}
}
class TestProductBundles extends suitecrmProductBundles
{
	function set_test_vars()
	{
		//set by the class...
		$this->set( "name", "Test Product Bundle");
		
		$this->set( "bundle_stage", "Draft");
		$this->set( "tax", "0.00");
		$this->set( "total", "0.00");
		$this->set( "subtotal", "0.00");
		//$this->set( "revision", "1" );
//		$this->set( "filename", "class.suitecrm_product.php" );
//		$this->set( "file_upload_path", "class.suitecrm_product.php" );
		$this->set( "debug_level", "1" );
		//$this->set( "assigned_user_id", $this->get( 'user_id' ) );
		$this->set( "product_cost_price", "2.99" );
		$this->set( "product_list_price", "4.99" );
		$this->set( "product_unit_price", "3.99" );
		$this->set( "group_id", "1" );
	}
}

$params = array( "url"=> $url, "username" => $username, "password" =>$password, "module_name" => "login" );
$test = new TestQuotes( null, $params );
$test->login();
/** /
$res = $test->search2("%74241");
var_dump( $res );
 /**/
$test->set_test_vars();
try
{
	$test->set( "debug_level", 0 );
	$test->create();
	//Set Parent for the attached products
	$params["parent_type"] = $test->get( "module_name" );
	$params["parent_id"] = $test->get( "id" );
	$params["debug_level"] = $test->get( "debug_level" );
//This is creating a product.  Instead we should be searching for an existing product...
	$tpq = new TestProductsQuotes( null, $params );
	$tpq->login();
	//$res = $tpq->search2("%74241");
//	var_dump( $res );
	$tpq->set_test_vars();
	//$tpq->set( "quote_id", $test->get( "id" ) );
	$tpq->create();
	/** /
	$nvl = $tpq->get("name_value_list" );
	var_dump( $nvl );
	 /**/
	//$tpq->unset( "name_value_list" );
	$tpq2 = new TestProductsQuotes( null, $params );
	$tpq2->login();
	$tpq2->set_test_vars();
	$tpq2->set( "name", "Product " . date( 'YmdHis' ) );
	$tpq2->set( "group_id", "2" );
	//$tpq->objectvars2array();
	$tpq2->create();
	/** /
	$nvl = $tpq2->get("name_value_list" );
	var_dump( $nvl );
	 /**/

	$tpq3 = new TestProductsQuotes( null, $params );
	$tpq3->login();
	$tpq3->set_test_vars();
	$tpq3->set( "name", "Product " . date( 'YmdHis' ) );
	$tpq3->set( "group_id", "3" );
	//$tpq->objectvars3array();
	$tpq3->create();
	/** /
	$nvl = $tpq3->get("name_value_list" );
	var_dump( $nvl );
	/**/

		$tpq4 = new TestProductsQuotes( null, $params );
	$tpq4->login();
	$tpq4->set_test_vars();
	$tpq4->set( "name", "Product " . date( 'YmdHis' ) );
	$tpq4->set( "group_id", "4" );
	$tpq4->set( "product_cost_price", "2.99" );
	$tpq4->set( "product_list_price", "4.99" );
	$tpq4->set( "product_unit_price", "3.99" );
	//$tpq->objectvars4array();
	$tpq4->create();
	/** /
	$nvl = $tpq4->get("name_value_list" );
	var_dump( $nvl );
	 /**/


		$tpq5 = new TestProductsQuotes( null, $params );
	$tpq5->login();
	$tpq5->set_test_vars();
	$tpq5->set( "name", "Product " . date( 'YmdHis' ) );
	$tpq5->set( "group_id", "5" );
	//$tpq->objectvars5array();
	$tpq5->create();
	/** /
	$nvl = $tpq5->get("name_value_list" );
	var_dump( $nvl );
	 /**/

	
}
catch( Exception $e )
{
	throw new Exception( "This code is for testing.  Why isn't it commented out? :: " . $e->getMessage() );
}

use PHPUnit\Framework\TestCase;
use App\Models\AOSQuotesModel;
use App\Models\AOSQuotesController;

/**
 * PHPUnit test class for creating AOS Quotes.
 */
class CreateQuoteTest extends TestCase
{
    /**
     * Test the creation of a new quote.
     */
    public function testCreateNewQuote()
    {
        $model = new AOSQuotesModel(['name' => 'Test Quote']);
        $controller = new AOSQuotesController($model);

        $controller->createQuote();

        $this->assertNotNull($model->get('id'), 'Quote ID should not be null after creation.');
        $this->assertEquals('Test Quote', $model->get('name'), 'Quote name should match the input.');
    }
}


