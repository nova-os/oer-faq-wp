<?php


add_action( 'wp', 'oer_faq_entry_click' );
function oer_faq_entry_click()
{
    global $wpdb;
    if (is_singular('faq')) {
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->prefix}faq SET clicks = clicks + 1 WHERE post_id = %d LIMIT 1",
               get_the_ID()
            )
         );
    }
}

