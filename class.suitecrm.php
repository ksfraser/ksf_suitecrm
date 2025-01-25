<?php

require_once( '../ksf_modules_common/class.curl_handler.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/**//************************************************************************************************
 * This class is the connetor to SuiteCRM.  It uses CURL and the REST interface.
 *
 * Someday I might adapt this to use SOAP.  Apparantly you can do SOAP over email.
 *
 * TODO:
 * 	Clean up Exceptions to include codes
 * 	Clean up logging
 * 	Provide Function Documentation
 *
 * */
class suitecrm
{
	protected $url;
	protected $username;
	protected $password;
	protected $user_id;
	protected $session_id;
	protected $loggedin;
	protected $module_name;
	protected $response;
        protected $obj_var = array( "url", "username", "password",  );
	protected $crm_var = array( "crm_api", "un",   "p",         );
	protected $curl_http_code;
	protected $curl_curlinfoarray;
	protected $curl_response_headers;
//SEARCH
	protected $search_string;
	protected $search_modules_array;
	protected $search_offset;
	protected $search_max_results;
	protected $search_return_fields_array;
	protected $unified_search_only;
	protected $search_favorites_only;
	protected $search_id;
//
	protected $isHtaccessProtected;
	protected $htaccessUsername;
	protected $htaccessPassword;
	protected $curl;	//<! Curl object
	protected $name_value_list;	//!< Used during set_entry to create a record
	var $id;
	var $debug_level;
	protected $attach_to_id;	//!< string the ID of the note/document record we are attaching the uploaded file to.
	protected $upload_method;	//!< string the "method" in the call used to upload the file
	protected $save_filename;
	protected $revision;
	protected $file_upload_path;
	protected $related_module;	//!< string module that we are associating this record to. (set_relationship)
	protected $related_ids_array;	//!< array 1D array of IDs in the related module


    function __construct( $url, $username, $password, $module_name )
    {
	    $this->debug_level = 0;
	    $this->url = $url;
	    $this->username = $username;
	    $this->password = $password;
	    $this->module_name = $module_name;
	    $this->loggedin = false;
	    $this->search_id = "";
	//SEARCH defaults
		$this->search_favorites_only = false;
		$this->unified_search_only = false;
		$this->search_offset = 0;
		$this->search_max_results = 10;
    }
	/**//******************************************************************************
	 * Initialize our connection to CURL if it hasn't been done.
	 *
	 * Will set/reset CURL options even if the connection has been initialized earlier
	 * Our connection to WooCommerce needs us to then set CURLOPT_HEADER to False after
	 * running this function
	 *
	 * @param method string HTTP Method (Post/Put/Get/...)
	 * @param params array Passed to constructor of curl_handler
	 * @param headers array Passed to constructor of curl_handler 
	 * @params data array Passed to constructor of curl_handler
	 * @return null.  Sets ->curl
	 * */
	function init_curl( $method = "POST", $params, $headers, $data )
	{
		
		if( !isset( $this->curl ) )
			$this->curl = new curl_handler( $this->debug_level, $this->url, "POST", $params, $headers, $data );
		else{
		    
		    //CURL already initialized
		}
		$this->curl->curl_setopt( CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		$this->curl->curl_setopt( CURLOPT_HEADER, TRUE );	//False for WOO
		$this->curl->curl_setopt( CURLOPT_SSL_VERIFYPEER, FALSE );
		$this->curl->curl_setopt( CURLOPT_RETURNTRANSFER, TRUE );
		$this->curl->curl_setopt( CURLOPT_FOLLOWLOCATION, FALSE );
		$this->curl->curl_setopt( CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		if($this->isHtaccessProtected == TRUE){
			$this->curl->set_htaccessProtected( $this->htaccessUsername, $this->htaccessPassword);
		}
		return;
	}

	/**//**************************************************
	 * function to make cURL request
	 *
	 * @param suite_method string method to pass data to Suite. NOT HTTP Methods
	 * @param parameters array data to be passed to SuiteCRM
	 * @return stdClass response from SuiteCRM API
	 * */
    /*@stdClass@*/function call($suite_method, $parameters)
    {
	    ob_start();
	    $this->response = "";
	    $params = array();
	    $headers = array();
	    $jsonEncodedData = json_encode($parameters);
	    $data = array(
		    "method" => $suite_method,
		    "input_type" => "JSON",
		    "response_type" => "JSON",
		    "rest_data" => $jsonEncodedData
	    );
	    
	    if( !isset( $this->curl ) )
		    $this->init_curl( $suite_method, $params, $headers, $data );
	    else
	    {
		    $this->curl->set( "data", $data );
		   // $this->curl->set( "method", $suite_method );	//Setting the method causes weird things to happen.  Probably because it should be an HTTP method, not Suite method!!!
		    $this->curl->set( "params", $params );
		    $this->curl->set( "headers", $headers );
	    }
	    $res = $this->curl->curl_exec();
	    
	    if( false !== $res )
	    {
		    $response = $this->curl->get( 'response_body' );
		    $this->curl_http_code = $this->curl->get( 'response_HTTP_code');
		    $this->curl_curlinfoarray = $this->curl->get( 'curlinfoarray');
		    $this->curl_response_headers = $this->curl->get( 'response_headers');
		    /*stdClass*/$decoded = json_decode( $response );
		    $this->response = $decoded;
		    ob_end_flush();
		    if( $this->debug_level > 1 )
		    {
		    	echo $this->get( "module_name" ) . " response\n\r";
		    	var_dump( $response );
		    	echo $this->get( "module_name" ) . " decoded\n\r";
		    	var_dump( $decoded );
		    }
		    return $this->response;
		}
	    else
	    {
		    throw new Exception( $this->get( "module_name" ) . " CURL returned False.  Couldn't connect? ::" . $suite_method );
	    }
    }
	/**//*******************************************************************
	 * Login to SuiteCRM
	 *
	 * Using variables of the class, build the Login data and connect to SuiteCRM
	 *
	 * @exception catches and re-throws exceptions from CURL
	 * @param NONE
	 * @returns NONE sets session_id
	 *
	 * */
    function login()
    {
    	//$ldap_enc_key = substr(md5($ldap_enc_key), 0, 24);
	$login_parameters = array(
		"user_auth" => array(
	        	"user_name" => $this->username,
	              	//"password" => bin2hex(mcrypt_cbc(MCRYPT_3DES, $ldap_enc_key, $password, MCRYPT_ENCRYPT, 'password')),
		      	"password" => md5($this->password),
		      	"version" => "1",
		      	//"pass_clear" => $password,
		),
	        "application_name" => "FrontAccounting",
	        "name_value_list" => array(),
	);

	try 
	{
		$login_result = $this->call("login", $login_parameters);
		if( $this->debug_level > 2 )
		{
			echo "*********************************************";
			echo "\n\r";
			echo "*****************Login Results***************";
			echo "\n\r";
			echo "*********************************************";
			echo "\n\r";
			var_dump( $login_result );
			echo "*********************************************";
			echo "\n\r";
			echo "*********************************************";
			echo "\n\r";
		}
		if( NULL == $login_result )
		{
			//OH OH
			throw new Exception( $this->get( "module_name" ) . " Login Result came back NULL", KSF_INVALID_DATA_TYPE );
		}
		else
		{
			//get session id
			if( isset( $login_result->id ) )
			{
				//echo "Setting Session ID\n\r";
				$this->session_id = $login_result->id;
			}
			else
			{
				//echo "Setting Session ID to a guess\n\r";
				$this->session_id = $this->id;
			}
			$this->loggedin = true;
			//Get info about the logged in User
			if( $login_result->module_name == "Users" )
			{
				$this->user_id = $login_result->name_value_list->user_id->value;
				//var_dump( $this->user_id );
			}
		}
	    }
	    catch( Exception $e )
	    {
		    throw $e;
	    }
	    $this->response = null;
    }
    /**//**
     *
     *
     * */
    /*string*/function set_entry()
    {
	    if( 0 < $this->debug_level )
    		echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
	    if( ! isset( $this->session_id ) )
	    {
		    //Not logged in?
		    try
		    {
			    $this->login();
		    }
		    catch( Exception $e )
		    {
			    throw $e;
		    }
	    }
        $parameters = array(
        	"session" => $this->session_id,
                "module_name" => $this->module_name,
                //Record attributes
                    "name_value_list" => $this->name_value_list,
	    );
	    	if( 1 < $this->debug_level )
		{
                	echo "<pre>" . __LINE__ . '\n\r';
                	print_r($parameters);
			echo "</pre>". '\n\r';
		}
            $result = $this->call("set_entry", $parameters);
		if( isset( $result->id ) )
            		$this->id = $result->id;
		else
			throw new Exception( $result->description );
	    	if( 1 < $this->debug_level )
		{
                	echo "<pre>" . __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
                	print_r($result);
			echo "</pre>";
		}
		return $result->id;
    }
    /**//**
     *
     *
     * */
    /*string*/function set_relationship()
    {
	    if( 0 < $this->debug_level )
    		echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
	    if( ! isset( $this->session_id ) )
	    {
		    //Not logged in?
		    try
		    {
			    $this->login();
		    }
		    catch( Exception $e )
		    {
			    throw $e;
		    }
	    }
        $parameters = array(
        	"session" => $this->session_id,
                "module_name" => $this->module_name,
                //Record attributes
		"name_value_list" => $this->name_value_list,
		"module_id" => $this->id,
		"link_field_name" => $this->related_module,
		"related_ids" => array( $this->related_ids_array ),
		"delete" => 0
	    );
	    	if( 1 < $this->debug_level )
		{
                	echo "<pre>" . __LINE__ . '\n\r';
                	print_r($parameters);
			echo "</pre>". '\n\r';
		}
		$result = $this->call("set_relationship", $parameters);
		if( 1 < $this->debug_level )
		{
                	echo "<pre>" . __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
                	print_r($result);
			echo "</pre>";
		}
		return $result->id;
    }
    /**//**
     *
     *
     * */
    function update()
    {
	    if( 0 < $this->debug_level )
                echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
	    //the ID parameter needs to be set to update something.
	    $idset = false;
	    foreach( $this->name_value_list as $arr )
	    {
		    if( $arr['name'] == "id" )
			    $idset = true;
	    }
	    if( ! $idset )
	    {
		    throw new Exception( "Can't update without ID of what to update being set" );
	    }

	    //If we got this far we can update the entry
	    return $this->set_entry();

    }
    /**//**
     *
     *
     * */
    function create()
    {
	    if( ! isset( $this->name_value_list ) )
		    $this->name_value_list = $this->objectvars2array();
	    return $this->set_entry();

    }
    /**//**
     *
     *
     * */
    function upload_file()
    {
	    if( 0 < $this->debug_level )
                echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
	    if( ! isset( $this->session_id ) )
	    {
		    //Not logged in?
		    try
		    {
			    $this->login();
		    }
		    catch( Exception $e )
		    {
			    throw $e;
		    }
	    }
	    if( ! isset( $this->file_upload_path ) )
	    {
		    throw new Exception( "Can't upload a file that hasn't been specified" );
	    }
	    $contents = file_get_contents( $this->file_upload_path );
	    if( ! isset( $this->attach_to_id ) )
	    {
		    throw new Exception( "Can't attach a document to a record when the record isn't specified" );
	    }
	    if( ! isset( $this->save_filename ) )
	    {
		    //throw new Exception( "Can't attach a document to a record when the record isn't specified" );
		    $this->save_filename = basename( $this->file_upload_path );
	    }
    	    if( ! isset( $this->revision ) )
	    {
		    //throw new Exception( "Can't attach a document to a record when the record isn't specified" );
		    $this->revision = 1;
	    }

	    $parameters = array(
		        //session id
		        "session" => $this->session_id,
		        //The attachment details
		        "note" => array(
				//The ID of the parent document.
				'id' => $this->attach_to_id,
				//The binary contents of the file.
				'file' => base64_encode($contents),
				//The name of the file
				'filename' => $this->save_filename,
				//The revision number
				'revision' => (string)$this->revision,
		        ),
		);
	    //if( $this->debug_level > 0 )
		    var_dump( $parameters );
	
		$result = $this->call($this->upload_method, $parameters);
		return $result;
    }
    /**//**
     *
     *
     * */
    function get_entry_list( $module, $query_where /*The SQL WHERE clause without the word "where" */ )
    {
	    if( 0 < $this->debug_level )
                echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
	    if( ! isset( $this->session_id ) )
	    {
		    //Not logged in?
		    try
		    {
			    $this->login();
		    }
		    catch( Exception $e )
		    {
			    throw $e;
		    }
	    }
	$get_entries_count_parameters = array(
		//Session id
                'session' => $this->session_id,
                //The name of the module from which to retrieve records
                'module_name' => $module,
                //The SQL WHERE clause without the word "where".
                'query' => $query_where,
                //If deleted records should be included in results.
                //           'deleted' => false
	);
        $result = $client->call('get_entry_list', $get_entries_count_parameters);
        $entry_list = $result['entry_list'];
        foreach($entry_list as $entry)
        {
        	foreach($entry['name_value_list'] as $field)
		{
			$this->$field['name'] = $field['value'];
       		}
    	}
    }
    	/**//**
	 * Request that SuiteCRM return matching search results.
	 *
	 * Sets some defaults if the class doesn't already have them
	 * set.  However others that can't be defaulted throw an
	 * Exception.
	 *
	 * @param NONE but uses internal variables.
     	 * @return stdClass response from (SuiteCRM API) ->call
    	 * */
	function search()
	{
		if(! isset( $this->search_string ) )
		{
			throw Exception( "Can't search without a searchstring" );
		}
		if(! isset( $this->search_modules_array ) )
		{
			throw new Exception( "Can't search without knowing which modules to search " );
		}
		
		if(! isset( $this->search_offset ) )
		{
			//default to 0.  Effectively get from the first page...
			$this->set( "search_offset", 0 ); 
		}
		if(! isset( $this->search_max_results ) )
		{
			//Default to 10
			$this->set( "search_max_results", 10); 
		}
		if( ! isset( $this->search_return_fields_array ) )
		{
			//An empty array returns all fields
			$this->set( "search_return_fields_array", array() ); 
		}
		if( ! isset( $this->unified_search_only ) )
		{
			//default to false
			$this->set( "unified_search_only", false ); 
		}
		if(! isset( $this->search_favorites_only ) )
		{
			$this->set( "search_favorites_only", false ); 
		}
	    if( ! isset( $this->session_id ) )
	    {
		    //Not logged in?
		    try
		    {
			    $this->login();
		    }
		    catch( Exception $e )
		    {
			    throw $e;
		    }
	    }


		$search_param = array(
                 	"session" => $this->session_id,
			"search_string" => $this->search_string,
			"modules" => $this->search_modules_array,
			"offset" => $this->search_offset,
			"max_results" => $this->search_max_results,
			"selected_fields" => $this->search_return_fields_array,    //An array of fields to return.
        						//If empty the default return fields will be from 
							//the active listviewdefs.

			"unified_search_only" => $this->unified_search_only, 	//If the search is to only search 
										//modules participating in the unified search.
			"favorites" => $this->search_favorites_only	//If only records marked as favorites should be returned
		);
		if( isset( $this->search_id ) )
		{
			//Filters records by the assigned user ID.
        		//Leave this empty if no filter should be applied.
			$search_param["id"] = $this->search_id;
		}

		$result = $this->call( "search_by_module", $search_param );
	    	if( 1 <= $this->debug_level )
		{
                	echo "<pre>";
                	print_r($result);
			echo "</pre>";
		}
		return $result;
	}
    /**//**
     * Set a field to a value.
     *
     * Allows the setting of protected/private variables to outsiders.
     * Does not currently do any sanity checking.
     *
     * @param param string Field (class Variable) to set
     * @param value mixed value to set the field
     * @return NONE
     * */
    function set( $param, $value )
    {
	    $this->$param = $value;
    }
    function unset( $param )
    {
	    unset( $this->$param );
    }
    /**//**
     * Retrieve the class variable's value
     *
     * Allows the retrieval of protected/Private variables
     * @param param string field (var) name
     * @returns mixed value
     * */
    function get( $param )
    {
	    return $this->$param;
    }
	/***************************************************************//**
	* Replace the name of a VAR in our object with the associated field in SuiteCRM
	*
	******************************************************************/
 	/*@array@*/function objectvars2array()
        {
                $val = array();
                foreach( get_object_vars( $this ) as $key => $value )
                {
                        $key = str_replace( $this->obj_var, $this->crm_var, $key );
                        //if( $key == "product_url" )
			//      $key = "url";
			if( "id" != $key )	//Not used for CREATE but needed for UPDATE.
				if( isset( $this->$key ) )
		                        $val[] = array( "name" => $key, "value" => $this->$key );
                }
                return $val;
	}
    /**//**
     *
     * @returns mixed list of fields for the module
     * */
    function getFieldList( $module = null )
    {
	    if( null === $module )
	    {
		    throw new Exception( "Can't request field list from a NULL module" );
	    }
	    if( ! isset( $this->session_id ) )
	    {
		    //Not logged in?
		    try
		    {
			    $this->login();
		    }
		    catch( Exception $e )
		    {
			    throw $e;
		    }
	    }

		$set_entry_parameters = array(	 
			"session" => $this->session_id,
			"module_name"	=> $module
		);
		$result = $this->call("get_module_fields", $set_entry_parameters);
		return $result;
    }
    /**//**
     * Attach a Note to another record.
     *
     * As we are attaching a doc to a Note, we need to have
     * received an ID back from when we created the note.
     * We do not do any sanity checking on that ID, so in theory
     * we could try attaching a file to a different record.  Don't
     * know if that would fail or succeed as far as SuiteCRM is concerned.
     *   (Attaching to a DOCUMENT is the same except for the method).
     *
     * @param filename string path to file
     * @param savename string (optional) name to save the file in the attachment.  Will be set to filename if not passed in.
     * @return bool Success or Failure - do we have an ID from the attachment.
     * */
    /*@bool@*/function setNoteAttachment( $filename, $savename = null )
    {
	    if( 0 < $this->debug_level )
                echo  __LINE__ . "::" . __FILE__ . "::" . __METHOD__ . "\n\r";
	    if( ! isset( $this->session_id ) )
	    {
		    //Not logged in?
		    try
		    {
			    $this->login();
		    }
		    catch( Exception $e )
		    {
			    throw $e;
		    }
	    }
	if( !file_exists( $filename ) )
		return FALSE;
	if( null == $savename )
		$savename = basename( $filename );
	    if( $this->id != '' )
	    {
		    	$attachment=array( 'id' => $this->id,
			    	'filename' => $savename,
				'file_mime_type' => mime_content_type( $filename ),
				'file' => base64_encode( file_get_contents ($filename) )
				);								
			$note_attachment=array( 'session' => $this->session_id,
						'note' 	  => $attachment );
			$result = $this->call('set_note_attachment', $note_attachment);
	    }
	    if( isset( $result->id ) )
		    return TRUE;
	    else
		    return FALSE;
    }
}


