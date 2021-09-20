<?php
include('/var/www/owenpalmer.com/htdocs/wp-load.php');
global $wpdb;
$test = $_POST['test'];
$test = 21;

$load = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM wp_piano LIMIT %d",
        $test)
);
r($load);
wp_send_json($load);

?>
