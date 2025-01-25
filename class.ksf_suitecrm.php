<?php


$path_to_root = "../..";

/*******************************************
 * If you change the list of properties below, ensure that you also modify
 * build_write_properties_array
 * */

require_once( '../ksf_modules_common/class.table_interface.php' ); 
require_once( '../ksf_modules_common/class.generic_fa_interface.php' );

//THese TYPE defines are used in the xref table to indicate what the index refers to
define( 'CATEGORIES_TYPE', 10 );
define( 'PRODUCTS_TYPE', 20 );
define( 'QUOTES_TYPE', 30 );
define( 'ORDERS_TYPE', 40 );
define( 'CUSTOMERS_TYPE', 50 );

/*************************************************************//**
 * Interface between FrontAccounting and SuiteCRM.
 *
 * Inherits:
 *                 function __construct( $host, $user, $pass, $database, $pref_tablename )
                function eventloop( $event, $method )
                function eventregister( $event, $method )
                function add_submodules()
                function module_install()
                function install()
                function loadprefs()
                function updateprefs()
                function checkprefs()
                function call_table( $action, $msg )
                function action_show_form()
                function show_config_form()
                function form_export()
                function related_tabs()
                function show_form()
                function base_page()
                function display()
                function run()
                function modify_table_column( $tables_array )
                / *@fp@* /function append_file( $filename )
                /*@fp@* /function overwrite_file( $filename )
                /*@fp@* /function open_write_file( $filename )
                function write_line( $fp, $line )
                function close_file( $fp )
                function file_finish( $fp )
                function backtrace()
                function write_sku_labels_line( $stock_id, $category, $description, $price )
		function show_generic_form($form_array)
 * Provides:
        function __construct( $prefs )
        function define_table()
        function form_ksf_suitecrm
        function form_ksf_suitecrm_completed
        function action_show_form()
        function install()
        function master_form()
 * 
 *
 * ***************************************************************/
