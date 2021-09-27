<?php

require get_stylesheet_directory() . '/inc/post-types.php';
require get_stylesheet_directory() . '/inc/faq-import.php';
require get_stylesheet_directory() . '/inc/search-index.php';
require get_stylesheet_directory() . '/inc/settings-page.php';
require get_stylesheet_directory() . '/inc/statistics.php';


// style and scripts
add_action( 'wp_enqueue_scripts', 'bootscore_5_child_enqueue_styles',21 );
function bootscore_5_child_enqueue_styles() {
    // Dequeue parent-theme bootstrap.min.css
    wp_dequeue_style( 'bootstrap' );
    wp_deregister_style( 'bootstrap' );

    // style.css
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // Enqueue compile custom.css
    wp_enqueue_style( 'child-theme-bootstrap', get_stylesheet_directory_uri() .'/css/custom.css' , array('parent-style'));
    // wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
    // custom.js
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', false, '', true);

}

function bootscore_5_child_enqueue_admin_styles() {
    wp_enqueue_style('admin-styles', get_stylesheet_directory_uri().'/css/admin.css');
}
add_action('admin_enqueue_scripts', 'bootscore_5_child_enqueue_admin_styles');

function oer_faq_question_redirect() {
    global $wpdb;
    $faq_question_id = get_query_var( 'faq' );
    if (isset($faq_question_id)) {
        $post_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}faq WHERE question_id = %s;", $faq_question_id ) );
        if (isset($post_id)) {
            wp_redirect(get_the_permalink($post_id));
            exit;
        }
    }
}
add_action( 'template_redirect', 'oer_faq_question_redirect' );

function get_faq_data($post_id = null) {
    global $wpdb;
    if (!isset($post_id)) {
        $post_id = get_the_ID();
    }
    return $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}faq WHERE post_id = %d;", $post_id) );
}


function get_most_asked_questions() {
    global $wpdb;
    return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}faq ORDER BY clicks DESC LIMIT 10" );
}

function the_faq_license($faq) {
    echo '<p>';
    echo get_faq_license_text($faq, false);
    echo '</p>';
    if (isset($faq->license_2_type) && $faq->license_2_type) {
        echo '<p>';
        echo get_faq_license_text($faq, true);
        echo '</p>';
    }
    $permalink = home_url('/faq/' . $faq->question_id);
    echo '<p>Permalink: <a href="' . esc_attr($permalink) . '">' . esc_html($permalink) . '</a></p>';
}

function get_faq_license_text($faq, $second_license) {
    $template = '';

    if (!$second_license) {
        if ($faq->license_type === 'a') {
            $template = "Der Text für die Antwort ist eine wortwörtliche inhaltliche Übernahme aus: <a href=\"{license_source_link}\">{license_title}</a>. Von: {license_authors} unter <a href=\"{license_link}\">{license}</a>.";
        } elseif ($faq->license_type === 'b') {
            $template = "Der Text für die Antwort ist eine wortwörtliche inhaltliche Übernahme aus: <a href=\"{license_source_link}\">{license_title}</a>. Von: {license_authors} unter <a href=\"{license_link}\">{license}</a>. (Bearbeitung: {license_edit_notice})";
        } elseif ($faq->license_type === 'c') {
            $template = "Der Text für die Antwort beruht auf dem Original aus <a href=\"{license_source_link}\">{license_title}</a> von: {license_authors} unter <a href=\"{license_link}\">{license}</a>. Die inhaltliche Bearbeitung durch {license_edited_by} steht unter <a href=\"{license_new_link}\">{license_new}</a>. (Bearbeitung: {license_edit_notice})";
        } elseif ($faq->license_type === 'd') {
            $template = "Die Antwort stammt von {license_authors} und wurde für das Projekt OER FAQ erstellt. Sie steht unter <a href=\"{license_link}\">{license}</a>.";
        } elseif ($faq->license_type === 'e') {
            $template = "Die Texte für die Antwort und die weiteren Hinweise wurden übernommen aus: <a href=\"{license_source_link}\">{license_title}</a>. Von: {license_authors} unter <a href=\"{license_link}\">{license}</a>.";
        } elseif ($faq->license_type === 'f') {
            $template = "Der Text für die Antwort und die weiteren Hinweise ist eine inhaltliche Übernahme aus: <a href=\"{license_source_link}\">{license_title}</a>. Von: {license_authors} unter <a href=\"{license_link}\">{license}</a>. (Bearbeitung: {license_edit_notice})";
        } elseif ($faq->license_type === 'g') {
            $template = "Der Text für die Antwort und die weiteren Hinweise beruht auf dem Original aus <a href=\"{license_source_link}\">{license_title}</a> von: {license_authors} unter <a href=\"{license_link}\">{license}</a>. Die inhaltliche Bearbeitung durch {license_edited_by} steht unter <a href=\"{license_new_link}\">{license_new}</a>. (Bearbeitung: {license_edit_notice})";
        } elseif ($faq->license_type === 'h') {
            $template = "Der Text für die weiteren Hinweise stammt von {license_authors} und wurde für das Projekt OER FAQ erstellt. Sie steht unter <a href=\"{license_link}\">{license}</a>.";
        }
    } else {
        if ($faq->license_2_type === 'i') {
            $template = "Der Text für die weiteren Hinweise wurde übernommen aus: <a href=\"{license_2_source_link}\">{license_2_title}</a>. Von: {license_2_authors} unter <a href=\"{license_2_link}\">{license_2}</a>.";
        } elseif ($faq->license_2_type === 'j') {
            $template = "Der Text für die weiteren Hinweise ist eine inhaltliche Übernahme aus: <a href=\"{license_2_source_link}\">{license_2_title}</a>. Von: {license_2_authors} unter <a href=\"{license_2_link}\">{license_2}</a>. (Bearbeitung: {license_2_edit_notice})";
        } elseif ($faq->license_2_type === 'k') {
            $template = "Der Text für die weiteren Hinweise beruht auf dem Original aus <a href=\"{license_2_source_link}\">{license_2_title}</a> von: {license_2_authors} unter <a href=\"{license_2_link}\">{license_2}</a>. Die inhaltliche Bearbeitung durch {license_2_edited_by} steht unter <a href=\"{license_2_new_link}\">{license_2_new}</a>. (Bearbeitung: {license_2_edit_notice})";
        } elseif ($faq->license_2_type === 'l') {
            $template = "Der Text für die Antwort und die weiteren Hinweise stammt von {license_2_authors} und wurde für das Projekt OER FAQ erstellt. Sie steht unter <a href=\"{license_2_new}\">{license_2_new_link}</a>.";
        }
    }

    foreach($faq as $variable => $value) {
        $template = str_replace('{' . $variable . '}', $value, $template);
    }
    return $template;
}

