<?php
// -----
// Part of the "Meta Tags Updated" plugin, created for http://overthehillweb.com
// Copyright (c) 2015 Vinos de Frutas Tropicales
//
function get_other_pages_metatags_title ($page_name, $languages_id) {
  global $db;
  $db_info = $db->Execute ("SELECT * FROM " . TABLE_OTHER_PAGES_META_TAGS . " WHERE other_pages_name = '$page_name' AND language_id = $languages_id LIMIT 1");
  return ($db_info->EOF) ? '' : $db_info->fields['metatags_title'];
  
}

function get_other_pages_metatags_keywords ($page_name, $languages_id) {
  global $db;
  $db_info = $db->Execute ("SELECT * FROM " . TABLE_OTHER_PAGES_META_TAGS . " WHERE other_pages_name = '$page_name' AND language_id = $languages_id LIMIT 1");
  return ($db_info->EOF) ? '' : $db_info->fields['metatags_keywords'];
  
}
function get_other_pages_metatags_description ($page_name, $languages_id) {
  global $db;
  $db_info = $db->Execute ("SELECT * FROM " . TABLE_OTHER_PAGES_META_TAGS . " WHERE other_pages_name = '$page_name' AND language_id = $languages_id LIMIT 1");
  return ($db_info->EOF) ? '' : $db_info->fields['metatags_description'];
  
}

function get_other_pages_metatags_icon ($which_tag, $page_name, $languages_id) {
  switch ($which_tag) {
    case 'metatags_title': $metatag_info = get_other_pages_metatags_title ($page_name, $languages_id); break;
    case 'metatags_keywords': $metatag_info = get_other_pages_metatags_keywords ($page_name, $languages_id); break;
    case 'metatags_description': $metatag_info = get_other_pages_metatags_description ($page_name, $languages_id); break;
    default: $metatag_info = ''; break;
    
  }
  if (zen_not_null ($metatag_info)) {
    $icon_name = 'icon_edit_metatags_on.gif';
    $icon_alt = ICON_METATAGS_ON;
  } else {
    $icon_name = 'icon_edit_metatags_off.gif';
    $icon_alt = ICON_METATAGS_OFF;
  }
  return zen_image (DIR_WS_IMAGES . $icon_name, $icon_alt);
  
}

require('includes/application_top.php');

$action = (isset ($_GET['action']) ? $_GET['action'] : '');
$languages = zen_get_languages ();
switch ($action) {
  case 'save': {
    $page_name = zen_db_prepare_input (isset ($_POST['page_name'])) ? $_POST['page_name'] : '';
    $metatags_title = zen_db_prepare_input ($_POST['metatags_title']);
    $metatags_keywords = zen_db_prepare_input ($_POST['metatags_keywords']);
    $metatags_description = zen_db_prepare_input ($_POST['metatags_description']);
    foreach ($languages as $current_language) {
      $languages_id = $current_language['id'];
      $sql_data_array = array ();
      $sql_data_array['metatags_title'] = zen_clean_html ($metatags_title[$languages_id]);
      $sql_data_array['metatags_keywords'] = zen_clean_html ($metatags_keywords[$languages_id]);
      $sql_data_array['metatags_description'] = zen_clean_html ($metatags_description[$languages_id]);
      
      $presence_check = $db->Execute ("SELECT * FROM " . TABLE_OTHER_PAGES_META_TAGS . " WHERE other_pages_name = '$page_name' AND language_id = $languages_id LIMIT 1");
      if (!zen_not_null ($sql_data_array['metatags_title'] . $sql_data_array['metatags_keywords'] . $sql_data_array['metatags_description'])) {
        if (!$presence_check->EOF) {
          $db->Execute ("DELETE FROM " . TABLE_OTHER_PAGES_META_TAGS . " WHERE other_pages_name = '$page_name' AND language_id = $languages_id LIMIT 1");
          $messageStack->add_session (sprintf (SUCCESS_META_TAGS_UPDATED, $page_name, TEXT_DELETED), 'success');
          
        }
      } else {
        if ($presence_check->EOF) {
          $sql_data_array['other_pages_name'] = $page_name;
          $sql_data_array['language_id'] = $languages_id;
          zen_db_perform (TABLE_OTHER_PAGES_META_TAGS, $sql_data_array);
          $messageStack->add_session (sprintf (SUCCESS_META_TAGS_UPDATED, $page_name, TEXT_ADDED), 'success');
          
        } else {
          zen_db_perform (TABLE_OTHER_PAGES_META_TAGS, $sql_data_array, 'update', "other_pages_name = '$page_name' AND language_id = $languages_id");
          $messageStack->add_session (sprintf (SUCCESS_META_TAGS_UPDATED, $page_name, TEXT_UPDATED), 'success');
          
        }
      }
    }
    zen_redirect (zen_href_link (FILENAME_OTHER_PAGES_META_TAGS, ($page_name == '') ? '' : "page_name=$page_name"));
    break;
  }
  default: {
    break;
  }
}

