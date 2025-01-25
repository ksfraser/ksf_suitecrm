<?php
/******************************************//**
 *
 * Controller to test functions of the classes
 *	**************************************/

//
// Required libraries

require_once( "../ksf_modules_common/class.origin.php" );
require_once( "class.suitecrmSoapClient.php" );
require_once( "../ksf_modules_common/defines.inc.php" );


$config_array = array();
global $userGUID;
$config_array['site_url'] = "http://fhsws001.ksfraser.com/devel/fhs/suitecrm/service/v4_1/soap.php";
$config_array['soapuser'] = "admin";
$config_array['pass_hash'] = md5('m1l1ce');
$config_array['version'] = "4";
$config_array['testing'] = "true";

class cloneSiteA2B extends origin
{
	protected $connectionA;
	protected $testing;
	protected $module_name;
	protected $module_id;
	protected $module_ids;
	protected $record_id;
	protected $related_ids;
	protected $related_fields;
	protected $related_module_query;
	protected $module_names;
	protected $query;
	protected $track_view;
	protected $max_results;
	protected $limit;
	protected $delete;
	protected $deleted;
	protected $order_by;
	protected $offset;
	protected $record_ids;
	protected $select_fields;
	protected $link_field_name;
	protected $link_field_names;
	protected $link_name_to_fields_array;

	/****************************************************************//**
	 *
	 * Defaults:
	 * 	TESTING is false
	 * 	Authoratative is A
	 * 	email_address kevin@ksfraser.com
	 * 	delete_on_clone false
	 *
	 * 	@param config_array array
	 * ******************************************************************/
	function __construct( $config_array = null )
	{
		if( !isset( $config_array['site_url'] ) )
			throw new Exception( "Missing Config value site_url", KSF_VALUE_NOT_SET );
		if( !isset( $config_array['appname'] ) )
			throw new Exception( "Missing Config value appname", KSF_VALUE_NOT_SET );
		if( !isset( $config_array['soapuser'] ) )
			throw new Exception( "Missing Config value soapuser", KSF_VALUE_NOT_SET );
		if( !isset( $config_array['pass_hash'] ) )
			throw new Exception( "Missing Config value pass_hash", KSF_VALUE_NOT_SET );
		/******************************************************************************/
		//global $sugar_config;
		//$sugar_config[ "site_url" ] = $config_array['site_url']; 
		//$sugar_config[ "appname" ] = $config_array['appname']; 
		//$sugar_config[ "username" ] = $config_array['soapuser']; 
		//$sugar_config[ "password" ] = $config_array['pass_hash'];
		$this->connectionA = new suitecrmSoapClient( array( 	"site_url" => $config_array['site_url'], 
			"appname" => $config_array['appname'], 
			"username" => $config_array['soapuser'], 
			"password" => $config_array['pass_hash'] ) );
		/******************************************************************************/
		if( isset( $config_array[ 'testing' ] ) )
			switch( $config_array[ 'testing' ] )
			{
				case true:
				case false:
					$this->testing = $config_array[ 'testing' ];
					break;
				default:
					$this->testing = false;

			}
		else
			$this->testing = false;
		/******************************************************************************/
		$this->suite_soap_defaults();
	}
	function suite_soap_defaults()
	{
		$this->set( "module_name", "" );
		$this->set( "module_id", "" );
		$this->set( "module_ids", array( "" ) );
		$this->set( "record_id", "" );
		$this->set( "record_ids", "" );
		$this->set( "related_ids", array( "" ) );
		$this->set( "related_fields", array( "" ) );
		$this->set( "related_module_query", "" );
		$this->set( "module_names", array( "" ) );
		$this->set( "query", "" );
		$this->set( "track_view", "" );
		$this->set( "max_results", "10" );
		$this->set( "limit", "10" );
		$this->set( "delete", "0" );
		$this->set( "deleted", "0" );
		$this->set( "order_by", "" );
		$this->set( "offset", "" );
		$this->set( "record_ids", array() );
		$this->set( "select_fields", array() );
		$this->set( "link_field_name", "" );
		$this->set( "link_field_names", array() );
		$this->set( "link_name_to_fields_array", array() );

		$this->connectionA->set( "module_name", "" );
		$this->connectionA->set( "module_id", "" );
		$this->connectionA->set( "module_ids", array( "" ) );
		$this->connectionA->set( "record_id", "" );
		$this->connectionA->set( "record_ids", "" );
		$this->connectionA->set( "related_ids", array( "" ) );
		$this->connectionA->set( "related_fields", array( "" ) );
		$this->connectionA->set( "related_module_query", "" );
		$this->connectionA->set( "module_names", array( "" ) );
		$this->connectionA->set( "query", "" );
		$this->connectionA->set( "track_view", "" );
		$this->connectionA->set( "max_results", "10" );
		$this->connectionA->set( "limit", "10" );
		$this->connectionA->set( "delete", "0" );
		$this->connectionA->set( "deleted", "0" );
		$this->connectionA->set( "order_by", "" );
		$this->connectionA->set( "offset", "" );
		$this->connectionA->set( "record_ids", array() );
		$this->connectionA->set( "select_fields", array() );
		$this->connectionA->set( "link_field_name", "" );
		$this->connectionA->set( "link_field_names", array() );
		$this->connectionA->set( "link_name_to_fields_array", array() );
	}
	function run( $modules_array = null )
	{

		$test_data = array(
			"name" => $name,
			"description" => $text
		);
	
		foreach( $modules_array as $module )
		{
			$lmod = strtolower( $module );
			require_once( 'class.suitecrm_' . $lmod . '.php' );
			$mod = new suitecrm_$lmod( $test_data );
			$mod->prepare();
			$this->connectionA->set( "nvl", $mod->get( "nvl" ) );
			$this->connectionA->set( "module_name", $module );
			if( $this->testing )
			{
				$this->connectionA->set( "max_results", "10" ); 
			}
			$ret = $this->connectionA->set_entry();
			if( $this->testing )
			{
				//var_dump( $res );
				//exit;
			}
			$mod->set( "id", $ret->id );
			$mod->prepare();
			$this->connectionA->set( "nvl", $mod->get( "nvl" ) );
			$ret = $this->connectionA->get_entry_list();
		}
	}
	function create_note_of_differences( $a, $b, $c, $mod, $r_id )
	{
		echo __METHOD__ . "::" . __LINE__ . "\n";
		$ajson = json_encode( $a );
		$bjson = json_encode( $b );
		$cjson = json_encode( $c );
		$name = "Difference on record between systems A and B";
		$text = "Data from A: \n" . $ajson . "\n" . "Data from B: \n" . $bjson;
		$text .= "\n\n" . "Merged data: \n" . $cjson;
		$this->create_note( $name, $text, $mod, $r_id );
	}
	function create_note( $name, $text, $related_module = null, $related_id = null )
	{
		require_once( 'class.suitecrm_note.php' );
		echo __METHOD__ . "::" . __LINE__ . "\n";
		$data = array(
			"name" => $name,
			"description" => $text,
			"related_module_name" => $related_module,
			"related_module_id" => $related_id );
		$this->connectionB->set( "module_name", "Notes" );

		$note = new suitecrm_note( $data );
		$note->prepare();
		$this->connectionB->set( "nvl", $note->get( "nvl" ) );
		$ret = $this->connectionB->set_entry();
		echo __METHOD__ . "::" . __LINE__ . "\n";
		var_dump( $ret );
		return $ret;
	}
	function create_task_review_differences()
	{
		$task_ret = $this->create_task( $data );
	}
	function create_task( $data )
	{
		$task = new suitecrm_task( $data );
		$task->prepare();
		$this->connectionB->set( "nvl", $task->get( "nvl" ) );
		$ret = $this->connectionB->set_entry();
		echo __METHOD__ . "::" . __LINE__ . "\n";
		var_dump( $ret );
		return $ret;

	}
	function email_record( $record, $subject )
	{
		echo __METHOD__ . "::" . __LINE__ . "\n";
		$j = json_encode( $subject ) . "\n" . json_encode( $record );
		var_dump( $j );
	}
}


