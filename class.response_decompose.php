<?php

/****************************************************************//**
 *Take the response from a get_entry_list and decompose into original module
 *
 * 	********************************************************************/

/***********************//**
 * Generic class
 * ***********************/

require_once( '../ksf_modules_common/class.origin.php' );
require_once( "class.name_value_list.php" );


/***********************************************//**
 * Superparent to SUITECRM classes.  
 *
 * This class handles SOAP activities.
 * *************************************************/
class response_decompose extends origin
{
	protected $nvl;				//!<array of data to be added to SOAP msg
	protected $module_name;			//!<string the module to query about
	protected $id;
	protected $obj;
	/**********************************************************//**
	 *
	 * @param response stdClass object response from a SOAP query
	 * *********************************************************/	
	function __construct( $response )
	{
 		/*
                 ["id"]=>
                        string(36) "e7e3b6f5-b7db-0cdf-fcbe-5a7fc7ddd4c4"
                ["module_name"]=>
                        string(8) "Accounts"
                ["name_value_list"]=>
                         [51]=>
                        object(stdClass)#516 (2) {
                                ["name"]=>
                                        string(15) "jjwg_maps_lat_c"
                                ["value"]=>
                                        string(10) "0.00000000"
    			}
                */
		
		if( !isset( $response->id ) )
			throw new Exception( "Are you sure of the data type passed in?" );
		else
			$this->set( "id", $response->id );
		if( !isset( $response->module_name ) )
			throw new Exception( "Without a module name we can't go forward" );
		else
			$this->set( "module_name", $response->module_name );
		if( !isset( $response->name_value_list ) )
			throw new Exception( "Without a NVL we can't go forward" );
		else
		{
			$nvl = new name_value_list();
			$hash = $nvl->hash_nvl( $response->name_value_list );
			$this->hash2obj( $hash );
		}
	}
	function hash2obj( $hash = null )
	{
		$classname = "suitecrm_" . strtolower( $this->module_name );
		require_once( 'class.' . $classname . '.php' );
		$obj = new $classname();
		foreach( $hash as $key=>$value )
		{
			$obj->set( $key, $value, true );
		}
		$this->obj = $obj;
		//We now have a "Accounts/Contacts/..." module ready for use (i.e. ->prepare())
	}
}