$error_message = '';
$languages_id = (int)$_SESSION['languages_id'];
$template_info = $db->Execute ("SELECT template_dir FROM " . TABLE_TEMPLATE_SELECT . " WHERE template_language IN (0, $languages_id) LIMIT 1");
if ($template_info->EOF) {
  $error_message = sprintf (ERROR_NO_TEMPLATE_DIR, $languages_id);

} else {
  $template_dir = $template_info->fields['template_dir'];
  $language = $_SESSION['language'];
  $meta_tags_filename = DIR_FS_CATALOG . "includes/languages/$language/$template_dir/meta_tags.php";
  if (file_exists ($meta_tags_filename)) {
    $meta_tags_source = file_get_contents ($meta_tags_filename);
    
  } else {
    $meta_tags_filename1 = $meta_tags_filename;
    $meta_tags_filename = DIR_FS_CATALOG . "includes/languages/$language/meta_tags.php";
    if (!file_exists ($meta_tags_filename)) {
      $error_message = sprintf (ERROR_NO_META_TAGS_FILE, $meta_tags_filename1, $meta_tags_filename);
      
    } else {
      $meta_tags_source = file_get_contents ($meta_tags_filename);
      
    }
  }
  
  if ($error_message == '') {
    $search_string = "'ROBOTS_PAGES_TO_SKIP'";
    $pages_to_skip_start = strpos ($meta_tags_source, $search_string);
    if ($pages_to_skip_start === false) {
      $error_message = sprintf (ERROR_MISSING_PAGES_TO_SKIP, $meta_tags_filename);
      
    } else {
      $pts_definition_start = strpos ($meta_tags_source, "'", $pages_to_skip_start + strlen ($search_string));
      if ($pts_definition_start === false ) {
        $error_message = sprintf (ERROR_MISSING_PAGES_TO_SKIP_DEFINITION, $meta_tags_filename);
        
      } else {
        $pts_definition_start++;
        $pts_definition_end = strpos ($meta_tags_source, "'", $pts_definition_start);
        if ($pts_definition_end === false) {
          $error_message = sprintf (ERROR_MISSING_PAGES_TO_SKIP_DEFINITION, $meta_tags_filename);
          
        } else {
          $pages_to_skip = explode (',', substr ($meta_tags_source, $pts_definition_start, $pts_definition_end - $pts_definition_start));
          $pages_to_skip[] = FILENAME_EZPAGES;
          unset ($meta_tags_source);
          
          $page_dir = DIR_FS_CATALOG . 'includes/modules/pages/';
          $page_names_list = glob ($page_dir . '*', GLOB_ONLYDIR);
          if ($page_names_list === false) {
            $page_names_list = array ();
            
          }
          for ($i = 0, $n = count ($page_names_list); $i < $n; $i++) {
            $page_names_list[$i] = str_replace ($page_dir, '', $page_names_list[$i]);
            if (in_array ($page_names_list[$i], $pages_to_skip) || (strpos ($page_names_list[$i], 'product_') === 0 || strpos ($page_names_list[$i], 'document_') === 0)) {
              unset ($page_names_list[$i]);
              
            }
          }
          $page_names_list = array_values ($page_names_list);
          unset ($pages_to_skip);
          
        }
      }
    }
  }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<style type="text/css">
<!--
.centered { text-align: center; }
.right { text-align: right; }
.left { text-align: left; }
.instructions { font-size: 12px; padding-bottom: 10px; padding-top: 10px; }
.name-input { width: 90%; }
.smaller { font-size: smaller; }
.error_message { background-color: red; color: white; font-weight: bold; }
.dataTableRow:hover > .dataTableContent { background-color: white!important; }
-->
</style>
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
</head>
<body onload="init();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
<?php
if ($error_message != '') {
?>
          <tr>
            <td class="error-message"><?php echo $error_message; ?></td>
          </tr>
          
        </table></td>
      </tr>
<?php
} else {
?>          
          <tr>
            <td class="instructions"><?php echo sprintf (TEXT_INSTRUCTIONS, $meta_tags_filename); ?></td>
          </tr>
          
        </table></td>
      </tr>

      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGE_NAME; ?></td>
                <td class="dataTableHeadingContent centered"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent centered"><?php echo TABLE_HEADING_KEYWORDS; ?></td>
                <td class="dataTableHeadingContent centered"><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>

<?php
  $active_page = (isset ($_GET['page_name'])) ? $_GET['page_name'] : $page_names_list[0];
  foreach ($page_names_list as $current_page) {
    $metatags_title_status = $metatags_keywords_status = $metatags_description_status = '';
    $number_of_languages = count ($languages);
    foreach ($languages as $current_language) {
      $languages_id = $current_language['id'];
      $languages_icon = ($number_of_languages == 1) ? '' : (zen_image (DIR_WS_CATALOG_LANGUAGES . $current_language['directory'] . '/images/' . $current_language['image'], $current_language['name']) . '&nbsp;');
      $metatags_title_status .= $languages_icon . '&nbsp;' . get_other_pages_metatags_icon ('metatags_title', $current_page, $languages_id) . '&nbsp;&nbsp;';
      $metatags_keywords_status .= $languages_icon . '&nbsp;' . get_other_pages_metatags_icon ('metatags_keywords', $current_page, $languages_id) . '&nbsp;&nbsp;';
      $metatags_description_status .= $languages_icon . '&nbsp;' . get_other_pages_metatags_icon ('metatags_description', $current_page, $languages_id) . '&nbsp;&nbsp;';
    }
?>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo $current_page; ?></td>
                <td class="dataTableContent centered"><?php echo $metatags_title_status; ?></td>
                <td class="dataTableContent centered"><?php echo $metatags_keywords_status; ?></td>
                <td class="dataTableContent centered"><?php echo $metatags_description_status; ?></td>
                <td class="dataTableContent right">
<?php
    if ($current_page == $active_page) { 
      echo zen_image (DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
      
    } else { 
      echo '<a href="' . zen_href_link (FILENAME_OTHER_PAGES_META_TAGS, 'page_name=' . $current_page) . '">' . zen_image (DIR_WS_IMAGES . 'icon_edit.gif', IMAGE_EDIT) . '</a>';
      
    } 
?>
                &nbsp;</td>
              </tr>
<?php
  }  //-END foreach processing each "other" page
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array ('text' => '<b>' . sprintf (TEXT_INFO_EDIT_META_TAGS, $active_page) . '</b>');

  $contents = array ('form' => zen_draw_form ('status', FILENAME_OTHER_PAGES_META_TAGS, 'page_name=' . $active_page  . '&action=save') . zen_draw_hidden_field ('page_name', $active_page));
  $contents[] = array ('text' => TEXT_INFO_EDIT_INTRO);

  $contents[] = array ('text' => '<br>' . TEXT_INFO_METATAGS_TITLE);
  $title_info = '';
  foreach ($languages as $current_language) {
    $title_info .= '<br />' . zen_image (DIR_WS_CATALOG_LANGUAGES . $current_language['directory'] . '/images/' . $current_language['image'], $current_language['name']) . '&nbsp;' . zen_draw_input_field ('metatags_title[' . $current_language['id'] . ']', htmlspecialchars (get_other_pages_metatags_title ($active_page, $current_language['id']), ENT_COMPAT, CHARSET, TRUE));
    
  }
  $contents[] = array ('text' => $title_info);
  
  $contents[] = array ('text' => '<br>' . TEXT_INFO_METATAGS_KEYWORDS);
  $title_info = '';
  foreach ($languages as $current_language) {
    $title_info .= '<br />' . zen_image (DIR_WS_CATALOG_LANGUAGES . $current_language['directory'] . '/images/' . $current_language['image'], $current_language['name']) . '&nbsp;' . zen_draw_textarea_field ('metatags_keywords[' . $current_language['id'] . ']', 'soft', '100%', '3', htmlspecialchars (get_other_pages_metatags_keywords ($active_page, $current_language['id']), ENT_COMPAT, CHARSET, TRUE));
    
  }
  $contents[] = array ('text' => $title_info);
  
  $contents[] = array ('text' => '<br>' . TEXT_INFO_METATAGS_DESCRIPTION);
  $title_info = '';
  foreach ($languages as $current_language) {
    $title_info .= '<br />' . zen_image (DIR_WS_CATALOG_LANGUAGES . $current_language['directory'] . '/images/' . $current_language['image'], $current_language['name']) . '&nbsp;' . zen_draw_textarea_field ('metatags_description[' . $current_language['id'] . ']', 'soft', '100%', '3', htmlspecialchars (get_other_pages_metatags_description ($active_page, $current_language['id']), ENT_COMPAT, CHARSET, TRUE));
    
  }
  $contents[] = array ('text' => $title_info);
  
  $contents[] = array ('align' => 'center', 'text' => '<br>' . zen_image_submit ('button_update.gif', IMAGE_UPDATE) . ' <a href="' . zen_href_link (FILENAME_OTHER_PAGES_META_TAGS, 'page_name=' . $active_page) . '">' . zen_image_button ('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  
  $box = new box;
?>
            <td width="25%" valign="top"><?php echo $box->infoBox ($heading, $contents); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
}  //-Primary display, no errors found in pre-processing
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>