//TESTS replace


$obj = new cloneSiteA2B( $config_array );
$obj->run( array( "Accounts" ) );

/*
$o = new suitecrmSoapClient();
$o->soapLogin();
$nvl = new name_value_list();
$nvl->add_nvl( "filter", "accounts.name like '%Fraser%'" );
$nvl->add_nvl( "order_by", "date_entered" );
$nvl->add_nvl( "start", "0" );
$nvl->add_nvl( "Return", array() );
$nvl->add_nvl( "Link", "" );
$nvl->add_nvl( "Results", "10" );
$nvl->add_nvl( "Deleted", "0" );
$nvl->add_nvl( "unknown", "false" );

$o->setSoapParams( "Accounts", $nvl->get_nvl() );
$oret = $o->soapCall( "get_entry_list" );
//print "Returned object call 1";
//var_dump( $oret );
$p = new suitecrmSoapClient();
$p->set( 'url', $sugar_config['site_url2']  . "/soap.php" );
$p->set( 'username', $sugar_config['soapuser2'] );
$p->set( 'soapCredential', $sugar_config['pass_hash2'] );
$p->setSoapClient();
$p->soapLogin();
$p->setSoapParams( "Accounts", $nvl->get_nvl() );
$pret = $p->soapCall( "get_entry_list" );
//print "Returned object call 2";
//var_dump( $pret );

foreach( $oret->entry_list as $recs )
{
	$success = false;
	//var_dump( $recs );
	$OUID = $recs->id;
	//Get the UID and see if in other list
	print "Searching for ID " . $OUID . "\n";
	$count = 0;
	foreach( $pret->entry_list as $precs )
	{

		if( $precs->id <> $OUID )
		{
			print " NO MATCH $OUID::$precs->id\n";
			$count++;
		}
		else
		{
			print " MATCH $OUID::$precs->id on $count record\n";
			$success = true;
			break;
		}
	}
	if( $success )
	{
		//do nothing, unless we compare fields for changes
	}
	else
	{
		//insert the record
		
	}
		
}

$nvl2 = new name_value_list();
$nvl2->add_nvl( "name", "soap Test Company" );
$nvl2->add_nvl( "phone", "4035272135" );
//$nvl2->add_nvl( "assigned_user_name", "kevin" );
$nvl2->add_nvl( "assigned_user_id", "2" );
//$nvl2->add_nvl( "created_by_name", "admin" );
//$nvl2->add_nvl( "modified_user_id", "1" );
//$nvl2->add_nvl( "description", "1" );
//$nvl2->add_nvl( "account_type", "" );
$nvl2->add_nvl( "phone_fax", "4039121654" );
$nvl2->add_nvl( "billing_address_street", "747 Windridge Road SW" );
//$nvl2->add_nvl( "billing_address_city", "Airdrie" );
//$nvl2->add_nvl( "billing_address_state", "Alberta" );
//$nvl2->add_nvl( "billing_address_postalcode", "T4B2R1" );
//$nvl2->add_nvl( "billing_address_country", "Canada" );
$nvl2->add_nvl( "phone_office", "5876000013" );
$nvl2->add_nvl( "email1", "kevin@ksfraser.com" );
//$o->setSoapParams( "Accounts", "");
var_dump( $nvl2->get_nvl() );

$p->set( "module_name", "Accounts" );
$p->set( "nvl", $nvl2->get_nvl() );
$p->set( "select_fields", array( "name", "last_name", "account_type", "phone_fax", "phone_office", "email1", "assigned_user_name", "assigned_user_id", "description" ) );
//$p->setSoapParams( "Accounts", array( $nvl2->get_nvl() ) );
$ret = $p->soapCall( "set_entry" );
var_dump( $ret );
//$p->set( "nvl", null );
//$p->set( "record_id", "efc604ff-fcd2-7625-65af-5deacd606f8e" );	//WORKS
$p->set( "record_id", $ret->id );
$ret2 = $p->get_entry(	); 
var_dump( $ret2 );

$p->set( "module_name", "Contacts" );
$nvl2->add_nvl( "name", "soap Test Person" );
$nvl2->add_nvl( "account_id", $ret->id );
$nvl2->add_nvl( 'first_name','Geek');
$nvl2->add_nvl( 'last_name','Smith');
$nvl2->add_nvl( 'email1','a@b.com');
var_dump( $nvl2->get_nvl() );
$p->set( "nvl", $nvl2->get_nvl() );
$ret3 = $p->soapCall( "set_entry" );
var_dump( $ret3 );

 */


