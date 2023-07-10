<?php

if ($bkb_single_tpl_layout == 2) {

    $bkb_content_class = "bkbcol-1-1";
} else {

    $bkb_single_tpl_layout = 1;
    $bkb_content_class = "bkbcol-8-12";
}

?>

<?php do_action('bkbm_before_single_content', $bkb_single_tpl_layout) ?>

<?php while (have_posts()) : the_post(); ?>

    <header class="bkbm-single-header">

        <?php echo do_shortcode('[bkbm_tpl_bc /]'); ?>

        <?php

        if (!post_password_required() && has_post_thumbnail()) :

        ?>

            <div class="bkbm-featured-img-container">

                <?php
                the_post_thumbnail();
                ?>

            </div> <!--  end .bkbm-featured-image-container  -->

        <?php endif; ?>


        <h1 class="bkbm-single-title"><?php the_title(); ?></h1>

        <?php if (comments_open()) : ?>
            <div class="comments-link">
                <?php comments_popup_link('<span class="leave-reply">' . __('Leave a reply', 'bkb_tpl') . '</span>', __('1 Reply', 'bkb_tpl'), __('% Replies', 'bkb_tpl')); ?>
            </div><!-- .comments-link -->

        <?php endif; // comments_open()   
        ?>
    </header><!-- .entry-header -->

    <div class="bkbm-entry-content">
        <?php the_content(__('Continue reading', 'bkb_tpl')); ?>
        <?php wp_link_pages(array('before' => '<div class="page-links">' . __('Pages:', 'bkb_tpl'), 'after' => '</div>')); ?>
    </div><!-- .entry-content -->

    <?php


    $bkb_hide_kb_nav_box = 0;

    if (isset($bkb_data['bkb_hide_kb_nav_box']) && $bkb_data['bkb_hide_kb_nav_box'] == 1) {

        $bkb_hide_kb_nav_box = 1;
    }

    //@Description: Hide Navigation Box.
    //@Since: Version 1.0.3

    if ($bkb_hide_kb_nav_box == 0) {

        $bkb_kb_nav_box_default_title = __('Knowledgebase Navigation:', 'bkb_tpl');

        $bkb_kb_nav_box_title = (isset($bkb_data['bkb_kb_nav_box_title']) && $bkb_data['bkb_kb_nav_box_title'] != "" && $bkb_kb_nav_box_default_title != $bkb_data['bkb_kb_nav_box_title']) ? $bkb_data['bkb_kb_nav_box_title'] : $bkb_kb_nav_box_default_title;

    ?>
        <div class="bkbm-grid bkbm-grid-pad nav-single bwl-row-cols-2">
            <div>
                <span class="nav-previous"><?php previous_post_link('%link', '<span class="meta-nav">' . _x('&larr;', 'Previous post link', 'bkb_tpl') . '</span> %title'); ?>&nbsp;</span>
            </div>
            <div class="text-right">
                <span class="nav-next">&nbsp;<?php next_post_link('%link', '%title <span class="meta-nav">' . _x('&rarr;', 'Next post link', 'bkb_tpl') . '</span>'); ?></span>
            </div>
        </div><!-- .nav-single -->

    <?php

    }

    // Load theme comment template.
    comments_template('', true); ?>

<?php endwhile; // end of the loop. 
?>

<?php do_action('bkbm_after_single_content'); ?>