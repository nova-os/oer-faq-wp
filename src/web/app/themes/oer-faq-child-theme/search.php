<?php
	/**
	 * The template for displaying search results pages
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

                    <?php get_search_form() ?>
                    <?php $has_results = have_posts(); ?>
                    <?php if ( $has_results ) : ?>
                    <div class="container container-max-width my-5">
                        <div class="card my-4">
                            <div class="card-bg question"></div>
                            <div class="card-body">
                                <h3>
                                    <?php
                                        /* translators: %s: search query. */
                                        printf( esc_html__( 'Ergebnisse für: %s', 'bootscore' ),  get_search_query() );
                                    ?>
                                </h3>

                                <div>
                                    <p>
                                        <?php
                                        global $wp_query;
                                        $first_post = absint($wp_query->get('paged')) * $wp_query->get('posts_per_page') + 1;
                                        $last_post = $wp_query->post_count;
                                        $all_posts = $wp_query->found_posts;

                                        ?>

                                        Fragen <?= $first_post ?>-<?= $last_post ?> von <?= $all_posts ?>
                                    </p>
                                </div>

                                <ul id="post-<?php the_ID(); ?>" class="question-list arrow-list">
                                <?php
                                /* Start the Loop */
                                while ( have_posts() ) :
                                    the_post();

                                    /**
                                     * Run the loop for the search to output the results.
                                     * If you want to overload this in a child theme then include a file
                                     * called content-search.php and that will be used instead.
                                     */
                                    get_template_part( 'template-parts/content', 'search' );

                                endwhile;
                                ?>
                                </ul>
                                <?php
                                else :
                                    ?>

                                <?php
                                get_template_part( 'template-parts/content', 'none' );

                                endif;
                                ?>
                            </div>
                            <?php if ( $has_results ) : ?>
                                <div class="card-body my-0">
                                    <?php if ($has_results ) bootscore_pagination(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php get_template_part('template-parts/most-asked-questions') ?>
                    <?php get_template_part('template-parts/faq-categories') ?>

                </main><!-- #main -->

            </div><!-- col -->
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