//$p = new suitecrmSoapClient();
/*
 * Taken care of by refactored classes.
$p->set( 'url', $sugar_config['site_url2']  . "/soap.php" );
$p->set( 'username', $sugar_config['soapuser2'] );
$p->set( 'soapCredential', $sugar_config['pass_hash2'] );
$p->setSoapClient();
$p->soapLogin();
 */


//$nvl = new name_value_list();
//$nvl->add_nvl( "name", "soap Test Company 1540" );
//$p->set( 'module_name', 'Accounts' );
//$p->set( 'nvl', $nvl->get_nvl() );
//
/*
$p->set( 'soapParams', array( 	
			$p->get( 'session_id' ), 
			$p->get( 'module_name' ), 
			$p->get( 'nvl' ) ) );
 */
//$pret = $p->soapCall( "set_entry" );
//
//$pret = $p->set_entry();
//$account_id = $pret->id;

//$nvl2 = new name_value_list();
//$nvl2->add_nvl( "name", "soap Test Person 1540" );
//$nvl2->add_nvl( "account_id", $account_id );
//$nvl2->add_nvl( 'first_name','Geek 1540');
//$nvl2->add_nvl( 'last_name','Smith 1540');
//$nvl2->add_nvl( 'email1','a1540@b.com');

//$p->set( 'module_name', 'Contacts' );
//$p->set( 'nvl', $nvl2->get_nvl() );
/*
$p->set( 'soapParams', array( 	
			$p->get( 'session_id' ), 
			$p->get( 'module_name' ), 
			$p->get( 'nvl' ) ) );
 */
//$pret = $p->set_entry();
