<?php
/**
 * meta_tags module
 *
 * @package modules
 * @copyright Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: meta_tags.php 11202 2008-11-23 09:18:34Z drbyte $
 */
// -----
// Part of the "Meta Tags Updated" plugin, created for http://overthehillweb.com
//
// This reflects a **major** restructuring of the processing, so no bof/eof change lines are included.  The processing "rules" are:
//
// If a page's meta-tag keywords are not defined, no <meta name="keywords"> tag is output
//
// If Configuration->My Store->Meta Tags - Default Values is set to false and either the meta-tag title or description is not
//   registered in the database for a given page, the associated <meta> tag is not output.
// Otherwise, the "title" and "description" meta-tag output defaults depending on a page's category if not registered in the database:
// - Product pages:  title set to "product-name [product-model]", description set to first xx characters of the product's description
// - Category/product listings:  title set to "category-name", description set to first xx characters of the category's description (if set)
// - Manufacturers' listing:  title set to "manufacturer-name", description not set
// - EZ-Pages:  title set to "ez-page-title", description is set to first xx characters of the EZ-Page HTML content (if set).
// - All other pages:  title set to the HEADING_TITLE constant for the given page, description not set
//
// Copyright (c) 2015 Vinos de Frutas Tropicales
//
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
  
}

// This should be first line of the script:
$zco_notifier->notify ('NOTIFY_MODULE_START_META_TAGS');

