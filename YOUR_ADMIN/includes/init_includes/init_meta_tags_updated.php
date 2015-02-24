<?php
// -----
// Initialize the newly-created database elements for the "Meta Tags Updated" plugin, created for http://overthehillweb.com.
// Copyright (c) 2015 Vinos de Frutas Tropicales
//
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
// -----
// Create the new "Maximum Values" entries that control the overall meta-tags' generation.
//
if (!defined ('MAX_LENGTH_META_TAG_TITLE')) {
  $db->Execute ("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function ) VALUES ( 'Meta Tags &mdash; Maximum Title Length', 'MAX_LENGTH_META_TAG_TITLE', '100', 'Specify the maximum number of characters allowed for any page\'s <em>title</em> meta-tag.  Any title that is longer than this value will be truncated prior to output.', 3, 100, NULL , NULL)");
  
  $db->Execute ("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function ) VALUES ( 'Meta Tags &mdash; Maximum Number of Keywords', 'MAX_NUMBER_META_TAG_KEYWORDS', '20', 'Specify the maximum number of comma-separated keywords allowed for any page\'s <em>keyword</em> meta-tag. If a page\'s configuration uses more than this number, any keywords found after the limit will not be output.', 3, 101, NULL , NULL)");
    
  $db->Execute ("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function ) VALUES ( 'Meta Tags &mdash; Maximum Description Length', 'MAX_LENGTH_META_TAG_DESCR', '200', 'Specify the maximum number of characters allowed for any page\'s <em>description</em> meta-tag.  Any description that is longer than this value will be truncated prior to output.', 3, 102, NULL , NULL)");

  $db->Execute ("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function ) VALUES ( 'Meta Tags &mdash; Default Values', 'STORE_META_TAGS_USE_DEFAULTS', 'false', 'Use <em>Intelligent Auto-Population</em> rules for any meta-tag value that is not already defined? Refer to the documentation for details.', 1, 150, NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),')");

  $db->Execute ("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function ) VALUES ( 'Tell Robots\/Spiders Not to Index', 'ROBOTS_PAGES_TO_SKIP', 'login\,logoff\,create_account\,account\,account_edit\,account_history\,account_history_info\,account_newsletters\,account_notifications\,account_password\,address_book\,advanced_search\,advanced_search_result\,checkout_success\,checkout_process\,checkout_shipping\,checkout_payment\,checkout_confirmation\,cookie_usage\,create_account_success\,contact_us\,download\,download_timeout\,customers_authorization\,down_for_maintenance\,password_forgotten\,time_out\,unsubscribe\,info_shopping_cart\,gv_faq\,gv_redeem\,gv_send\,popup_image\,popup_image_additional\,product_reviews_write\,ssl_check\,shopping_cart\,no_account\,order_status\,address_book_process\,checkout_payment_address\,checkout_shipping_address\,download_time_out\,page_not_found\,payer_auth_auth\,payer_auth_frame\,payer_auth_verifier\,popup_attributes_qty_prices\,popup_coupon_help\,popup_cvv_help\,popup_search_help\,popup_shipping_estimator\,redirect\,tpp\,zen4wp_add_product', 'Use <em>Intelligent Auto-Population</em> rules for any meta-tag value that is not already defined? Refer to the documentation for details.', 1, 155, NULL , NULL)");

  $db->Execute ("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function ) VALUES ( 'Site Title Default', 'TITLE', 'Zen Cart!', 'Default title. This is what will appear in the title tag of the homepage', 1, 8, NULL , NULL)");
  
  $db->Execute ("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function ) VALUES ( 'Site Tagline Default', 'SITE_TAGLINE', 'The Art of E-Commerce!', 'Default tagline. This is what will appear in the title tag of the homepage', 1, 8, NULL , NULL)");
  
}

// -----
// Create the new database table that holds the "Other" pages' meta-tag information.
//
$db->Execute ("CREATE TABLE IF NOT EXISTS " . TABLE_OTHER_PAGES_META_TAGS . " (
  `other_pages_name` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL default '1',
  `metatags_title` varchar(255) NOT NULL default '',
  `metatags_keywords` text,
  `metatags_description` text,
  PRIMARY KEY  (`other_pages_name`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=" . DB_CHARSET);

// -----
// Register the "Other" pages' meta-tag entry tool in the admin-level menus.
//
if (!zen_page_key_exists ('localizationMetaTagsUpdated')) {
  $next_sort = $db->Execute ('SELECT MAX(sort_order) as max_sort FROM ' . TABLE_ADMIN_PAGES . " WHERE menu_key='localization'");
  zen_register_admin_page ('localizationMetaTagsUpdated', 'MENU_LABEL_OTHER_PAGES_META_TAGS', 'FILENAME_OTHER_PAGES_META_TAGS', '', 'localization', 'Y', $next_sort->fields['max_sort']+1);
  
}