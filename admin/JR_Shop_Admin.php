<?php
/*
 * JR-SHOP-ADMIN
 *
 * Admin backend for the plugin. At first will just be for maintenance, file cleanup etc.
 * this is the "soft reset", first point of call if things arent playing.
 *
 * (c) Jon Richards 2015
 */
/* --------- Load Up Admin Section --------------------------------------------------- */

include ('JR_Admin_Functions.php');

add_action('admin_menu', 'rhc_setup_menu');
add_action( 'admin_enqueue_scripts', 'rhc_getScripts' );
?>
