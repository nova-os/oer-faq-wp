<?php


/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function oer_faq_register_post_types() {
    register_post_type( 'faq', [
        'labels'             => [
            'name'                  => _x( 'FAQ', 'Post type general name' ),
            'singular_name'         => _x( 'FAQ Eintrag', 'Post type singular name' ),
            'menu_name'             => _x( 'FAQ', 'Admin Menu text' ),
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'faq' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author' ),
    ]);

    register_taxonomy( 'faq_tag', 'faq', [
        'labels'             => [
            'name'              => _x( 'FAQ-Schlagwort', 'taxonomy general name'),
            'singular_name'     => _x( 'Schlagwort', 'taxonomy singular name'),
        ],
        'public'             => true,
    ]);

    register_taxonomy( 'faq_category', 'faq', [
        'labels'             => [
            'name'              => _x( 'FAQ-Kategorien', 'taxonomy general name'),
            'singular_name'     => _x( 'Kategorie', 'taxonomy singular name'),
        ],
        'public'             => true,
    ]);
}

add_action( 'init', 'oer_faq_register_post_types' );


add_filter('manage_edit-faq_columns', 'oer_faq_edit_admin_columns') ;
function oer_faq_edit_admin_columns($columns) {
  $columns = [
    'cb' => $columns['cb'],
    'question_id' => 'Frage-ID',
    'title' => $columns['title'],
    'date' => $columns['date'],
    'helpful' => 'Hilfreich'
  ];
  return $columns;
}

add_action ('manage_faq_posts_custom_column', 'oer_faq_post_custom_columns', 10, 2);
function oer_faq_post_custom_columns($column, $post_id) {
  switch ($column) {
    case "question_id":
        $faq = get_faq_data($post_id);
        echo $faq->question_id;
    break;
  }
}
