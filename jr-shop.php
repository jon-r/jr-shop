<?php
/**
 * Plugin Name:       Shop Plugin For Red Hot Chilli Northwest
 * Description:       Lightweight shop output plugin purpose built for the shop. Built with a focus on speed and ease of use. Requires local user connection to the MS Access 'Back end'
 * Version:           1.2.7
 * Author:            Jon Richards
 * Author URI:        https://github.com/jon-r
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jr-shop
 */
/*  Copyright (c) 2015  Jonathan Richards (email : jon.richards@outlook.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

include ('functions/JR_Mini_Cache.php');

include('functions/classes/JR_class_product.php');
include('functions/classes/JR_class_itemList.php');
include('functions/classes/JR_class_compile.php');
include('functions/classes/JR_class_sitemap.php');
include('functions/classes/JR_class_validate.php');

include ('functions/JR_Carousel.php');
include ('functions/JR_Queries.php');
include ('functions/JR_Site_Options.php');
include ('functions/JR_Image_Edit.php');
include ('functions/JR_Item_Scale.php');
include ('functions/JR_Permalinks.php');
include ('functions/JR_Page_Nav.php');
include ('functions/JR_Plugin_Integration.php');
include ('functions/JR_Search.php');
include ('functions/JR_Shop_Compile.php');
include ('functions/JR_Shop_Filter.php');
include ('functions/JR_Shortcodes.php');
include ('functions/JR_Testimonials.php');
include ('functions/JR_Validate.php');
include ('functions/JR_Contact_Forms.php');
include ('functions/JR_File_Cleanup.php');


/* if (is_admin()) {
   ADMIN HAS BEEN REMOVED FOR NOW - all options have been moved to the database

   include 'admin/JR_Shop_Admin.php';
 }*/

$jr_page = new pageValidate;
$jr_page->init();

?>
