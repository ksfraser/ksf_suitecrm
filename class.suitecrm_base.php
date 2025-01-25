<?php

require_once( '../ksf_modules_common/class.origin.php' );
require_once( 'class.suitecrm.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class suitecrm_base extends origin
{
	var $id;		//!< string should be a GUID  xxxxx-xxxxx-xxxxx
	protected $suitecrm;	//!< class suitecrm (connector)
	protected $url;		//!< string http://...
	protected $password;	//!< string
	protected $username;	//<! string
	protected $user_id;	//<! string returned from SuiteCRM
	protected $module_name;	//<! string Accounts/Leads/Contacts/Notes/...
	protected $debug_level;
	protected $loggedin;	//<! bool
//SEARCH
	protected $search_string;
	protected $search_modules_array;
	protected $search_offset;
	protected $search_max_results;
	protected $search_return_fields_array;
	protected $unified_search_only;
	protected $search_favorites_only;
	protected $search_id;
	protected $search_by_id;	//!<bool should we search by ID
//
	protected $result;
	//FILE
	//COMMON
	protected $created_by_name;
	protected $date_entered;		//!< datetime
	protected $date_modified;		//!< datetime
	protected $deleted;			//!< checkbox
	protected $assigned_user_id;
	protected $assigned_user_name;
	public $modified_user_id;
	public $modified_by_name;
	public $created_by;
	//public $created_by_name;
	public $created_by_link;
	public $modified_user_link;
	public $assigned_user_link;


	//Relationships
	protected $related_module;
	protected $related_id;
	protected $parent_type;
	protected $parent_id;
	public $parent_name;

	


	
    function __construct($debug_level = PEAR_LOG_DEBUG, $param_arr )
    {
	    parent::__construct( $debug_level, $param_arr );
	    if( isset( $this->url ) AND isset( $this->username ) AND isset( $this->password ) AND isset( $this->module_name ) )
	    {
		    $this->suitecrm = new suitecrm( $this->url, $this->username, $this->password, $this->module_name );
	    }
	    else
	    {
		    //Should I throw an exception?  We can't setup the connection otherwise.
		    throw new Exception( "Not all pre-reqs are set so can't create the connection to SuiteCRM" );
	    }
	    $this->loggedin = false;
	    $this->debug_level = 0;
	    $this->search_by_id = false;
    }
    /**//**
     *
     *
     * */
	function login()
	{
		try {
			$this->suitecrm->login();
			//If we made it this far our SuiteCRM class didn't throw an exception
			$this->loggedin = true;
			$this->set( "user_id", $this->suitecrm->get( "user_id" ) );
			//var_dump( $this->user_id );
		} catch( Exception $e )
		{
			
			if( $this->get( "debug_level" ) > 0 )
                        {
                        	echo "*****************************";
                                echo "******RESULTS OF LOGIN*******";
                                echo "*****************************";
                                var_dump( $e->msg() );
                                echo "*****************************";
                                echo "*****************************";
			}

			throw $e;
		}
	}
	/**//***********************************************************
	* Wrapper to origin's unset_var function
	*
	* PHP uses unset to clear a variable.  My origin fcn doesn't use
	* that name.  I had a reason to use unset_var when I wrote it
	* so here I wrap it.
	*
	* @param string
	* @returns none
	*
	***************************************************************/
	function unset( $field )
	{
		$this->unset_var( $field );
	}
	/**//**
	 * Use SuiteCRM to search for a string.
	 *
	 * WARNING: If you set the id field to the id of the record we created
	 * 	searching afterwards will not return what you expected.
	 *
	 * @param NONE but depends on internal variables having been set
	 * @return stdClass result from connector.
	 * */
	function search()
	{
		if( ! $this->loggedin )
		{
			$this->login();
			return $this->search();
		}
		//This would be totally optional.  Searching on an ID
		//would effectively nullify the search string.
		if( $this->search_by_id )
		{
			if( isset( $this->id ) )
			{
				$this->suitecrm->set( "search_id", $this->id); 
			}
			else if( isset( $this->search_id ) )
			{
				$this->suitecrm->set( "search_id", $this->search_id); 
			}
			else
			{
				$this->unset( "search_id" );
				$this->suitecrm->unset( "search_id" );
				//do we need to also temporarily clear ID?
			}
		}
		else
		{
			$this->suitecrm->unset( "search_id" );
		}
		if( false === $this->search_by_id AND isset( $this->search_string ) )
		{
			$this->suitecrm->set( "search_string", $this->search_string ); 
		}
		else
		{
			throw new Exception( $this->get( "module_name" ) . "Can't search without a searchstring" );
		}
		if( isset( $this->search_modules_array ) )
		{
			$this->suitecrm->set( "search_modules_array", $this->get( "search_modules_array" ) ); 
		}
		else
		{
			//throw new Exception( "Can't search without knowing which module_names to search " );
			//Assume we want to search ourself...
			$this->suitecrm->set( "search_modules_array", $this->get( "module_name" ) ); 
		}
		
		if( isset( $this->search_offset ) )
		{
			$this->suitecrm->set( "search_offset", $this->search_offset ); 
		}
		else
		{
			//default to 0.  Effectively get from the first page...
			$this->suitecrm->set( "search_offset", 0 ); 
			$this->set( "search_offset", 0 ); 
		}
		
		if( isset( $this->search_max_results ) )
		{
			$this->suitecrm->set( "search_max_results", $this->search_max_results); 
		}
		else
		{
			//Default to 10
			$this->suitecrm->set( "search_max_results", 10); 
			$this->set( "search_max_results", 10); 
		}
		
		if( isset( $this->search_return_fields_array ) )
		{
			$this->suitecrm->set( "search_return_fields_array", $this->search_return_fields_array ); 
		}
		else
		{
			//An empty array returns all fields
			$this->suitecrm->set( "search_return_fields_array", array() ); 
			$this->set( "search_return_fields_array", array() ); 
		}
		
		if( isset( $this->unified_search_only ) )
		{
			$this->suitecrm->set( "unified_search_only", $this->unified_search_only ); 
		}
		else
		{
			//default to false
			$this->suitecrm->set( "unified_search_only", false ); 
			$this->set( "unified_search_only", false ); 
		}
		
		if( isset( $this->search_favorites_only ) )
		{
			$this->suitecrm->set( "search_favorites_only", $this->search_favorites_only ); 
		}
		else
		{
			//default to false
			$this->suitecrm->set( "search_favorites_only", false ); 
			$this->set( "search_favorites_only", false ); 
		}

		$this->result = $result = $this->suitecrm->search();
		return $result;
		/*
		if( count( $result->entry_list ) > 0 )
		{
			//returns an array of data that can go back into objects.
			foreach( $result->entry_list as $obj_array )
			{
				if( count( $obj_array->records ) > 0 )
				{
					$classtype = $obj_array->name;
				}
				else
					continue;
				foreach( $obj_array->records as $record )
				{
			//		$tmp = new $classtype();
					foreach( $record as $varval )
					{
	    					if( 0 < $this->debug_level )
                					echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
						var_dump( $varval );
						//should have a name and value
						//What do we need to do now?
					}
				}
			}
		}
		else
		{
			if( 1 < $this->debug_level )
			{
                		echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
				var_dump( $result );
                		echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
				var_dump( $this );
			}
		}
		 */
	
	}
    /**//**
     *
     *
     * */
	function set_debug( $level )
	{
		$this->debug_level = $level;
		$this->suitecrm->set( "debug_level", $level );
	}
	/**//**
	 * update a record in SuiteCRM
	 *
	 * The UPDATE is the same as a create,
	 * but the ID is also passed in.
	 *
	 * NOTE: If you have just created a record with the class,
	 * you will need to ->unset( "name_value_list" ) so that we
	 * don't just create a NEW IDENTICAL record.  As this should
	 * really only be a test(ing) case (why would you create a record with bad data)
	 * that we won't force the nuking of the NVL.
	 * 
	 * * @param NONE
	 * * @return NONE but sets result
	 * */
	function update()
	{
		if( !isset( $this->id ) OR strlen( $this->id ) < 1 )
		{
			throw new Exception( "Can't update a record without the ID set" );
		}
		return $this->create();
	}

    
	/**//**
     * Create a record in SuiteCRM
     *
     * @param NONE
     * @return NONE but sets result, ID
     * */
	function create()
	{
		if( isset( $this->suitecrm ) )
		{
			$session_id = $this->suitecrm->get( 'session_id' );
		}
		else
		{
			throw new Exception( $this->get( "module_name" ) . " No SuiteCRM connection so can't create!" );
		}
		if( ! isset( $this->module_name ) OR strlen( $this->module_name ) < 1 )
		{
			throw new Exception( $this->get( "module_name" ) . " Module name not set so can't create!" );
		}
		if( ! isset( $this->name_value_list ) OR count( $this->name_value_list ) < 1 )
		{
			//throw new Exception( "NVL not set so can't create!" );
			$this->objectvars2array();
			if( count( $this->name_value_list ) < 1 )
			{
				throw new Exception( $this->get( "module_name" ) . " NVL not set and couldn't be created so we can't create a SuiteCRM record!" );
			}
		}
		$set_entry_parameters = array(
         		"session" => $session_id,
         		"module_name" => $this->module_name,
         		"name_value_list" =>  $this->name_value_list,
    		);
		$this->result = $this->suitecrm->call("set_entry", $set_entry_parameters);
		//result is a stdClass with id, entry_list (array of NVL for the record)
		if( isset( $this->result->id ) )
		{
			$this->set( "id", $this->result->id );
			if( $this->debug_level > 0 )
			{
				echo "*******************";
				echo "\n\r";
				echo "Result from CREATE";
				echo "\n\r";
				echo "*******************";
				echo "\n\r";
				var_dump( $this->result );
				echo "*******************";
				echo "\n\r";
				echo "*******************";
				echo "\n\r";
			}
		}
		else
		{
				echo "*******************";
				echo "\n\r";
				echo "Result from suitecrm->call";
				echo "\n\r";
				echo "*******************";
				echo "\n\r";
				var_dump( $this->result );
				echo "*******************";
				echo "\n\r";
				echo "*******************";
				echo "\n\r";
			throw new Exception( $this->get( "module_name" ) . " Record not created!" );
		}
		return $this->result;
	}
	/**//***********************************************
	 * After a CREATE, we don't have the ID in our class but it is in the result set.
	 *
	 * @param NONE
	 * @return NONE but sets ID
	 * */
	function get_id_from_create()
	{
		$this->id = $this->result->id;
		return;
	}
	/**//***************************************************
	 * After a search, parse the returned RESULTS for our class's fields
	 *
	 * @param NONE
	 * @returns array of classes with their fields set
	 * */
	function get_fields_from_search_results()
	{
		//From suitecrm - setting the user_id from the return on login
		//			$this->user_id = $login_result->name_value_list->user_id->value;
		/*
		 * Multiple MODULES search
		*object(stdClass)#37 (1) {
		*  ["entry_list"]=>
		*  array(3) {
		*    [0]=>
		*    object(stdClass)#61 (2) {
		*      ["name"]=>
		*      string(8) "Accounts"
		*      ["records"]=>
		*      array(1) {
		*        [0]=>
		*        object(stdClass)#52 (6) {
		*          ["name"]=>
		*          object(stdClass)#60 (2) {
		*            ["name"]=>
		*            string(4) "name"
		*            ["value"]=>
		*            string(12) "Kevin Fraser"
		*          }
		*          ["billing_address_city"]=>
		*          object(stdClass)#54 (2) {
		*            ["name"]=>
		*            string(20) "billing_address_city"
		*            ["value"]=>
		*            string(7) "Airdrie"
		*          }
		*          ["billing_address_country"]=>
		*          object(stdClass)#59 (2) {
		*            ["name"]=>
		*            string(23) "billing_address_country"
		*            ["value"]=>
		*            string(6) "Canada"
		*          }
		*          ["assigned_user_name"]=>
		*          object(stdClass)#58 (2) {
		*            ["name"]=>
		*            string(18) "assigned_user_name"
		*            ["value"]=>
		*            string(5) "kevin"
		*          }
		*          ["date_entered"]=>
		*          object(stdClass)#57 (2) {
		*            ["name"]=>
		*            string(12) "date_entered"
		*            ["value"]=>
		*            string(19) "2018-09-16 23:18:00"
		*          }
		*          ["id"]=>
		*          object(stdClass)#56 (2) {
		*            ["name"]=>
		*            string(2) "id"
		*            ["value"]=>
		*            string(36) "d26e3ff8-a730-59dd-5751-5b9ee4aef6cc"
		*          }
		*        }
		*      }
		*    }
		*/			
		$rec_arr = array();
		$reccount = 0;	
		foreach( $this->result->entry_list as $results )
		{
			//Each entry_list is a stdClass with Name, records
			$classtype = $results->name;
			//20231118 Error on _$classtype
			$cn = "suitecrm_" . $classtype;
			try {
				$rec = new $cn();
				//$rec = new suitecrm_$classtype();
				$reccount++;
				$rec_arr[] = $rec;
				foreach( $results->records as $field_arr )
				{
					foreach( $field_arr as $record )
					{
						//var_dump( $record );
						try {
							$rec->set( $record->name, $record->value );
						} catch( Exception $e )
						{
						}
					}
				}
			} catch( Exception $e )
			{
			}
		}	//foreach entry_list
		return $rec_arr;
	}
	function get_module_fields()
	{
		$res = $this->suitecrm->getFieldList( $this->get( "module_name" ) );
		return $res;
	}
	/**********************************************//**
	 * Designed to help us find the field names for the module.
	 *
	 * */
	function search2( $unused )
	{
		$this->set( "search_string", "%" ); 
		$this->set( "search_modules_array", array( $this->get( "module_name" ) ) ); 
		$this->set( "search_offset", 0 ); 
		$this->set( "search_max_results", 25); 
		//$this->set( "search_return_fields_array", array( "id", "name", "assigned_user" ) ); 
		$this->set( "search_return_fields_array", array( ) ); //Return ALL fields
		//$this->unset( "search_return_fields_array" ); 
		$this->set( "unified_search_only", false ); 
		$this->set( "search_favorites_only", false ); 
		$res = $this->search(); 
		echo "***************************************";
		echo "\n\r";
		echo "*************_BASE SEARCH2*************";
		echo "\n\r";
		echo "***************************************";
		echo "\n\r";
		var_dump( $res );
		echo "***************************************";
		echo "\n\r";
		echo "***************************************";
		echo "\n\r";
	}
	function set_relationship()
	{
		if( ! isset( $this->id ) )
			throw new Exception( "Required field ID not set" );
		if( ! isset( $this->related_module ) )
			throw new Exception( "Required field Related_module not set" );
		if( ! isset( $this->related_id ) )
			throw new Exception( "Required field Related_id not set" );
		$this->suitecrm->set( "related_module", $this->get( "related_module" ) );
		if( is_array( $this->related_id  ) )
			$this->suitecrm->set( "related_ids_array", $this->get( "related_id" ) );
		else
		{
			//Not an array, needs to be an array
			$related = array( $this->get( "related_id" ) );
			$this->suitecrm->set( "related_ids_array", $related );
		}
		$this->suitecrm->set( "id", $this->get( "id" ) );
		$this->suitecrm->set( "module_name", $this->get( "module_name" ) );
		$this->suitecrm->set_relationship();

	}
	function set_parent( $parent_type, $parent_id )
	{
		$this->set( "parent_type", $parent_type );
		$this->set( "parent_id", $parent_id );
	}
	/**//*********************************************************
	* Update the record if it exists and we can positively match, or create new
	*
	* @param none
	* @returns none
	*/
	function update_or_create()
	{
		$bCreate = false;
		if( isset( $this->id ) )
		{
			//we are updating
			$bCreate = TRUE;
		}
		else
		{
			//Need to see if the record already exists
			$this->search();
			//Now we need to determine if there is a match
			$bCreate = $this->score_match();
		}
		if( $bCreate )
		{
			$this->create();
		}
	}
	function score_match()
	{
		throw new Exception( "This function needs to be overridden" );
	}
}



