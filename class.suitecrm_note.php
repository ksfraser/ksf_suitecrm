<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

/************************************************************************************************
*	20231120 Creates a Note with an attachment.
*	Doesn't set contact.	A relationship, so maybe can't set the field and have it work.
*	Doesn't set Notes (relationship).
************************************************************************************************

/**//******************************************************************
 * Create a Note with an Attachment.
 *
 * From the Example code, NOTES and DOCUMENTS api is identical
 * except for the Module name.
 *
 * */
class suitecrm_note extends suitecrm_base
{
	var $id;
	protected $note_id;	//returned as ID from a search
	protected $name;	//Subject?
	protected $contact_name;
	protected $filename;	//Attachment
	protected $file_mime_type;
	protected $parent_type;
	protected $note_source;		//Extended
	protected $sms_number;		//ASSIST module?
	protected $portal_flag;		//Display in Portal?
	protected $embed_flag;		//Embed in Email
	protected $description;
	protected $file_upload_path;
	protected $upload_method;
	protected $note;
	//protected $description;		//INHERITED
	protected $notes;	//Relationship?

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Notes" );
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
		if( isset( $this->result->id ) )
		{
			$this->set( "note_id", $this->result->id );
			$this->set( "id", $this->result->id );
		}
		else
		{
			throw new Exception( "Record not created!" );
		}
		 /*
		var_dump( $this->document_id );
		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		 /**/
	
	}
	/**************************************//**
	 * Attach a document to the note
	 *
	 * Assumption that CREATE has already been run
	 * so that note_ID is set.
	 *
	 * ***************************************/
	function upload_file()
	{
	    if( ! isset( $this->note_id ) )
	    {
		    throw new Exception( "Can't attach a document to a record when the record isn't specified" );
	    }

		if( !isset( $this->file_upload_path ) )
			throw new Exception( "Document path not set" );
		$this->suitecrm->set( "file_upload_path", $this->file_upload_path );
		$this->suitecrm->set( "upload_method", "set_note_attachment" );
		$this->suitecrm->set( "attach_to_id", $this->note_id );
		$this->result = $this->suitecrm->upload_file();
		if( 1 < $this->debug_level )
		{
		    	echo "<pre>" . "::" . __LINE__ . "::" . __METHOD__ . "\n\r";
		    	print_r($this->result);
			echo "</pre>";
		}
		return $this->result;
	}

}


