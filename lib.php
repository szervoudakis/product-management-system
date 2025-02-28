<?php
class Products
{

    private $xml_file_path = '';
    // Define the fields that a product contains, specifying whether each field is stored as CDATA.
    // key = The field used in the get_post_data function.
    // node and iscdata are used for inserting data into the XML.
    private $fields_map =  [
        'name' => ['node'=>'NAME', 'iscdata'=>true],
        'price' => ['node'=>'PRICE', 'iscdata' => false],
        'quantity' => ['node'=>'QUANTITY', 'iscdata' => false],
        'category' => ['node'=>'CATEGORY', 'iscdata' => false],
        'category_id' => [],
        'manufacturer' => ['node'=>'MANUFACTURER', 'iscdata' => false],
        'barcode' => ['node'=>'BARCODE', 'iscdata' => true],
        'weight' => ['node'=>'WEIGHT', 'iscdata' => true],
        'instock' => ['node'=>'INSTOCK', 'iscdata' => false],
        'availability' => ['node'=>'AVAILABILITY', 'iscdata' => false],
    ];
    public function __construct($xml_file_path = ''){
        $this->xml_file_path = $xml_file_path;
    }

    /**
     * This function prints an HTML table with all the products as read from the xml file
     * @return void 
     */
    public function print_html_table_with_all_products()
    {
        
        $xmldata = simplexml_load_file($this->xml_file_path) or die("Failed to load");
        $xml_data = $xmldata->children();
        echo "<table border='1'>";
        echo "<tr>";
            foreach ($this->fields_map as $field=>$mapping) {
                if(isset($mapping['node'])){
                    $lowercase=strtolower($field);
                    $finalWord=ucfirst(strtolower($lowercase)); // We want to display the first letter capitalized and the rest lowercase
                    echo "<th>{$finalWord}</th>"; // Print the field name
                }
                
            }
        echo "</tr>";
        foreach ($xml_data->PRODUCTS->PRODUCT as $key => $prod) {
            // Call the function that inserts the elements available in the XML into the HTML table
            $this->print_html_of_one_product_line($prod);
        }
        echo"</table>";  
    }

    /**
     * This function prints an HTML tr for a given product
     * @param mixed $prod It is the product object as retrieved from the xml file
     * @return void 
     */
    private function print_html_of_one_product_line($prod){
       
        echo "<tr>";
        foreach ($this->fields_map as $field => $mapping) {
            if (isset($mapping['node']) && isset($prod->{$mapping['node']})) { // If the mapping contains the node, then create the td
                $value = htmlspecialchars((string) $prod->{$mapping['node']});
                echo "<td>{$value}</td>";
            }
        }
        echo "</tr>";
    }

    public function insert_product_xml($prod){
        
        try {
            //try to load the xml file
            $xmldata = simplexml_load_file($this->xml_file_path);
            if ($xmldata === false) {
                throw new Exception("Failed to load XML file.");
            }
    
            $xmldata->LAST_UPDATE = date('Y-m-d H:i:s');
            $new_product = $xmldata->PRODUCTS->addChild('PRODUCT'); // we update the node, with the current date
    
            foreach ($this->fields_map as $field => $mapping) {
                $lowercase_field = strtolower($field);
                $value = $prod[$lowercase_field] ?? '';
                if(isset($mapping['node'])){  // If the field has a node, then insert a new child into the XML
                    // If the field is CDATA, we use the `add_cdata_child` function.
                    if ($mapping['iscdata']) {
                        $this->add_cdata_child($new_product, $mapping['node'], $value);
                    } else {
                        // In the CATEGORY section of the XML, we add the attribute 'id' to maintain the same structure
                        if ($mapping['node'] === 'CATEGORY') {
                            $categorynode = $new_product->addChild('CATEGORY', $value);
                            $categorynode->addAttribute('id', $prod['category_id']);
                        }
                        $new_product->addChild($mapping['node'], $value);
                    }
                }
                
            }
    
            // store data in xml
            $xmldata->asXML($this->xml_file_path);
        } catch (Exception $e) {
            
            error_log("Error: " . $e->getMessage()); // logging error
            echo "<p style='color:red;'>There was an error while saving the product. Please try again later.</p>";
        }
    }
    
    //we create function for all cdata fields
    private function add_cdata_child($parent, $name, $value){
         $parent->addChild($name);
         $node = $parent->$name;
         $dom = dom_import_simplexml($node);
         $cdata = $dom->ownerDocument->createCDATASection($value);//creates section CDATA
         $dom->appendChild($cdata);
    }

    //helper method to get data from POST
    public function get_post_data(){
        $data = [];
        foreach ($this->fields_map as $field => $mapping) {
            $data[$field] = $_POST[$field] ?? ''; // Initialize as empty
        }
        // Return the array with the product data entered by the user
        return $data;
    }
}
