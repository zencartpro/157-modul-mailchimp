<?php
$content = '';
$content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">';
$content .= '';
$content .= BOX_MAILCHIMP_PITCH;
$content .= '<br />';
$content .= '<br />';

$content .= '<form target="_blank" action="' . BOX_MAILCHIMP_NEWSLETTER_URL . '?' . 
'u='. BOX_MAILCHIMP_NEWSLETTER_U . '&amp;' . 
'id=' . BOX_MAILCHIMP_NEWSLETTER_ID . '" '. 
'method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form">';
$content .= ENTRY_EMAIL_ADDRESS; 
$content .= '<input type="text" name="EMAIL" value="" /><br />';
// Tragen Sie hier vor dem <br/> den Spam Bot Schutzcode ein so wie in der liesmich.txt beschrieben 
$content .= '<br />';
$content .= '<div style="margin-top: 5px;">';
$content .= '<input type="submit" name="submit" value="'.BOX_MAILCHIMP_SUBSCRIBE . '" />';   
$content .= '</div>';
$content .= '</form>';
$content .= '</div>';