
<?php
define ('SS_ksf_suitecrm', 111<<8);

/***************************************************************************************
 *
 * Hooks is what adds menus, etc to FrontAccounting.
 * It also appears to be called pre and post database transactions
 * for certain modules (see includes/hooks.inc) around line 360
 * 	hook_db_prewrite
 * 	hook_db_postwrite
 * 	hook_db_prevoid
 *
 * Looks like we could also provide our own authentication module
 * 	hook_authenticate (useful for REST?)
 *
 *
 * 	THIS MODULE REQUIRES MODIFIED CUSTOMER AND BRANCH CODE
 * 	sales\includes\db\...
 * 	includes\types.inc
 *
 * ***********************************************************************************/
class hooks_ksf_suitecrm extends hooks {
	
	var $module_name = 'ksf_suitecrm'; 
	function __construct()
	{
		//parent::__construct();	//Error about can't call constructor
	}

	/*
		Install additonal menu options provided by module
	*/
	function install_options($app) {
		global $path_to_root;
		$mod_rel_path =  $path_to_root . '/modules/' . $this->module_name . '/';
		switch($app->id) {
			//case 'GL':
			//case 'system':
			//case 'stock':
			//case 'proj': //dimensions
			//case 'manuf': //manufacturing
			//case 'AP':	//Suppliers
			case 'orders':	//Customer
				$app->add_module( _("SuiteCRM") );
				$app->add_rapp_function(3, _("Synchronize Customers with SuiteCRM"), 
					$mod_rel_path .'/ksf_suitecrm.php', 'SA_ksf_suitecrm');
				break;
			case 'stock':	//Inventory
				$app->add_module( _("SuiteCRM") );
				$app->add_rapp_function(3, _("Synchronize Inventory Items with SuiteCRM"), 
					$mod_rel_path .'/ksf_suitecrm.php', 'SA_ksf_suitecrm');
				break;
		}
	}

