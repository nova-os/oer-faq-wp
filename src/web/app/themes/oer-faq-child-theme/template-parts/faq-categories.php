<?php
/**
 * Template part for displaying faq categories
 *
 */

$terms = get_terms([
    'taxonomy' => 'faq_category',
    'fields' => 'all',
]);
?>
<div class="container container-max-width my-5">
    <div class="card shadow">
        <div class="card-body">
            <div class="quad-flex-container ">
                <?php foreach($terms as $t): ?>
                    <a class="quad-flex-item" href="<?php echo get_term_link($t) ?>"><span><?php echo esc_html($t->name) ?></span></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
