<?php
// -----
// Part of the "Meta Tags Updated" plugin, created for http://overthehillweb.com
// Copyright (c) 2015 Vinos de Frutas Tropicales
//
define ('HEADING_TITLE', "Meta-Tags for Non-Product Pages");
define ('TEXT_INSTRUCTIONS', 'Use this page to manage the meta-tag information for the "other" pages in your store, i.e. pages that are not category-, manufacturer- or product-listings, product-information or EZ-Pages.  The page inspects your store\'s /includes/modules/pages folder and displays a list of those "other" pages that are <b>not</b> specified in your current template\'s <em>ROBOTS_PAGES_TO_SKIP</em> definition <span class="smaller">(contained in the file %s)</span>.');

define ('TABLE_HEADING_PAGE_NAME', 'Page Name');
define ('TABLE_HEADING_TITLE', 'Title');
define ('TABLE_HEADING_KEYWORDS', 'Keywords');
define ('TABLE_HEADING_DESCRIPTION', 'Description');
define ('TABLE_HEADING_ACTION', 'Action');

define ('TEXT_INFO_EDIT_META_TAGS', 'Edit the Meta Tags for %s');
define ('TEXT_INFO_EDIT_INTRO', 'To remove a meta-tag for this page, simply save the value as blank.');

define ('TEXT_INFO_METATAGS_TITLE', 'Meta Tags Title');
define ('TEXT_INFO_METATAGS_KEYWORDS', 'Meta Tags Keywords (comma-separated)');
define ('TEXT_INFO_METATAGS_DESCRIPTION', 'Meta Tags Description');

define ('ERROR_NO_TEMPLATE_DIR', 'Could not locate template directory , using template_language (0, %u).');
define ('ERROR_NO_META_TAGS_FILE', 'Could not locate meta_tags.php, neither %s nor $s is present.');
define ('ERROR_MISSING_PAGES_TO_SKIP', 'Missing definition of ROBOTS_PAGES_TO_SKIP in %s.');
define ('ERROR_MISSING_PAGES_TO_SKIP_DEFINITION', 'Missing value for ROBOTS_PAGES_TO_SKIP in %s.');

define ('TEXT_DELETED', 'deleted');
define ('TEXT_ADDED', 'added');
define ('TEXT_UPDATED', 'updated');
define ('SUCCESS_META_TAGS_UPDATED', 'The meta-tags for the page <b>%1$s</b> were %2$s.');  //-%1$s (the name of the page), %2$s (the action, one of the above 3 values)