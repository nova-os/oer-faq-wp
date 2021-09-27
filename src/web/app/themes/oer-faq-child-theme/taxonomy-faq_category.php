<?php

/**
 * The template for displaying category pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<div id="content" class="site-content container py-0 py-lg-7 mt-5">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <div class="row">
            <div class="col">

                <main id="main" class="site-main">
                    <div class="container container-max-width my-5">
                        <div class="card mb-4 shadow">
                            <div class="card-body">
                                <!-- Title & Description -->
                                <h2><?php single_cat_title(); ?></h2>
                                <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
                                <!-- Grid Layout -->
                                <?php if (have_posts()) : ?>
                                    <ul class="question-list">

                                        <?php while (have_posts()) : the_post(); ?>
                                            <li>
                                                <a class="question-list-link" href="<?php the_permalink(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <div>
                        <?php bootscore_pagination(); ?>
                    </div>

                </main><!-- #main -->

            </div><!-- col -->
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->

<?php
get_footer();
