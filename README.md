# Product Management System

## Overview
The **Product Management System** is a simple PHP-based application that allows users to manage products using an XML file as a data storage mechanism. It provides functionalities for listing existing products in an HTML table and inserting new products through a form.

## Features
- Display a list of products stored in an XML file.
- Add new products through an HTML form.
- Ensure data consistency and handle special fields like CDATA sections.

## Main Components
### `products.php`
- Displays a table containing all products stored in `products.xml`.
- Uses the `Products` class to read and render product data.

### `lib.php`
Defines the `Products` class, which includes the following methods:
- `print_html_table_with_all_products()`: Generates and prints an HTML table containing all products.
- `print_html_of_one_product_line($prod)`: Helper function that prints a single product row inside the table.
- `insert_product_xml($prod)`: Adds a new product entry to `products.xml`, ensuring data integrity.
- `get_post_data()`: Retrieves user-submitted form data for product insertion.
- `add_cdata_child($parent, $name, $value)`: Handles special characters using CDATA sections.

### `viewproducts.php`
- Contains an HTML form for adding a new product.
- Validates user input (e.g., the `NAME` field is mandatory).
- Processes form data and stores the new product entry in `products.xml`.

## Installation Instructions
### Prerequisites
- Web Server: Apache or Nginx
- PHP: Version 7.4 or later
- Permissions: Write permissions for the XML file (`products.xml`)
- PHP Extensions: `libxml`, `simplexml`

### Installation Steps
1. **Download and Upload Files**
   - Download all project files (`products.php`, `lib.php`, `viewproducts.php`, `products.xml`).
   - Upload them to the appropriate directory on your web server (e.g., `/var/www/html/`).

2. **Set File Permissions**
   - Ensure that the `products.xml` file has write permissions:
     ```sh
     chmod 666 /var/www/html/products.xml
     ```

3. **Verify PHP Installation**
   - Create a file named `info.php` in your web root directory with the following content:
     ```php
     <?php
     phpinfo();
     ?>
     ```
   - Open `http://localhost/info.php` in your browser to verify PHP installation.

4. **Run the Application**
   - Open `products.php` in your browser (e.g., `http://localhost/products.php`) to view the product list.
   - Use `viewproducts.php` to add new products.
