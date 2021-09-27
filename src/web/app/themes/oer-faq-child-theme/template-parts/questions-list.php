<?php
/**
 * Template part for displaying a box with a questions list
 *
 */

    $search_query = get_search_query();
    $parameter = !empty($search_query) ? '?o=' . $search_query : '';
?>
<?php if (!empty($args['questions'])): ?>
<div class="container container-max-width mb-6 mb-lg-7 px-4">
    <div class="card shadow" style="position:relative;">
        <div class="card-bg <?= !empty($args['bg']) ? $args['bg'] : 'question' ?>"></div>
        <div class="card-body" style="">
            <h2><?php echo esc_html($args['title']); ?></h2>
            <ul class="question-list <?php echo isset($args['list-class']) ? $args['list-class'] : '' ?>">
                <?php foreach($args['questions'] as $faq): ?>
                    <li><a class="question-list-link" href="<?php the_permalink($faq->post_id); ?><?php echo $parameter ?>"><?php echo esc_html($faq->question) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>
