<?php
 // These functions are for MailChimp API 3.0 
 function mailchimp_add($email) {
    $mailchimp = new MailChimp(BOX_MAILCHIMP_NEWSLETTER_API_KEY);
    $reply = $mailchimp->list_add_subscriber([
       'id_list' => BOX_MAILCHIMP_NEWSLETTER_ID,
       'email' => $email
    ]);

    if (isset($reply->type)) { 
        $errorMessage = "Unable to run add_subscriber command()!\n" . 
           "\tMsg=" . print_r($reply, true) . "\n";
          $file = DIR_FS_LOGS . '/' . 'MailChimp.log';
          if ($fp = @fopen($file, 'a')) {
            fwrite($fp, $errorMessage);
            fclose($fp);
          }
    }
 }

 function mailchimp_del($email) {
    $mailchimp = new MailChimp(BOX_MAILCHIMP_NEWSLETTER_API_KEY);

    $reply = $mailchimp->list_del_subscriber([
       'id_list' => BOX_MAILCHIMP_NEWSLETTER_ID,
       'email' => $email
    ]);

    if (isset($reply->type)) { 
        $errorMessage = "Unable to run del_subscriber command()!\n" . 
           "\tMsg=" . print_r($reply, true) . "\n";
          $file = DIR_FS_LOGS . '/' . 'MailChimp.log';
          if ($fp = @fopen($file, 'a')) {
            fwrite($fp, $errorMessage);
            fclose($fp);
          }
    }
 }
