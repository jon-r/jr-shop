<?php
/**
 * Plugin Name:       Shop Plugin For Red Hot Chilli Northwest
 * Description:       Lightweight shop output plugin purpose built for the shop. Built with a focus on speed and ease of use. Requires local user connection to the MS Access 'Back end'
 * Version:           0.9.8
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
/* ---- unhooks style settings from other plugins, to minimise css conflicts */
include('functions/JR_Plugin_Integration.php');
/* ---- functions ----
 > Misc functions, internal use.
 > Static variables (where no editing is needed)
*/
include('functions/JR_Functions_Other.php');
/* ---- shop Output:
  > Converts data into user friendly chunks of output.
  > Includes filters, sorts, etc.
  > Smart image hunting (based on naming conventions) No need to assign images like usual CMS's. '
  > Also carousel, testimonials
*/
include('functions/JR_Functions_Shop.php');
/* ---- admin: ----
/*
 * back end core. UI for soft resets, web based settings, maintenance
*/
if (is_admin()) {
  include 'admin/JR_Shop_Admin.php';
}
/* ---- Permalinks:
  > extending the WP rewrite rules, to include all the shop pages.
*/
include('functions/JR_Permalinks.php');
/* ---- Queries:
  > Takes the data directly from the database,
  > Filtered and sorted based on user input or pages.
*/
include('functions/JR_Queries.php');
/* ---- Global Variables
  > The things tweaked the most (items, categorys) are in the database, easily edited.
  > This is the stuff that should be fine-tuned to cover most tweaks (hopefully).
  >> TO GO INTO ADMIN <<
*/
include('functions/JR_Global_Variables.php');
/* ---- Validate:
  > Since the Back-End is Based on in house PCs, theres a fairly limited amount that the customer can access.
  > Light security whitlelist sanitises the input to prevent injection just in case.
*/
include('functions/JR_Validate.php');
/* ---- Search:
  > semi inteligent search input, but fairly vague output if the smart keywords arent triggered
  > the triggers are loose guides, prefer that they are skipped unless 90% likely to be what the customer is looking for.
  > An RHC(s) number points at the specific item
  > everything else will be a REGEX search of whats been typed, treating spaces as 'OR'
*/
include('functions/JR_Search.php');
/* ---- Innitialise ----
 > Page start up.
 > Root information called on each new page. Used for menus, validation, output.
*/
include('functions/JR_Init.php');
/* ---- mini cache
 > caches the parts of the page that stay somewhat static
 > creates html templates to use instead of the function heavyphp ones
 */
include('functions/JR_miniCache.php');
?>