function get_related_questions($faq) {
    global $wpdb;
    if (empty($faq->related_questions)) {
        return [];
    }
    $list = explode(',', $faq->related_questions);
    $list = esc_sql($list);
    $list = array_map(function($s) { return '"' . $s . '"'; }, $list);
    $list = implode(',', $list);
    return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}faq WHERE question_id IN ({$list}) ORDER BY clicks DESC LIMIT 10" );
}

function get_similar_questions($faq) {
    global $wpdb;
    $post_id = $faq->post_id;
    return $wpdb->get_results("
        SELECT
            f.*,
           (
                SELECT  COUNT(tt1.term_taxonomy_id)
                FROM {$wpdb->prefix}term_taxonomy AS tt1
                INNER JOIN {$wpdb->prefix}term_relationships AS tr1 ON (tr1.term_taxonomy_id = tt1.term_taxonomy_id)
                INNER JOIN {$wpdb->prefix}term_taxonomy AS tt2 ON (tt2.term_id = tt1.term_id)
                INNER JOIN {$wpdb->prefix}term_relationships AS tr2 ON (tr2.term_taxonomy_id = tt2.term_taxonomy_id)
                WHERE tt1.taxonomy = \"faq_tag\"
                AND tr1.object_id = {$post_id}
                AND tt2.taxonomy = \"faq_tag\"
                AND tr2.object_id <> {$post_id}
                AND tr2.object_id = f.post_id
            ) AS common_count
        FROM {$wpdb->prefix}faq AS f
        ORDER BY common_count DESC LIMIT 10" );
}

/** Add custom css-classes to the gravity forms submit button */
function add_custom_css_classes( $button, $form ) {
    $dom = new DOMDocument();
    $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $classes = $input->getAttribute( 'class' );
    $classes .= " btn btn-primary mb-0 ms-auto d-block";
    $input->setAttribute( 'class', $classes );
    return $dom->saveHtml( $input );
}
add_filter( 'gform_submit_button', 'add_custom_css_classes', 10, 2 );

/** Was this helpful plugin */
function wthf_output() {

    // Get post id
    $post_id = get_the_ID();

    $content = "";

    // Enqueue style and scripts
    wp_enqueue_style('wthf-style', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_script('wthf-script', plugins_url('/js/script.js', __FILE__), array('jquery'), '1.0', TRUE);
    wp_add_inline_script('wthf-script', 'var nonce_wthf = "'.wp_create_nonce("wthf_nonce").'";var ajaxurl = "' . admin_url('admin-ajax.php') . '";', TRUE);

    $onclick_yes = "_paq.push(['trackEvent', 'helpful', 'Was this helpful', 'yes']);";
    $onclick_no = "_paq.push(['trackEvent', 'helpful', 'Was this helpful, 'no']);";

    if(!isset($_COOKIE["helpful_id_".$post_id])){
        $content = '<div id="was-this-helpful" data-post-id="'.$post_id.'" data-thank-text="'.get_option("wthf_thank_text").'"><div id="wthf-title">'.get_option("wthf_question_text").'</div><div id="wthf-yes-no"><span onclick="' . $onclick_yes . '" id="was-this-helpful-y" data-value="1">'.get_option("wthf_yes_text").'</span><span id="was-this-helpful-n" onclick="' . $onclick_no . '" data-value="0">'.get_option("wthf_no_text").'</span></div></div>';
    } else {
        $content = '<div id="was-this-helpful" data-post-id="'.$post_id.'">' . get_option("wthf_thank_text") . '</div>';
    }

    return $content;
}

add_shortcode( 'was_this_article_helpful', 'wthf_shortcode' );

/** remove gravity forms "required legend" **/
add_filter( 'gform_required_legend', function( $legend, $form ) {
    return '';
}, 10, 2 );
