<?php


add_action("after_switch_theme", "oer_faq_create_faq_table");

function oer_faq_create_faq_table()
{
    global $wpdb;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table_name = $wpdb->prefix . "faq";

    $sql = "CREATE TABLE `$table_name` (
        `id` int(11) NOT NULL,
        `question_id` varchar(255) NOT NULL,
        `question` text NOT NULL,
        `awnser` text NOT NULL,
        `content` mediumtext NOT NULL,
        `license_type` varchar(255) NOT NULL,
        `license_title` varchar(255) NOT NULL,
        `license_source_link` varchar(255) NOT NULL,
        `license_authors` varchar(255) NOT NULL,
        `license` varchar(255) NOT NULL,
        `license_link` varchar(255) NOT NULL,
        `license_edited_by` varchar(255) NOT NULL,
        `license_new` varchar(255) NOT NULL,
        `license_new_link` varchar(255) NOT NULL,
        `license_edit_notice` varchar(255) NOT NULL,
        `license_2_type` varchar(255) NOT NULL,
        `license_2_title` varchar(255) NOT NULL,
        `license_2_source_link` varchar(255) NOT NULL,
        `license_2_authors` varchar(255) NOT NULL,
        `license_2` varchar(255) NOT NULL,
        `license_2_link` varchar(255) NOT NULL,
        `license_2_edited_by` varchar(255) NOT NULL,
        `license_2_new` varchar(255) NOT NULL,
        `license_2_new_link` varchar(255) NOT NULL,
        `license_2_edit_notice` varchar(255) NOT NULL,
        `tags` text NOT NULL,
        `categories` text NOT NULL,
        `related_questions` text NOT NULL,
        `clicks` int(11) NOT NULL DEFAULT 0,
        `post_id` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    dbDelta($sql);
}


add_action('pre_get_posts', 'oer_faq_search_query', 1000);

function oer_faq_search_query($wp_query)
{
    if ($wp_query->get('faq') && $wp_query->get('s')) {
        $wp_query->set('s', '');
    }
    return $wp_query;
}


// Join for searching metadata
function oer_faq_search_clauses($clauses, $wp_query)
{
    global $wpdb;
    if (!$wp_query->is_search() || !isset($wp_query->query_vars['s'])) {
        return $clauses;
    }

    $searchQuery = $wp_query->query_vars['s'];
    $searchMapping = [
        // '/\bCC\b/i' => 'Creative Commons',
        // '/\bBY\b/i' => 'Attribution',
        // '/\bSA\b/i' => 'ShareAlike',
        // '/\bNC\b/i' => 'Non Commercial',
        // '/\bND\b/i' => 'No Derivatives',
        // '/\bKI\b/i' => 'Künstliche Intelligenz',
        // '/\bVG\b/i' => 'Verwertungsgesellschaft',
        // '/\b3D\b/i' => 'dreidimensional',
        // '/\bOER\b/i' => 'Open Educational Resources',
    ];

    $synonyms = get_option('oer_faq_synonyms');
    $searchQuery = preg_replace(array_keys($searchMapping), array_values($searchMapping), $searchQuery);
    $searchQuery = preg_replace('/@|~|<|>|\*|\(|\)|\+|\=|\.|\?|\,|\!|\;/', '', $searchQuery);
    $searchQueryNorm = OerFaqImporter::normalize($searchQuery, $synonyms);
    $query = trim($searchQueryNorm);
    $query = preg_replace('/@|~|<|>|\*|\(|\)|\+|\=|\.|\?|\,|\!|\;/', '', $query);
    $query = str_replace('-', '_', $query);
    $query = preg_replace('/\s\s+/', ' ', $query);

    $naturalTextQuery = explode(" ", str_replace('"', '', $query));
    // $naturalTextQuery = array_map(function($s) { return '"' . $s . '"';}, $naturalTextQuery);
    $naturalTextQuery = array_filter($naturalTextQuery, function($word) { return strlen($word) > 0 && $word[0] !== '-'; });

    if (count($naturalTextQuery)) {
        $naturalTextQuery[count($naturalTextQuery) - 1] =  $naturalTextQuery[count($naturalTextQuery) - 1] . '*';
    }

    // $naturalTextQuery = array_map(function($word) {
    //     return '+' . $word;
    // }, $naturalTextQuery);

    $naturalTextQuery = implode(" ", $naturalTextQuery);
    $escapedSearch = esc_sql($searchQuery);
    $escapedSearchNorm = esc_sql($searchQueryNorm);
    $ntm = "(
        IF ({$wpdb->prefix}faq.question LIKE '{$escapedSearch}%', 20, IF ({$wpdb->prefix}faq.question_norm LIKE '{$escapedSearchNorm}%', 15, 0))
        + MATCH ({$wpdb->prefix}faq.question_norm) AGAINST ('{$escapedSearchNorm}' IN NATURAL LANGUAGE MODE) * 5
        + MATCH ({$wpdb->prefix}faq.tags_norm) AGAINST ('{$escapedSearchNorm}' IN NATURAL LANGUAGE MODE) * 0.25
        + MATCH ({$wpdb->prefix}faq.content_norm) AGAINST ('{$escapedSearchNorm}' IN NATURAL LANGUAGE MODE) * 0.1
        + MATCH ({$wpdb->prefix}faq.awnser_norm) AGAINST ('{$escapedSearchNorm}' IN NATURAL LANGUAGE MODE) * 0.1
    )";

    $btm = "MATCH ({$wpdb->prefix}faq.question_norm, {$wpdb->prefix}faq.tags_norm, {$wpdb->prefix}faq.content_norm, {$wpdb->prefix}faq.awnser_norm) AGAINST ('" . esc_sql($naturalTextQuery) . "' IN BOOLEAN MODE)";

    $clauses['fields'] .= ", {$ntm} as score";
    $clauses['join'] .= "INNER JOIN {$wpdb->prefix}faq ON $wpdb->posts.ID = {$wpdb->prefix}faq.post_id ";;
    $clauses['where'] = " AND {$btm}";
    $clauses['orderby'] = " score DESC";
    // var_dump($clauses);
    return $clauses;
}

add_filter('posts_clauses', 'oer_faq_search_clauses', 20, 2);


// function oer_faq_search_request($request, $wp_query)
// {

//     return $request;
// }

// add_filter('posts_request', 'oer_faq_search_request', 10, 2);



add_action('wp_ajax_ajax_search_results', 'oer_faq_ajax_search_results');
add_action('wp_ajax_nopriv_ajax_search_results', 'oer_faq_ajax_search_results');

function oer_faq_ajax_search_results() {
    $the_query = new WP_Query( [
        'posts_per_page' => 10,
        's' => $_GET['s'],
        'post_type' => 'faq'
    ]);
    $result = array_map(function($post) {
        return [
            'post_id' => $post->ID,
            'title' => get_the_title($post),
            'url' => get_the_permalink($post),
            'score' => $post->score,
        ];
    }, $the_query->get_posts());
    wp_send_json_success([
        'posts' => $result,
        'count' => $the_query->found_posts,
    ]);
}
