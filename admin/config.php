<?php
// config.php - database credentials and site settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_website_db');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', '/'); // adjust if hosted in subfolder, e.g. '/myapp/'
session_start();
?>