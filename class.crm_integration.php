<?php

use Mouf\Html\HtmlElement\HtmlTag; // Importing the HtmlTag class from mouf/html.tags

$path_to_root = "../..";

/*******************************************
 * If you change the list of properties below, ensure that you also modify
 * build_write_properties_array
 * */

require_once( '../ksf_modules_common/class.generic_fa_interface.php' );

class CrmIntegration extends generic_fa_interface {
    protected $idCrmIntegration; // Renamed to camelCase

    function __construct() {
        parent::__construct(); // Ensure parent constructor is called
    }

    function defineTable() {
        $sidl = 'varchar(255)'; // Placeholder for STOCK_ID_LENGTH
        $this->fields_array[] = array('name' => 'description', 'label' => 'Description', 'type' => $sidl, 'null' => 'NOT NULL', 'readwrite' => 'readwrite');
        $this->fields_array[] = array('name' => 'inserted_fa', 'label' => 'Inserted into FA', 'type' => 'bool', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0');
        $this->fields_array[] = array('name' => 'woo_id', 'label' => 'WooCommerce ID', 'type' => 'int(11)', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0');
    }

    function formCrmIntegration() {
        $this->call_table('formCrmIntegrationCompleted', "crm_integration");
    }

    function formCrmIntegrationCompleted() {
        // Implementation for form completion
    }

    function masterForm() {
        global $Ajax; // Assuming $Ajax is defined elsewhere
        $this->notify(__METHOD__ . "::" . __METHOD__ . ":" . __LINE__, "WARN");
        $this->create_full();

        $formDiv = new HtmlTag('div');
        $formDiv->setAttribute('id', 'form');
        echo $formDiv->open();

        $count = $this->fields_array2var();

        $sql = "SELECT ";
        $rowcount = 0;
        foreach( $this->entry_array as $row )
{
            if( $rowcount > 0 ) $sql .= ", ";
            $sql .= $row['name'];
            $rowcount++;
        }
        $sq( .= " from " .  $this->table_details['tablename'];
        if( isset( $this->table_details['orderby'] ) )
            $sql .= " ORDER BY " . $this->table_details['orderby'];

        $this->notify(__METHOD__ . ":" . __METHOD__ . ":" . __LINE__ . ":" . $sql, "WARN");
        $this->notify(__METHOD__ . ":" . __METHOD__ . ":" . __LINE__ . ":" . " Display data", "WARN");
        $this->display_table_with_edit($sql, $this->entry_array, $this->table_details['primarykey']);

        echo $formDiv->close();

        $generateDiv = new HtmlTag('div');
        $generateDiv->setAttribute('id', 'generate');
        echo $generateDiv->open();
        echo $generateDiv->close();
    }
}
