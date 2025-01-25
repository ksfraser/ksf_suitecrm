<?php

require_once( 'conf.url.php' );
require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/**//******************************************************************
 * Create a Note with an Attachment.
 *
 * From the Example code, NOTES and DOCUMENTS api is identical
 * except for the Module name.
 *
 * */

class suitecrm_documents extends suitecrm_base
{
	var $id;
	//Search returns 4 fields
	protected $document_id;	//Search returns as ID vice document_id
	protected $document_name;
	protected $date_entered;
	protected $assigned_user_name;
	//End of search returns...
	protected $date_modified;
	protected $description;
	protected $deleted;
	protected $category;
	protected $type;
	protected $contact;
	protected $revision;
	protected $file_upload_path;
	protected $upload_method;
	protected $save_filename;
	protected $assigned_user_id;

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Documents" );
	}
	function create()
	{
		parent::create();
		/*
		echo "\r\n";
		echo "*****************************";
			echo "\r\n";
		echo "Document created: \n\r";
		var_dump( $this->result );
		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		 /**/
		$this->set( "document_id", $this->result->id );
		$this->set( "id", $this->result->id );
		 /*
		var_dump( $this->document_id );
		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		 /**/
	}
	/**************************************//**
	 * Attach a document to the "document"
	 *
	 * Assumption that CREATE has already been run
	 * so that document_ID is set.
	 *
	 * ***************************************/
	function upload_file()
	{
	    if( ! isset( $this->document_id ) )
	    {
		    throw new Exception( "Can't attach a document to a record when the record isn't specified" );
	    }

		if( !isset( $this->file_upload_path ) )
			throw new Exception( "Document path not set" );
		$this->suitecrm->set( "file_upload_path", $this->file_upload_path );
		$this->suitecrm->set( "upload_method", "set_document_revision" );
		$this->suitecrm->set( "attach_to_id", $this->document_id );
		$this->result = $this->suitecrm->upload_file();
		if( 1 < $this->debug_level )
		{
		    	echo "<pre>" . "::" . __LINE__ . "::" . __METHOD__ . "\n\r";
		    	print_r($this->result);
			echo "</pre>";
		}
		return $this->result;
	}
	/**********************************************//**
	 * Designed to help us find the field names for the module.
	 *
	 * */
	function search2( $searchstring )
	{
		$this->set( "search_string", $searchstring ); 
		$this->set( "search_modules_array", array( "Documents" ) ); 
		$this->set( "search_offset", 0 ); 
		$this->set( "search_max_results", 25); 
		//$this->set( "search_return_fields_array", array( "id", "name", "assigned_user" ) ); 
		$this->set( "search_return_fields_array", array( ) ); //Return ALL fields
		//$this->unset( "search_return_fields_array" ); 
		$this->set( "unified_search_only", false ); 
		$this->set( "search_favorites_only", false ); 
		$res = parent::search(); 
		var_dump( $res );
	}


}

