<?php
include('/var/www/owenpalmer.com/htdocs/wp-load.php');
global $wpdb;
$test = $_POST['test'];

$load = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM wp_piano LIMIT %d", 
        $test)
);

wp_send_json($load);

?>
