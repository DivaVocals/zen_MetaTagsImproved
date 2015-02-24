<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2013 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Fri Feb 1 21:08:47 2013 -0500 Modified in v1.5.2 $
 */
// -----
// Part of the "Meta Tags Updated" plugin, created for http://overthehillweb.com
//
// This reflects a **major** restructuring of the meta-tags' processing, so no bof/eof change lines are included.
//
// All Zen-Cart built-in constants that are no longer used have been removed.
//
// Copyright (c) 2015 Vinos de Frutas Tropicales
//

// page title
//define('TITLE', 'Zen Cart!');

// Site Tagline
//define('SITE_TAGLINE', 'The Art of E-commerce');

// separators for meta tag definitions
// Define Primary Section Output
define('PRIMARY_SECTION', ' : ');

// Define Secondary Section Output
define('SECONDARY_SECTION', ' - ');

// Define divider ... comma plus a space.  This is used to separate the <meta> tag keywords in the HTML
define('METATAGS_DIVIDER', ', ');

// Define delimiter used in the database (could be the same as above) to separate each meta-tag keyword element
define('METATAGS_KEYWORDS_DELIMITER', ',');

// Define which pages to tell robots/spiders not to index
// This is generally used for account-management pages or typical SSL pages, and usually doesn't need to be touched.
//define('ROBOTS_PAGES_TO_SKIP','login,logoff,create_account,account,account_edit,account_history,account_history_info,account_newsletters,account_notifications,account_password,address_book,advanced_search,advanced_search_result,checkout_success,checkout_process,checkout_shipping,checkout_payment,checkout_confirmation,cookie_usage,create_account_success,contact_us,download,download_timeout,customers_authorization,down_for_maintenance,password_forgotten,time_out,unsubscribe,info_shopping_cart,gv_faq,gv_redeem,gv_send,popup_image,popup_image_additional,product_reviews_write,ssl_check,shopping_cart,no_account,order_status,address_book_process,checkout_payment_address,checkout_shipping_address,download_time_out,page_not_found,payer_auth_auth,payer_auth_frame,payer_auth_verifier,popup_attributes_qty_prices,popup_coupon_help,popup_cvv_help,popup_search_help,popup_shipping_estimator,redirect,tpp,zen4wp_add_product');