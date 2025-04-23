<?php

use Mouf\Html\Tags\Div; // Corrected to use the Div class
use Mouf\Html\HtmlElement\HtmlString; // For adding plain text as a child

$path_to_root = "../..";

/*******************************************
 * If you change the list of properties below, ensure that you also modify
 * build_write_properties_array
 * */

require_once( '../ksf_modules_common/class.generic_fa_interface.php' );

class CrmProduct extends generic_fa_interface {
    protected $idCrmProduct; // Renamed to camelCase

    function __construct() {
        parent::__construct(); // Ensure parent constructor is called
    }

    function defineTable() {
        $sidl = 'varchar(255)'; // Placeholder for STOCK_ID_LENGTH
        $this->fields_array[] = array('name' => 'description', 'label' => 'Description', 'type' => $sidl, 'null' => 'NOT NULL', 'readwrite' => 'readwrite');
        $this->fields_array[] = array('name' => 'inserted_fa', 'label' => 'Inserted into FA', 'type' => 'bool', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0');
        $this->fields_array[] = array('name' => 'woo_id', 'label' => 'WooCommerce ID', 'type' => 'int(11)', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0');
    }

    function formCrmProduct() {
        $this->call_table('formCrmProductCompleted', "crm_product");
    }

    function formCrmProductCompleted() {
        // Implementation for form completion
    }

    function masterForm() {
        global $Ajax; // Assuming $Ajax is defined elsewhere
        $this->notify(__METHOD__ . "::" . __METHOD__ . ":" . __LINE__, "WARN");
        $this->create_full();

        $formDiv = new Div();
        $formDiv->addClass('form'); // Using addClass to set the class attribute
        $formDiv->addChild(new HtmlString('')); // Adding an empty HtmlString as a placeholder
        $formDiv->toHtml();

        $count = $this->fields_array2var();

        $sql = "SELECT ";
        $rowcount = 0;
        foreach ($this->entry_array as $row) {
            if ($rowcount > 0) $sql .= ", ";
            $sql .= $row['name'];
            $rowcount++;
        }
        $sql .= " from " . $this->table_details['tablename'];
        if (isset($this->table_details['orderby']))
            $sql .= " ORDER BY " . $this->table_details['orderby'];

        $this->notify(__METHOD__ . ":" . __METHOD__ . ":" . __LINE__ . ":" . $sql, "WARN");
        $this->notify(__METHOD__ . ":" . __METHOD__ . ":" . __LINE__ . ":" . " Display data", "WARN");
        $this->display_table_with_edit($sql, $this->entry_array, $this->table_details['primarykey']);

        $generateDiv = new Div();
        $generateDiv->addClass('generate'); // Using addClass to set the class attribute
        $generateDiv->addChild(new HtmlString('')); // Adding an empty HtmlString as a placeholder
        $generateDiv->toHtml();
    }
}
