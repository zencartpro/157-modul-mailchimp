<?php
// test if box should display
  $show_mailchimp_sidebox = true;

  if ($show_mailchimp_sidebox == true) {
      require($template->get_template_dir('tpl_mailchimp_sidebox.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_mailchimp_sidebox.php');
      $title =  BOX_HEADING_MAILCHIMP_SIDEBOX;
      $title_link = false;
      $left_corner = false;
      $right_corner = false;
      $right_arrow = false;
      require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
 }
