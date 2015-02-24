<?php
/**
 * metatags-editing functions
 *
 * @package admin
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: functions_metatags_manufacturers.php 2844 2006-01-13 06:46:29Z drbyte $
 * @no-docs
 */
// -----
// Create database table, if it doesn't already exist.
//
$db->Execute ("CREATE TABLE IF NOT EXISTS " . TABLE_MANUFACTURERS_META . " (
  `manufacturers_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL default '1',
  `metatags_title` varchar(255) NOT NULL default '',
  `metatags_keywords` text,
  `metatags_description` text,
  PRIMARY KEY  (`manufacturers_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=" . DB_CHARSET);

/**
 * Manufacturers specific metatags
 */
function zen_get_manufacturer_metatags_title ($manufacturer_id, $language_id) {
  global $db;
  $category = $db->Execute ("SELECT * FROM " . TABLE_MANUFACTURERS_META . " WHERE manufacturers_id = " . (int)$manufacturer_id . " AND language_id = " . (int)$language_id . " LIMIT 1");
  
  return ($category->EOF) ? '' : $category->fields['metatags_title'];
}

function zen_get_manufacturer_metatags_description ($manufacturer_id, $language_id) {
  global $db;
  $category = $db->Execute ("SELECT * FROM " . TABLE_MANUFACTURERS_META . " WHERE manufacturers_id = " . (int)$manufacturer_id . " AND language_id = " . (int)$language_id . " LIMIT 1");

  return ($category->EOF) ? '' : $category->fields['metatags_description'];
}

function zen_get_manufacturer_metatags_keywords ($manufacturer_id, $language_id) {
  global $db;
  $category = $db->Execute ("SELECT * FROM " . TABLE_MANUFACTURERS_META . " WHERE manufacturers_id = " . (int)$manufacturer_id . " AND language_id = " . (int)$language_id . " LIMIT 1");

  return ($category->EOF) ? '' : $category->fields['metatags_keywords'];
}
