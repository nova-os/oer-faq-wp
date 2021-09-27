<?php
	/**
	 * Template part for displaying results in search pages
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
	 *
	 * @package Bootscore
	 */

	$search_query = get_search_query();
	$parameter = !empty($search_query) ? '?s=' . $search_query : '';

	?>
    <li class="">
        <a href="<?php the_permalink(); ?><?php echo $parameter; ?>">
            <?php the_title('', ' <span style="font-size: 0.8em">' . get_post()->score . '</span>'); ?>
        </a>
    </li>
<!-- #post-<?php the_ID(); ?> -->
