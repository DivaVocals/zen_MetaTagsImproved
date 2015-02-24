# zenMetaTagsImproved

The current meta tag capability in Zen Cart is really out of touch with modern browser standards.. This module is the 1st version of an attempt at modernizing the current Zen Cart meta tagging functionality. 

"Meta Tags Updated" plugin was developed based on the following requirements

There are various elements that are required to meet the data-gathering aspects of the requirements:
1.	Install Manufacturers Meta-Tags (http://www.zen-cart.com/downloads.php?do=file&id=932 ) This will update the admin’s Catalog->Manufacturers processing to add the meta-tag on/off icon to the manufacturers’ listing display; that icon operates in a manner similar to the built-in categories/products meta-tags. When selected, you can enter the title, keywords and description information for the current manufacturer (multi-lingual).
2.	Modify Meta Tags for EZ-Pages (http://www.zen-cart.com/downloads.php?do=file&id=746) plugin, separating out the meta-tags to a separate database table. This will update the admin’s Tools->EZ-Pages to add the meta-tag on/off icon to the EZ-Pages overview listing in a manner similar to the Manufacturers Metatags update. The title, keywords and description information can be entered for the EZ-Page in a newly-added section on the page’s input screen.
3.	Create Localization->Other Meta Tags to enable the meta-tag information to be entered for the “other” pages in the store.  This tool will interrogate the existing store-side pages, displaying those pages that aren’t listed in the ROBOTS_PAGES_TO_SKIP constant and not index, product*_info or document*_info pages – there will be an entry for the home-page meta-tags. The admin user can enter the title, keywords and description for each of these stand-alone pages. While the database will include a languages_id, only a single language is supported initially.

Along with the data-gathering elements, the store-side meta-tag processing will be modified to use the following "rules" in meta-tag processing:
Once the title, keywords and description information has been gathered from the “source” (which is dependent on the page itself), a meta-tag’s output will occur if-and-only-if meta-tag data has been entered – no default values are accepted. Each of the meta-tag outputs (title, keywords and description) will be truncated at their maximum length (or, for the keywords, count), as specified by Configuration->Maximum Values items added by this processing.

#Optional feature: Intelligent Auto-Population
Adds an additional true/false configuration switch (Configuration->My Store->Default Meta-Tag Values?), default: false. If this switch is set to true, then the following “rules” are used to populate the meta-tag values if no other setting applies:
 