class ksf_suitecrm extends generic_fa_interface {
	var $id_ksf_suitecrm;	//!< Index of table
	var $table_interface;
	function __construct( $prefs )
	{
		parent::__construct( null, null, null, null, $prefs );	//generic_interface has legacy mysql connection
									//not needed with the $prefs
/**/
		$this->config_values[] = array( 'pref_name' => 'lastoid', 'label' => 'Last Order Exported' );
		$this->config_values[] = array( 'pref_name' => 'debug', 'label' => 'Debug (0,1+)' );
		$this->tabs[] = array( 'title' => 'Config Updated', 'action' => 'update', 'form' => 'checkprefs', 'hidden' => TRUE );
		$this->tabs[] = array( 'title' => 'Configuration', 'action' => 'config', 'form' => 'action_show_form', 'hidden' => FALSE );
/**/
		$this->tabs[] = array( 'title' => 'Install Module', 'action' => 'create', 'form' => 'install', 'hidden' => TRUE );
		$this->tabs[] = array( 'title' => 'ksf_suitecrm Updated', 'action' => 'form_ksf_suitecrm_completed', 'form' => 'form_ksf_suitecrm_completed', 'hidden' => TRUE );
		$this->tabs[] = array( 'title' => 'Update ksf_suitecrm', 'action' => 'form_ksf_suitecrm', 'form' => 'form_ksf_suitecrm', 'hidden' => FALSE );
		$this->tabs[] = array( 'title' => 'Init Tables', 'action' => 'init_tables_form', 'form' => 'init_tables_form', 'hidden' => FALSE );
		$this->tabs[] = array( 'title' => 'Init Tables Completed', 'action' => 'init_tables_complete_form', 'form' => 'init_tables_complete_form', 'hidden' => TRUE );
	
$this->tabs[] = array( 'title' => 'Categories Export to SuiteCRM', 'action' => 'export_categories_form', 'form' => 'export_categories_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Categories Exported to SuiteCRM', 'action' => 'exported_categories_form', 'form' => 'exported_categories_form', 'hidden' => TRUE );

$this->tabs[] = array( 'title' => 'Products Export to SuiteCRM', 'action' => 'export_products', 'form' => 'export_products_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Products Exported to SuiteCRM', 'action' => 'exported_products', 'form' => 'exported_products_form', 'hidden' => TRUE );

$this->tabs[] = array( 'title' => 'Quote Export to SuiteCRM', 'action' => 'export_quotes', 'form' => 'export_quotes_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Quote Exported to SuiteCRM', 'action' => 'exported_quotes', 'form' => 'exported_quotes_form', 'hidden' => TRUE );
	
$this->tabs[] = array( 'title' => 'Quote Import to SuiteCRM', 'action' => 'import_quotes', 'form' => 'import_quotes_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Quote Imported to SuiteCRM', 'action' => 'imported_quotes', 'form' => 'imported_quotes_form', 'hidden' => TRUE );
	
$this->tabs[] = array( 'title' => 'Orders Export to SuiteCRM', 'action' => 'export_orders_form', 'form' => 'export_orders_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Orders Exported to SuiteCRM', 'action' => 'exported_orders_form', 'form' => 'exported_orders_form', 'hidden' => TRUE );

$this->tabs[] = array( 'title' => 'Orders Import from SuiteCRM', 'action' => 'import_orders_form', 'form' => 'import_orders_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Orders Imported from SuiteCRM', 'action' => 'imported_orders_form', 'form' => 'imported_orders_form', 'hidden' => TRUE );

$this->tabs[] = array( 'title' => 'Customer Export to SuiteCRM', 'action' => 'export_customers_form', 'form' => 'export_customers_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Customer Exported from SuiteCRM to SuiteCRM', 'action' => 'exported_customers_form', 'form' => 'exported_customers_form', 'hidden' => TRUE );

$this->tabs[] = array( 'title' => 'Customer Import from SuiteCRM', 'action' => 'import_customers_form', 'form' => 'import_customers_form', 'hidden' => FALSE );
$this->tabs[] = array( 'title' => 'Customer Imported from SuiteCRM', 'action' => 'imported_customers_form', 'form' => 'imported_customers_form', 'hidden' => TRUE );
			
		//We could be looking for plugins here, adding menu's to the items.
		$this->add_submodules();
		$this->table_interface = new table_interface();
		$this->define_table();
							
	}
	function define_table()
	{
		$this->table_interface->table_details['tablename'] = TB_PREF . 'ksf_suitecrm';
		$this->table_interface->fields_array[] = array('name' => 'id', 'type' => 'int(11)', 'auto_increment' => 'yup' );
		$this->table_interface->fields_array[] = array('name' => 'updated_ts', 'type' => 'timestamp', 'null' => 'NOT NULL', 'default' => 'CURRENT_TIMESTAMP');
		$this->table_interface->fields_array[] = array('name' => 'fa_type', 'type' => 'varchar(32)' );
		$this->table_interface->fields_array[] = array('name' => 'fa_index', 'type' => 'int(11)' );
		$this->table_interface->fields_array[] = array('name' => 'suitecrm_guid', 'type' => 'varchar(64)' );

		$this->table_interface->table_details['primarykey'] = "id";
	
		//$this->table_interface->fields_array[] = array('name' => 'stock_id', 'label' => 'SKU', 'type' => $sidl, 'null' => 'NOT NULL',  'readwrite' => 'readwrite');
		$sidl = 'varchar(' . STOCK_ID_LENGTH . ')';
		$descl = 'varchar(' . DESCRIPTION_LENGTH . ')';

		$this->table_interface->fields_array[] = array('name' => 'description', 'label' => 'Description', 'type' => $descl, 'null' => 'NOT NULL',  'readwrite' => 'readwrite' );
		$this->table_interface->fields_array[] = array('name' => 'inserted_fa', 'label' => 'Inserted into FA', 'type' => 'bool', 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->table_interface->fields_array[] = array('name' => 'woo_id', 'label' => 'WooCommerce ID', 'type' => 'int(11)', 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );

		$this->table_interface->table_details['primarykey'] = 'id';
//		$this->table_interface->table_details['orderby'] = 'sku';
//		$this->table_interface->table_details['index'][0]['type'] = 'unique';
//		$this->table_interface->table_details['index'][0]['columns'] = "stock_id, sku";
//		$this->table_interface->table_details['index'][0]['keyname'] = "stock_id-sku";
//
//		//$this->table_interface->table_details['foreign'][0] = array( 'column' => "variablename", 'foreigntable' => "woo_prod_variable_variables", "foreigncolumn" => "variablename", "on_update" => "restrict", "on_delete" => "restrict" );	
//		//$this->table_interface->table_details['foreign'][1] = array( 'column' => "stock_id", 'foreigntable' => "woo_prod_variable_master", "foreigncolumn" => "stock_id", "on_update" => "restrict", "on_delete" => "restrict" );
	}
	function form_ksf_suitecrm()
	{
		$this->call_table( 'form_ksf_suitecrm_completed', "ksf_suitecrm" );
	}
	function form_ksf_suitecrm_completed()
	{	//Need to add code here to do whatever this submodule is for...
	}
	function action_show_form()
	{
		$this->install();
		parent::action_show_form();
	}
	function install()
	{
		$this->table_interface->create_table();
		parent::install();
	}
	/*********************************************************************************//**
	 *master_form
	 *	Display the summary of items with edit/delete
	 *		
	 *	assumes entry_array has been built (constructor)
	 *	assumes table_details has been built (constructor)
	 *	assumes selected_id has been set (constructor?)
	 *	assumes iam has been set (constructor)
	 *
	 * ***********************************************************************************/
	function master_form()
	{
		global $Ajax;
		$this->notify( __METHOD__ . "::"  . __METHOD__ . ":" . __LINE__, "WARN" );
		$this->create_full();
		div_start('form');
		$count = $this->fields_array2var();
		
		$sql = "SELECT ";
		$rowcount = 0;
		foreach( $this->entry_array as $row )
		{
			if( $rowcount > 0 ) $sql .= ", ";
			$sql .= $row['name'];
			$rowcount++;
		}
		$sql .= " from " . $this->table_interface->table_details['tablename'];
		if( isset( $this->table_interface->table_details['orderby'] ) )
			$sql .= " ORDER BY " . $this->table_interface->table_details['orderby'];
	
		$this->notify( __METHOD__ . ":" . __METHOD__ . ":" . __LINE__ . ":" . $sql, "WARN" );
		$this->notify( __METHOD__ . ":" . __METHOD__ . ":" . __LINE__ . ":" . " Display data", "WARN" );
		$this->display_table_with_edit( $sql, $this->entry_array, $this->table_interface->table_details['primarykey'] );
		div_end();
		div_start('generate');
		div_end();
	}
	/***********************************************************************
	*After Send customers to SuiteCRM
	*
	*@param NONE
	*@returns NONE
	************************************************************************/
	function exported_customers_form()
	{
	/** /
		//We need to:
		//	1 - Grab the list of customers
		//	2 - Grab the details of each customer
		//	3 - Check to see if we have the customer listed in our XREF table
		//	3a - Check the CRM to see if we already have a matching customer
		//	4 - Insert / Update the CRM
		//	5 - Update our XREF table with the relationship.
		require_once( 'class.crm_customer.php' );
		$customer = new crm_customer( $this );
		$customer->debug = $this->debug;
		$customer->send_customers();
            	display_notification( $customer->sentcount . " Customers sent and " . $customer->updatecount . " updated.");
		//$this->call_table( 'export_rest_customer', "Export Another" );
	/**/
	}
	/***********************************************************************
	*Send customers to SuiteCRM
	*
	*@param NONE
	*@returns NONE
	************************************************************************/
	function export_customers_form()
	{
	/**/
		 //Display the launch form
		$this->call_table( 'exported_customers_form', "Export Customers to CRM" );
	/**/
	}
	/***********************************************************************
	*After Send products to SuiteCRM
	*
	*@param NONE
	*@returns NONE
	************************************************************************/
	function exported_products_form()
	{
	/** /
		require_once( 'class.woo_product.php' );
		$woo_product = new woo_product( $this->woo_server, $this->woo_rest_path, $this->woo_ck, $this->woo_cs, $this->environment, $this );
		$woo_product->debug = $this->debug;
		$sentcount = $woo_product->send_products();
            	display_notification( $sentcount . " Products sent and " . $updatecount . " updated.");
		//$this->call_table( 'export_rest_product', "Export Another" );
	/**/
	}
	/***********************************************************************
	*After Send products to SuiteCRM
	*
	*@param NONE
	*@returns NONE
	************************************************************************/
	function export_product_form()
	{
	/** /
		$missing_sql = "select sm.stock_id, sm.description, c.description, sm.inactive, sm.editable 
				from " . TB_PREF . "stock_master sm, " . TB_PREF . "stock_category c
				where sm.category_id = c.category_id and sm.stock_id in (select stock_id from " . TB_PREF . "woo)";
		 global $all_items;
	  	//stock_items_list($name, $selected_id, $all_option, $submit_on_change,
	        //        array('cells'=>true, 'show_inactive'=>$all), $editkey);
		//function stock_items_list($name, $selected_id=null, $all_option=false,
	        				//$submit_on_change=false, $opts=array(), $editkey = false)
		$selected_id = "0";
		$name = "";
		$editkey = TRUE;
		$opts = array('cells'=>true, 'show_inactive'=>'1');
		$all_option = FALSE;
		$submit_on_change = TRUE;
	        //if ($editkey)
	                set_editor('item', $name, $editkey);
		start_form();

		start_table();
		table_section_title(_("Export a Product to WOO via REST"));
	//	label_row(_("No Transaction History (no inventory movement):"), NULL);
		label_row("&nbsp;", NULL);
		label_row("Press F4 to pop open a window to edit the item details.  The details WON'T make it to WOO until the 'All Products Export' routine is rerun", null);

		table_section(1);
	        $ret = combo_input($name, $selected_id, $missing_sql, 'stock_id', 'sm.description',
	        array_merge(
	          array(
	                'format' => '_format_stock_items',
	                'spec_option' => $all_option===true ?  _("All Items") : $all_option,
	                'spec_id' => $all_items,
			'search_box' => true,
	        	'search' => array("sm.stock_id", "c.description","sm.description"),
	                'search_submit' => get_company_pref('no_item_list')!=0,
	                'size'=>10,
	                'select_submit'=> $submit_on_change,
	                'category' => 2,
	                'order' => array('c.description','sm.stock_id')
	          ), $opts) );
			echo $ret;
	  	//echo stock_items_list($name, $selected_id, $all_option, $submit_on_change,
	        //        array('cells'=>true, 'show_inactive'=>$all), $editkey);
		end_table(); 
		submit_center( "exported_rest_products", "Export" );
		$this->call_table( 'exported_rest_products', "Send Product via REST to WOO" );
		end_form();
	/**/
	}

	
}
