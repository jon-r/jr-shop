<?php
/*
 * JR-SHOP-ADMIN
 *
 * Admin backend for the plugin. At first will just be for maintenance, file cleanup etc.
 * this is the "soft reset", first point of call if things arent playing.
 *
 * (c) Jon Richards 2015
 */

add_action('admin_menu', 'rhc_setup_menu');

function rhc_setup_menu(){
    add_menu_page( 'Red Hot Chilli Maintenance', 'RHC Maintenance', 'manage_options', 'rhc-maintenance', 'rhc_init' );
}

function rhc_init(){
?>
<h1>Hello World!</h1>
<p>More Coming soon!</p>
 <?php
 } ?>
