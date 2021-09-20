<?php
include('/var/www/owenpalmer.com/htdocs/wp-load.php');
global $wpdb;
$value = $_POST['load'];
// error_log($value);

$load = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM wp_piano WHERE ID=%d", 
        $value)
);

wp_send_json(json_decode($load[0]->song));

?>
