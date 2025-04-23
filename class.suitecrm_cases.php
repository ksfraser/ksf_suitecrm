<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class SuitecrmCases extends suitecrmBase
{
	//var $id;			//Inherited
//	protected $meeting_id;
	protected $name;		//SUBJECT in vtiger
	protected $description;
	protected $location;
	protected $date_start;		//DTSTART in vtiger
	protected $date_end;		//DTEND in vtiger
	protected $parent_type;
	protected $status;		//EVENTSTATUS in vtiger
	protected $outlook_id;
	protected $sequence;
	protected $accept_status;

	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Cases" );
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
		//$this->set( "note_id", $this->result->id );
		if( isset( $this->result->id ) )
			$this->set( "id", $this->result->id );
		else
			throw new Exception( "Record not created!");
		 /*
		var_dump( $this->document_id );
		echo "\r\n";
		echo "*****************************";
		echo "\r\n";
		 /**/
	
	}
	/**************************************//**
	 * Attach a document to the meeting
	 *
	 * Assumption that this meeting has already been created
	 * so that we have an id set.
	 *
	 * CREATE an attachment, then SOAP CALL "set_meeting_attachment"
	 * Is there an "attach_to_id"?  my refactored code had it set here...
	 *
	 * ***************************************/
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
	/************************************
	 * *the 2 steps for relationships...
	 *
	 * my $link_entry = {
	 * 	module => 'Meetings',
	 * 	meeting_id => $meeting_id,
	 * 	module => 'Contacts',
	 * 	contact_id => $contact_list[$i]->{'id'}
	 * 	};
	 *
	 * 	my $out = $s->create_module_links('Meetings',$meeting_id,'contacts',[$contact_id],$link_entry);
	 * 	my $user_link_entry = {
	 * 		meeting_id => $meeting_id,
	 * 		user_id => $user_id,
	 * 		accept_status => 'accept',
	 * 		required => 1
	 * 	};
	 * 	$out = $s->create_module_links('Meetings',$meeting_id,'users',[$user_id],$user_link_entry);
	 *
	 * ***************************************************/
 

}