// -----
// First, pull any page-specific metatag entries from the database.
//
$is_product_page = $is_listing_page = false;
$languages_id = (int)$_SESSION['languages_id'];
switch (true) {
  // -----
  // If this is a product-information page, e.g. product_info or document_general_info, ...
  //
  case ((strpos ($current_page_base, 'product_') === 0 || strpos ($current_page_base, 'document_') === 0) && isset ($_GET['products_id'])): {
    $is_product_page = true;
    $metatags_info = $db->Execute ("SELECT pd.products_name, p.products_model, p.products_price_sorter, p.products_tax_class_id, p.metatags_title_status, 
                                           p.metatags_products_name_status, p.metatags_model_status, p.metatags_price_status, p.metatags_title_tagline_status,
                                           pd.products_description, mtpd.metatags_title, mtpd.metatags_keywords, mtpd.metatags_description
                                      FROM (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd) 
                                 LEFT JOIN " . TABLE_META_TAGS_PRODUCTS_DESCRIPTION . " mtpd ON mtpd.products_id = p.products_id and mtpd.language_id = $languages_id
                                     WHERE p.products_id = " . (int)$_GET['products_id'] . "
                                       AND p.products_id = pd.products_id
                                       AND pd.language_id = $languages_id");    
    break;
  }
  // -----
  // If this is a category, product or manufacturer listing page ...
  //
  case ($current_page_base == FILENAME_DEFAULT && !$this_is_home_page): {
    $is_listing_page = true;
    if ($category_depth == 'nested' || $category_depth == 'products') {
      $metatags_info = $db->Execute ("SELECT * FROM " . TABLE_METATAGS_CATEGORIES_DESCRIPTION . " WHERE categories_id = " . (int)$current_category_id . " AND language_id = " . (int)$_SESSION['languages_id'] . " LIMIT 1");

    } elseif (isset ($_GET['manufacturers_id'])) {
      $metatags_info = $db->Execute ("SELECT * FROM " . TABLE_MANUFACTURERS_META . " WHERE manufacturers_id = " . (int)$_GET['manufacturers_id'] . " AND language_id = " . (int)$_SESSION['languages_id'] . " LIMIT 1");
      
    }
    break;
  }
  // -----
  // If this is an EZ-page ...
  //
  case ($current_page_base == FILENAME_EZPAGES): {
    $ezpage_id = (int)$_GET['id'];
    $metatags_info = $db->Execute ("SELECT pages_meta_title AS metatags_title, pages_meta_keywords AS metatags_keywords, pages_meta_description AS metatags_description FROM " . TABLE_EZPAGES . " WHERE pages_id = $ezpage_id LIMIT 1");
    break;
  }
  // -----
  // Otherwise, this is an "other" page (including the home-page) ...
  //
  default: {
    $metatags_info = $db->Execute ("SELECT * FROM " . TABLE_OTHER_PAGES_META_TAGS . " WHERE other_pages_name = '$current_page_base' AND language_id = " . (int)$_SESSION['languages_id'] . " LIMIT 1");
    break;
  }
}

// -----
// If the page-specific meta-tag information has been set, pull the non-empty elements that have been defined.
//
$metatags_title = $metatags_keywords = $metatags_description = false;
if (!$metatags_info->EOF) {
  if (!$is_product_page) {
    if (zen_not_null (zen_clean_html ($metatags_info->fields['metatags_title']))) {
      $metatags_title = zen_clean_html ($metatags_info->fields['metatags_title']);
      
    }
  } else {
    $product_title = '';
    if ($metatags_info->fields['metatags_products_name_status'] == 1) {
      $product_title .= $metatags_info->fields['products_name'];
      
    }
    if ($metatags_info->fields['metatags_title_status'] == 1) {
      $product_title .= ' ' . $metatags_info->fields['metatags_title'];
      
    }
    if ($metatags_info->fields['metatags_model_status'] == 1) {
      $product_title .= ' [' . $metatags_info->fields['products_model'] . ']';
      
    }
    if ($metatags_info->fields['metatags_price_status'] == 1 && zen_check_show_prices () == true) {
      $product_title .= SECONDARY_SECTION;
      if ($metatags_info->fields['products_price_sorter'] > 0) {
        $product_title .= $currencies->display_price ($metatags_info->fields['products_price_sorter'], zen_get_tax_rate ($metatags_info->fields['products_tax_class_id']));
        
      } else {
        $product_title .= META_TAG_PRODUCTS_PRICE_IS_FREE_TEXT;
        
      }
    }
    if ($metatags_info->fields['metatags_title_tagline_status'] == 1) {
      $product_title .= PRIMARY_SECTION . TITLE . TAGLINE;
      
    }
    if (zen_not_null (zen_clean_html ($product_title))) {
      $metatags_title = zen_clean_html ($product_title);
      
    }
  }
  if (zen_not_null (zen_clean_html ($metatags_info->fields['metatags_keywords']))) {
    $metatags_keywords = zen_clean_html ($metatags_info->fields['metatags_keywords']);
    
  }
  if (zen_not_null (zen_clean_html ($metatags_info->fields['metatags_description']))) {
    $metatags_description = zen_clean_html ($metatags_info->fields['metatags_description']);
    
  }
}
unset ($metatags_info, $product_title);

// -----
// If the store is set to use meta-tag defaults and one or more of the tag values is currently unset, gather the default
// values based on the type of page that's currently being processed.
//
if (STORE_META_TAGS_USE_DEFAULTS == 'true' && ($metatags_title === false || $metatags_keywords === false || $metatags_description === false)) {
  switch (true) {
    // -----
    // If this is a product-information page, e.g. product_info or document_general_info, ...
    //
    case ($is_product_page): {
      $metatags_info = $db->Execute ("SELECT products_name AS metatags_title, products_description AS metatags_description FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = " . (int)$_GET['products_id'] . " AND language_id = " . (int)$_SESSION['languages_id'] . " LIMIT 1");
      break;
    }
    // -----
    // If this is a category, product or manufacturer listing page ...
    //
    case ($is_listing_page): {
      if ($category_depth == 'nested' || $category_depth == 'products') {
        $metatags_info = $db->Execute ("SELECT categories_name AS metatags_title, categories_description AS metatags_description FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE categories_id = " . (int)$current_category_id . " AND language_id = " . (int)$_SESSION['languages_id'] . " LIMIT 1");

      } elseif (isset ($_GET['manufacturers_id'])) {
        $metatags_info = $db->Execute ("SELECT manufacturers_name AS metatags_title FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = " . (int)$_GET['manufacturers_id'] . " LIMIT 1");
        
      }
      break;
    }
    // -----
    // If this is an EZ-page ...
    //
    case ($current_page_base == FILENAME_EZPAGES): {
      $metatags_info = $db->Execute ("SELECT pages_title AS metatags_title, pages_html_text AS metatags_description FROM " . TABLE_EZPAGES . " WHERE pages_id = $ezpage_id LIMIT 1");
      break;
    }
    // -----
    // Otherwise, this is an "other" page (including the home-page) ...
    //
    default: {
      if ($metatags_title === false && defined ('HEADING_TITLE')) {
        $metatags_title = HEADING_TITLE;
        
      }
      break;
    }
  }
  // -----
  // Set the default title and description values, if present and not already set.
  //
  if (isset ($metatags_info) && !$metatags_info->EOF) {
    if ($metatags_title === false && zen_not_null (zen_clean_html ($metatags_info->fields['metatags_title']))) {
      $metatags_title = zen_clean_html ($metatags_info->fields['metatags_title']);
      
    }
    if ($metatags_description === false && isset ($metatags_info->fields['metatags_description']) && zen_not_null (zen_clean_html ($metatags_info->fields['metatags_description']))) {
      $metatags_description = zen_clean_html ($metatags_info->fields['metatags_description']);
      
    }
  }
  unset ($metatags_info);
  
}

// -----
// Apply the size limits (either character length or item count) to any meta-tag values that are set.
//
if ($metatags_title !== false) {
  $metatags_title = htmlentities ($metatags_title, ENT_COMPAT, CHARSET, true);
  define ('META_TAG_TITLE', zen_trunc_string ($metatags_title, MAX_LENGTH_META_TAG_TITLE, 'false'));
  
}

if (!defined ('METATAGS_DIVIDER')) define ('METATAGS_DIVIDER', ', ');
if (!defined ('METATAGS_KEYWORDS_DELIMITER')) define ('METATAGS_KEYWORDS_DELIMITER', ',');
if ($metatags_keywords !== false) {
  $keywords_in = explode (METATAGS_KEYWORDS_DELIMITER, $metatags_keywords);
  for ($i = 0, $n = count ($keywords_in), $keywords_out = array (); $i < MAX_NUMBER_META_TAG_KEYWORDS && $i < $n; $i++) {
    $keywords_out[$i] = htmlentities (trim ($keywords_in[$i]), ENT_COMPAT, CHARSET, true);
    
  }
  define ('META_TAG_KEYWORDS', implode (METATAGS_DIVIDER, $keywords_out));
  
}

if ($metatags_description !== false) {
  $metatags_description = htmlentities ($metatags_description, ENT_COMPAT, CHARSET, true);
  define ('META_TAG_DESCRIPTION', zen_trunc_string ($metatags_description, MAX_LENGTH_META_TAG_DESCR, 'false'));
  
}

// This should be last line of the script:
$zco_notifier->notify ('NOTIFY_MODULE_END_META_TAGS');