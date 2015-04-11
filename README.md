# zenMetaTagsImproved
Two meta tags search engine DO use for helping with matching search terms to matching content are Title, and Description. (https://support.google.com/webmasters/answer/79812)

Improve meta tagging for Zen Cart. (An attempt to bring Zen Cart meta tags into the 20th century! **lol**)

The current meta tag capability in Zen Cart is really out of touch with modern browser standards.. This module is the 1st version of an attempt at modernizing the current Zen Cart meta tagging functionality. 

Zen Cart has admin functionality for editing the meta tags for categories and products, but not the rest of the site pages.. The rest of the site meta tags must be managed via the **includes/languages/english/YOUR_TEMPLATE_NAME/meta_tags.php** file. 

Novice site owners should not have to have to learn how to edit PHP files just to update the site meta tags. 

There is a need to add the functionality for managing page meta tags into the shop admin so that it can be managed there. Improvements to the existing meta tag functionality is needed as well because the current behavior doesn't match the search engine's current standards. (like the devaluation of keywords for example) The current way that title, keywords, and description tags are auto generated has the potential to HARM versus help current site owners.

Based on current browser standards, here's how title and descriptions should be managed on a site:<br />
**Description - Recommended Length**<br />
Meta descriptions can be any length, but search engines generally truncate snippets longer than 160 characters. It is best to keep meta descriptions between 150 and 160 characters.

**Description - Not a Google Ranking Factor**<br />
Google announced in September of 2009 that neither meta descriptions nor meta keywords factor into Google's ranking algorithms for web search. Google uses meta descriptions to return results when searchers use advanced search operators to match meta tag content, as well as to pull preview snippets on search result pages, but it's important to note that meta descriptions do not to influence Google's ranking algorithms for normal web search.

**Quotes Cut Off Descriptions**<br />
Any time quotes are used in a meta description, Google cuts off the description. To prevent meta descriptions from being cut off, it's best to remove all non-alphanumeric characters from meta descriptions. If quotation marks are important in your meta description, you can change them to single quotes rather than double quotes to prevent truncation.

**Title - Optimal Format**<br />
Primary Keyword - Secondary Keyword | Brand Name

**Title - Optimal Length for Search Engines**<br />
Google typically displays the first 50-60 characters of a title tag, or as many characters as will fit into a 512-pixel display. If you keep your titles under 55 characters, you can expect at least 95% of your titles to display properly. Keep in mind that search engines may choose to display a different title than what you provide in your HTML. Titles in search results may be rewritten to match your brand, the user query, or other considerations.
 
ALL meta tag functionality should include validation (preferably some kind of on-page validation) to ensure that the meta tags do not exceed search engine character or word limits as follows:
- Title: up to 100 characters
- Keywords: up to 20 keywords (comma separated)
- Description: up to 200 characters

The validation functionality needs to be incorporated into the new admin meta tag management functionality as well as the existing category and product meta tag maintenance.

"Meta Tags Updated" plugin was developed based on the following requirements:

There are various elements that are required to meet the data-gathering aspects of the requirements:
- Install Manufacturers Meta-Tags (http://www.zen-cart.com/downloads.php?do=file&id=932 ) This will update the admin’s Catalog->Manufacturers processing to add the meta-tag on/off icon to the manufacturers’ listing display; that icon operates in a manner similar to the built-in categories/products meta-tags. When selected, you can enter the title, keywords and description information for the current manufacturer (multi-lingual).
- Modify Meta Tags for EZ-Pages (http://www.zen-cart.com/downloads.php?do=file&id=746) plugin, separating out the meta-tags to a separate database table. This will update the admin’s Tools->EZ-Pages to add the meta-tag on/off icon to the EZ-Pages overview listing in a manner similar to the Manufacturers Metatags update. The title, keywords and description information can be entered for the EZ-Page in a newly-added section on the page’s input screen.
- Create Localization->Other Meta Tags to enable the meta-tag information to be entered for the "other" pages in the store.  This tool will interrogate the existing store-side pages, displaying those pages that aren’t listed in the ROBOTS_PAGES_TO_SKIP constant and not index, product*_info or document*_info pages – there will be an entry for the home-page meta-tags. The admin user can enter the title, keywords and description for each of these stand-alone pages. While the database will include a languages_id, only a single language is supported initially.

Along with the data-gathering elements, the store-side meta-tag processing will be modified to use the following "rules" in meta-tag processing:

- Once the title, keywords and description information has been gathered from the "source" (which is dependent on the page itself)
- A meta-tag’s output will occur if-and-only-if meta-tag data has been entered – no default values are accepted
- Each of the meta-tag outputs (title, keywords and description) will be truncated at their maximum length (or, for the keywords, count) as specified by Configuration->Maximum Values items added by this processing.

#Optional feature: Intelligent Auto-Population
Adds an additional true/false configuration switch (Configuration->My Store->Default Meta-Tag Values?), default: false. If this switch is set to true, then the following “rules” are used to populate the meta-tag values if no other setting applies:
<table>
<tbody>
<tr>
<td width="20%"><strong>Page Type</strong></td>
<td width="20%"><strong>Title</strong></td>
<td width="20%"><strong>Keywords</strong></td>
<td width="35%"><strong>Description</strong></td>
</tr>
<tr>
<td width="20%"><strong>All product pages</strong></td>
<td width="20%">Product's name [model]</td>
<td width="20%">Not populated</td>
<td width="35%">First nn characters of the product's description</td>
</tr>
<tr>
<td width="20%"><strong>Any category/product listing page</strong></td>
<td width="20%">Category name</td>
<td width="20%">Not populated</td>
<td width="35%">First nn characters of the category description (if present)</td>
</tr>
<tr>
<td width="20%"><strong>Manufacturer's listing</strong></td>
<td width="20%">Manufacturer name</td>
<td width="20%">Not populated</td>
<td width="35%">Not populated</td>
</tr>
<tr>
<td width="20%"><strong>EZ-Pages</strong></td>
<td width="20%">EZ-Page title</td>
<td width="20%">Not populated</td>
<td width="35%">First nn characters of the HTML content, if present.</td>
</tr>
<tr>
<td width="20%"><strong>"Other" Pages</strong></td>
<td width="20%">HEADING_TITLE constant for the page in question</td>
<td width="20%">Not populated</td>
<td width="35%">Not populated</td>
</tr>
</tbody>
</table> 