	function install_access()
	{
		$security_sections[SS_ksf_suitecrm] = _("ksf_suitecrm");
		$security_areas['SA_ksf_suitecrm'] = array(SS_ksf_suitecrm|101, _("ksf_suitecrm"));
		return array($security_areas, $security_sections);
	}
	function db_postwrite(&$cart, $trans_type)
	{
		//display_notification( "WOO_EXPORT hooks was told about " . $trans_type );
		//this is called every time a CART is written to a db
		//we could use this to send updates for QOH, or every time
		//a new product is added we could send to WOO
		//type 30 == sales_order
		//type 13 == delivery
		//type 12 == invoice?
		//type 10 == payment?
/**/
		if( require_once( 'class.ksf_suitecrm.php' ) ) {
			try {
				switch( $trans_type )
				{
					case ST_JOURNAL: //0);
						break;
					case ST_BANKPAYMENT: //1);
						//We are paying an expense.  Make a NOTE in the Supplier Account in SuiteCRM?
						break;
					case ST_BANKDEPOSIT: //2);
						//We are receiving a payment.  Make a NOTE in the Supplier/Customer Account in SuiteCRM?
						break;
					case ST_BANKTRANSFER: //4);
						//Transfering money between accounts.  Nothing to do in SuiteCRM...
						break;
					case ST_SALESINVOICE: //10);
						//Post a note in the Customer Account in SuiteCRM?
						//Do we need to post an Invoice or match to one in SuiteCRM?
						break;
					case ST_CUSTCREDIT: //11);
						//Customer returned something.  Do we need to adjust an invoice in SuiteCRM?
						break;
					case ST_CUSTPAYMENT: //12);
						//Customer payment.  Note in SuiteCRM?
						break;
					case ST_CUSTDELIVERY: //13);
						//Delivery.  Note in SuiteCRM?   Email the customer?
						break;
					case ST_LOCTRANSFER: //16);
						//Inventory transfer.  Nothing for SuiteCRM, though we should update Woo and Square (not this module)...
						break;
					case ST_INVADJUST: //17);
						//Inventory Adjustment.  Nothing for SuiteCRM, though we should update Woo and Square (not this module)...
						break;
					case ST_PURCHORDER: //18);
						//Customer purchase.  Do we need to find an Opportunity and mark won?  Note against account.
						break;
					case ST_SUPPINVOICE: //20);
						//Invoice from supplier.  Note in SuiteCRM?
						break;
					case ST_SUPPCREDIT: //21);
						//Return to supplier.  Note in SuiteCRM?
						break;
					case ST_SUPPAYMENT: //22);
						//Payment to supplier.  Note in SuiteCRM?
						break;
					case ST_SUPPRECEIVE: //25);
						//Items came in.  Match against opportunities and email clients?
						break;
					case ST_WORKORDER: //26);
						//Do we need to advise customers of the workorder?
						break;
					case ST_MANUISSUE: //28);
						break;
					case ST_MANURECEIVE: //29);
						break;
					case ST_SALESORDER: //30);
						//Note in SuiteCRM.  Match an opportunity?
						break;
					case ST_SALESQUOTE: //32);
						//Note in SuiteCRM.  Match an opportunity?  Match/Generate a quote?
						break;
					case ST_COSTUPDATE: //35);
						//Nothing to do in SuiteCRM
						break;
					case ST_DIMENSION: //40);
						//Nothing to do in SuiteCRM
						break;
					case ST_STATEMENT: //91);
						//Nothing to do in SuiteCRM
						break;
					case ST_CHEQUE: //92);
						//Nothing to do in SuiteCRM
						break;
					default:
						break;
				}	//SWITCH
			} catch( Exception $e )
			{
			}
		}	//IF
/** /
		$old = $cart->pos['pos_account'];
		try {
			//display_notification( __FILE__ . ":" . __LINE__  );
			$pay->select_row();	//Primary Key is set.
			$cart->pos['pos_account'] = $pay->get( "bank_account" );
		} catch( Exception $e )
		{
			if( KSF_FIELD_NOT_SET == $e->getCode() )
			{
				//the bank_account does not match a config in our module so no redirect
				if( FALSE != strpos( $e->getMessage(), "bank_account" ) )
					return true;
			}
			else
				display_error( __METHOD__ . ":" . __LINE__ . " " . $e->getMessage() );
		}
		if( ! $cart->payment_terms['cash_sale'] )
		{
			//Generate a payment
			$cart->payment_terms['cash_sale'] = 1;
		}
		return true;

/**/

		return true;
	}
	/*
	function install_tabs($app)
	{
		$app->add_application(new example_class); // add menu tab defined by example_class
	}
	//
	//	Invoked for all modules before page header is displayed
	//
	function pre_header($fun_args)
	{
	}
	//
	//	Invoked for all modules before page footer is displayed
	//
	function pre_footer($fun_args)
	{
	}

	//
	// Price in words. $doc_type is set to document type and can be used to suppress 
	// price in words printing for selected document types.
	// Used instead of built in simple english price_in_words() function.
	//
	//	Returns: amount in words as string.

	function price_in_words($amount, $doc_type)
	{
	}
	//
	// Exchange rate currency $curr as on date $date.
	// Keep in mind FA has internally implemented 3 exrate providers
	// If any of them supports your currency, you can simply use function below
	// with apprioprate provider set, otherwise implement your own.
	// Returns: $curr value in home currency units as a real number.

	function retrieve_exrate($curr, $date)
	{
//	 	$provider = 'ECB'; // 'ECB', 'YAHOO' or 'GOOGLE'
//		return get_extern_rate($curr, $provider, $date);
		return null;
	}

	// External authentication
	// If used should return true after successfull athentication, false otherwise.
	function authenticate($login, $password)
	{
		return null;
	}
	// Generic function called at the end of Tax Report (report 709)
	// Can be used e.g. for special database updates on every report printing
	// or to print special tax report footer 
	//
	// Returns: nothing
	function tax_report_done()
	{
	}
	// Following database transaction hooks akcepts array of parameters:
	// 'cart' => transaction data
	// 'trans_type' => transaction type

	function db_prewrite(&$cart, $trans_type)
	{
		return true;
	}

	function db_postwrite(&$cart, $trans_type)
	{
		return true;
		//display_notification( "WOO_EXPORT hooks was told about " . $trans_type );
		//this is called every time a CART is written to a db
		//we could use this to send updates for QOH, 
		//or every time a new product is added we could send to WOO
		//Every time a sales order is placed we could send WOO the order
		//Every time a delivery is done update WOO (this allows reviews?)
		//type 30 == sales_order
		//type 13 == delivery
		//type 12 == invoice?
		//type 10 == payment?
	 	 
	}

	function db_prevoid($trans_type, $trans_no)
	{
		return true;
/*
		//Something like:
		/ *	$sql = "
	    	 *	UPDATE ".TB_PREF."bi_transactions
	    	 *		SET status=0
	    	 *		WHERE
		 *		fa_trans_no=".db_escape($trans_no)." AND
		 *		fa_trans_type=".db_escape($trans_type)." AND
		 *		status = 1";
		 *		//display_notification($sql);
		 *	db_query($sql, 'Could not void transaction');
	 	 * /}
	//
	//	This method is called after module install.
	//
	function install_extension($check_only=true)
	{
		return true;
	}
	//
	//	This method is called after module uninstall.
	//
	function uninstall_extension($check_only=true)
	{
		return true;
	}
	//
	//	This method is called on extension activation for company.
	//
	function activate_extension($company, $check_only=true)
	{
		return true;
     		global $db_connections;
                $updates = array(
                        //'install_myapp.sql' => array('assets'),
			'sql/crm_campaign_types.sql' => array('crm_campaign_types'),
			'sql/crm_mailinglist.sql' => array('crm_mailinglist'),

                );

                return $this->update_databases($company, $updates, $check_only);	}
	//
	//	This method is called when extension is deactivated for company.
	//
	function deactivate_extension($company, $check_only=true)
	{
		return true;
  		global $db_connections;
                $updates = array(
                        'drop_myapp.sql' => array('assets')
                );

		return $this->update_databases($company, $updates, $check_only);	}

*/
}
