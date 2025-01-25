<?php


$path_to_root = "../..";

/*******************************************
 * If you change the list of properties below, ensure that you also modify
 * build_write_properties_array
 * */

require_once( '../ksf_modules_common/class.origin.php' );
require_once( '../ksf_modules_common/class.fa_debtors_master.php' );
require_once( $path_to_root . "/includes/db/customers_db.inc" );

class crm_customer extends origin {
	function __construct( $caller )
	{
	}
 	/***********************************************************************
        *Send customers to SuiteCRM
        *
        *@param NONE
        *@returns NONE
        ************************************************************************/
        function send_customers()
        {
        /** /
                //We need to:
                //      1 - Grab the list of customers
		//		 $sql = "SELECT debtor_no FROM ".TB_PREF."debtors_master ";
                //      2 - Grab the details of each customer
                //      3 - Check to see if we have the customer listed in our XREF table
                //      3a - Check the CRM to see if we already have a matching customer
                //      4 - Insert / Update the CRM
                //      5 - Update our XREF table with the relationship.
                require_once( 'class.fa_debtors_master.php' );
		$custs = new fa_debtors_master( $this );
		$custs->select_table( "name", null, null, null );
        /**/
        }

}
