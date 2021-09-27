<?php

?>

<div id="content" class="site-content container py-0 py-lg-7 mt-5">
    <div id="primary" class="content-area">
        <!-- Hook to add something nice -->

        <div class="row">
            <div class="col">
                <main id="main" class="site-main">
                    <div class="container container-max-width">
                        <div class="card mb-4 shadow">
                            <div class="card-body">
                                <?php the_content() ?>
                            </div>
                        </div>
                    </div>
                </main> <!-- #main -->

            </div><!-- col -->
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->

<?php get_footer(); ?>
