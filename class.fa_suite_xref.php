<?php

/**//**************************************************************************
* A table to track our records that we've sent to SuiteCRM.
*
*/

require_once( '../ksf_modules_common/class.table_interface.php' );

/**//***************************************
*
*
*/
class fa_suite_xref extends table_interface
{


	function define_table()
	{
		/**
		 * Moving back into ksf_suitecrm
		 *
		 *
		$this->fields_array[] = array('name' => 'id', 'type' => 'int(11)', 'auto_increment' => 'yup' );
		$this->fields_array[] = array('name' => 'updated_ts', 'type' => 'timestamp', 'null' => 'NOT NULL', 'default' => 'CURRENT_TIMESTAMP');
		$this->fields_array[] = array('name' => 'fa_type', 'type' => 'varchar(32)' );
		$this->fields_array[] = array('name' => 'fa_index', 'type' => 'int(11)' );
		$this->fields_array[] = array('name' => 'suitecrm_guid', 'type' => 'varchar(64)' );

		$this->table_details['tablename'] = $this->company_prefix . "fa_suite_xref";
		$this->table_details['primarykey'] = "id";
		/*
		$this->table_details['index'][0]['type'] = 'unique';
		$this->table_details['index'][0]['columns'] = "order_id,first_name,last_name,address_1,city,state";
		$this->table_details['index'][0]['keyname'] = "order-billing_address_customer";
		$this->table_details['index'][1]['type'] = 'unique';
		$this->table_details['index'][1]['columns'] = "customer_id,first_name,last_name,address_1,city,state";
		$this->table_details['index'][1]['keyname'] = "customer-billing_address_customer";
		 */
	}

}
