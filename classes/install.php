<?php

if (!class_exists('OP_Install')) {
    
    class OP_Install {

        // function install_tables() {
        //     error_log("installing");
        //     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //     $newtable = "CREATE TABLE wp_owen_test (
        //     id int(10) NOT NULL AUTO_INCREMENT , 
        //     words text NOT NULL , 
        //     date_and_time datetime DEFAULT CURRENT_TIMESTAMP , 
        //     UNIQUE KEY id (id));";
        //     dbDelta($newtable);
        // }
        
        function install_tables() {
            error_log("installing");
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $newtable = "CREATE TABLE `pUd_owenpalmer_com`.`wp_piano` (
            id INT NOT NULL AUTO_INCREMENT , 
            song TEXT NOT NULL , 
            name TEXT NOT NULL , 
            title TEXT NOT NULL , 
            PRIMARY KEY (id)) ENGINE = InnoDB;";
            dbDelta($newtable);
        }
    }
}
?>

