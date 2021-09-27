<?php
    /**
     * The template for displaying all pages
     *
     * This is the template that displays all pages by default.
     * Please note that this is the WordPress construct of pages
     * and that other 'pages' on your WordPress site may use a
     * different template.
     *
     * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
     *
     * @package Bootscore
     */

    get_header();
    ?>

<div class="relative search-container">
    <div class="search-inner">
        <?php get_search_form() ?>
    </div>
</div>


<div id="content" class="site-content container py-0 pb-lg-7 mt-0">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <div class="row">
            <div class="col">

                <main id="main" class="site-main">

                    <?php get_template_part('template-parts/most-asked-questions') ?>
                    <?php get_template_part('template-parts/faq-categories') ?>

                    <?php if ( !empty( get_the_content() ) ): ?>
                        <div class="fp-content container container-max-width mb-6 mb-lg-7 px-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                </main><!-- #main -->

            </div><!-- col -->
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->

<?php
get_footer();
