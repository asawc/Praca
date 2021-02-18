<?php
/* 
********************************************
!!! Po prawej stronie zmieniasz tylko !!!
*********************************************
*/
define('SERVER_NAME', 'localhost');
define('SERVER_USER_NAME', 'root');
define('SERVER_USER_PASSWORD', '');
define('DATABASE', 'android');

// TABELE
 // nazwa tabeli użytkownicy
define('USERS_TABLE', 'users');
// nazwy kolumn
define('USER_ID', 'id');
define('USER_NAME', 'username');
define('USER_PASSWORD', 'password');
define('USER_EMAIL', 'email');

// nazwa tabeli produkty
define('PRODUCTS_TABLE', 'products'); 
// nazwy kolumn
define('PRODUCT_ID', 'id');
define('PRODUCT_NAME', 'productname');
define('PRODUCT_SYMBOL', 'productsymbol');
define('PRODUCT_QUANTITY', 'quantity');

// nazwa tabeli pracownicy
define('EMPLOYEES_TABLE', 'employees'); 
// nazwy kolumn
define('EMPLOYEE_ID', 'id');
define('EMPLOYEE_SYMBOL', 'symbol');
define('EMPLOYEE_NAME', 'name');
define('EMPLOYEE_SURNAME', 'surname');

// nazwa tabeli wydania
define('RELEASES_TABLE', 'releases'); 
// nazwy kolumn
define('RELEASES_ID', 'id');
define('RELEASES_ID_EMPLOYEE', 'id_employee');
define('RELEASES_STATUS', 'status');
define('RELEASES_DATE_CREATION', 'date_creation');
define('RELEASES_DATE_REALIZING', 'date_realizing');

// nazwa tabeli zamówienia produktów
define('PRODUCTS_ORDERS_TABLE', 'products_orders'); 
// nazwy kolumn
define('PRODUCTS_ORDERS_ID', 'id');
define('PRODUCTS_ORDERS_ID_PRODUCT', 'id_product');
define('PRODUCTS_ORDERS_ID_RELEASE', 'id_release');
define('PRODUCTS_ORDERS_STATUS', 'status');
define('PRODUCTS_ORDERS_QUANTITY', 'quantity');

?>