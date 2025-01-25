<?php

require_once( 'class.suitecrm_base.php' );

//http://support.sugarcrm.com/Documentation/Sugar_Developer/Sugar_Developer_Guide_6.5/Application_Framework/Web_Services/Examples/REST/PHP/Creating_or_Updating_a_Record/

class suitecrm_users extends suitecrm_base
{
	/** from API during Login*/
	//protected $user_id;
	protected $user_name;
	protected $first_name;
	protected $last_name;
	protected $email_address;
	protected $user_language;
	protected $user_currency_id;
	protected $user_is_admin;
	protected $user_default_team_id;
	protected $user_default_dateformat;
	protected $user_default_timeformat;
	protected $user_number_separator;
	protected $user_decimal_separator;
	protected $mobile_max_list_entries;
	protected $mobile_max_subpanel_entries;
	protected $user_currency_name;
	/** !login */


	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Users" );
	}
	function create()
	{
		try {
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
			*/
			/**/
			//$this->set( "note_id", $this->result->id );
			if( isset( $this->result->id ) )
				$this->set( "id", $this->result->id );
			else
			{
				if( $this->get( "debug_level" ) > 0 )
				{
				  	echo "*****************************";
				  	echo "**RESULTS OF CREATE ATTEMP***";
				  	echo "*****************************";
					var_dump( $this->result );
				  	echo "*****************************";
				  	echo "*****************************";
				}
				throw new Exception( "Suitecrm_users::Failed to create record!" );
			}
			 /*
			var_dump( $this->document_id );
			echo "\r\n";
			echo "*****************************";
			echo "\r\n";
			 /**/
		}
		catch( Exception $e )
		{
			if( $this->get( "debug_level" ) > 0 )
			{
			  	echo "*****************************";
				echo "\n\r";
			  	echo "***CREATE THREW EXCEPTION ***";
				echo "\n\r";
			  	echo "*****************************";
				echo "\n\r";
				echo $e->getMessage();
				echo "\n\r";
			  	echo "*****************************";
				echo "\n\r";
			  	echo "*****************************";
				echo "\n\r";
			}
				throw $e;
		}
	
	}


}

	

