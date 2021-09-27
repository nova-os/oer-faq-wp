<?php
/**
 * Template part for displaying most asked quetions
 *
 */

?>
<?php get_template_part(
    'template-parts/questions-list',
    null, [
        'title' => 'Die am häufigsten genutzten Fragen',
        'questions' => get_most_asked_questions(),
        'bg' => 'question',
    ]);
?>
