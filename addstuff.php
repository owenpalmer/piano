<?php
include('/var/www/owenpalmer.com/htdocs/wp-load.php');
global $wpdb;
$song = $_POST['song'];   
$name = $_POST['name'];   
$title = $_POST['title'];   
// r(json_encode($value));
// echo $value;

error_log($value);
$wpdb->insert(
    'wp_piano',
    array(
        'song' => json_encode($song),
        'name' => $name,
        'title' => $title
    )
);

?>
