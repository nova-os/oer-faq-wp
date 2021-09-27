<?php
	/*
	 * Template Post Type: faw
	 */

	 get_header();
     $faq = get_faq_data();

     $go_back_url = '/';
     $go_back_text = 'Zurück zur Startseite';

     if (isset($_GET['s']) || isset($_GET['o'])) {
         if (isset($_GET['o'])) {
             $go_back_url = '/?s=' . esc_attr($_GET['o']);
         } else {
             $go_back_url = '/?s=' . esc_attr($_GET['s']);
         }

         $go_back_text = 'Zurück zur übersicht';
     }

?>

<div id="content" class="site-content container py-0 py-lg-7 mt-5">
    <div id="primary" class="content-area">
        <!-- Hook to add something nice -->

        <div class="row">
            <div class="col">
                <main id="main" class="site-main">
                    <?php get_search_form() ?>
                    <div class="container container-max-width mb-6 mb-lg-7">
                        <div class="card mb-4 shadow">
                            <a href="<?php echo $go_back_url ?>" class="text-uppercase go-back">
                                <i class="fa fa-arrow-left"></i>
                                <?php echo $go_back_text ?>
                            </a>
                            <div class="card-bg question "></div>
                            <div class="card-body">
                                <header class="entry-header">
                                    <?php the_post(); ?>
                                    <?php the_title('<h2>', '</h2>'); ?>
                                </header>

                                <div class="entry-excerpt">
                                    <p>
                                        <?php echo nl2br($faq->awnser) ?>
                                    </p>
                                </div>

                                <div class="accordion accordion-flush mt-3" id="faqAccordion">
                                    <?php if(!empty($faq->content)): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header h2" id="faqContent">
                                            <button class="accordion-button bg-primary collapsed border-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContent" aria-expanded="false" aria-controls="collapseContent">
                                                Zusätzliche Informationen
                                            </button>
                                        </h2>
                                        <div id="collapseContent" class="accordion-collapse collapse" aria-labelledby="faqContent">
                                            <div class="accordion-body entry-content px-0">
                                                <div class="row">
                                                    <div class="col-6 text-center text-lg-start col-lg-2 mx-auto">
                                                        <img style="filter: invert()" src="<?= get_stylesheet_directory_uri() ?>/img/icons/oerfaq-icon-hinweis.png">
                                                    </div>
                                                    <div class="col-12 col-lg-10">
                                                        <?php echo nl2br($faq->content) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faqLicense">
                                            <button class="accordion-button collapsed border-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLicense" aria-expanded="false" aria-controls="collapseLicense">
                                                Quellen und Lizenzen
                                            </button>
                                        </h2>
                                        <div id="collapseLicense" class="accordion-collapse collapse" aria-labelledby="faqLicense">
                                            <div class="accordion-body entry-license px-0">
                                                <div class="row">
                                                    <div class="col-6 text-center text-lg-start col-lg-2 mx-auto">
                                                        <img style="filter: invert()" src="<?= get_stylesheet_directory_uri() ?>/img/icons/oerfaq-icon-lizenz.png">
                                                    </div>
                                                    <div class="col-12 col-lg-10">
                                                        <?php the_faq_license($faq) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (function_exists('wthf_output')): ?>
                                        <div class="helpful">
                                            <?php echo wthf_output(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php get_template_part(
                        'template-parts/questions-list',
                        null, [
                            'title' => 'Verwandte Fragen',
                            'questions' => get_related_questions($faq),
                            'bg' => 'related-questions'
                        ]);
                    ?>
                    <?php get_template_part(
                        'template-parts/questions-list',
                        null, [
                            'title' => 'Ähnliche Fragen',
                            'questions' => get_similar_questions($faq),
                            'bg' => 'similar-questions',
                        ]);
                    ?>
                </main> <!-- #main -->

            </div><!-- col -->
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->

<?php get_footer(); ?>
