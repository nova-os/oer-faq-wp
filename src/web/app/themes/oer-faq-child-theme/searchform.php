<!-- Search Button Outline Secondary Right -->
<div class="container container-max-width">
    <form class="searchform mb-5 d-flex flex-row" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" autocomplete="off" class="form-inline">
        <div class="d-flex flex-fill flex-column">
            <input type="text" name="s" class="form-control bg-white shadow" placeholder="<?php _e('Search', 'bootscore'); ?>" data-ajax="<?php echo esc_attr(admin_url('admin-ajax.php')); ?>">
            <div class="position-relative">
                <div class="ajax-searchresults w-100 position-absolute"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary ms-3 shadow"><i class="fas fa-search"></i></button>
    </form>
</div>
