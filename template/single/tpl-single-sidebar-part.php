<?php do_action('bkbm_before_sidebar_content', $bkb_single_tpl_layout); ?>
        
        <?php
        
        $bkb_single_tpl_sidebar = 'bkbm_template_widget';

        global $bkb_data;
        
        if (isset($bkb_data['bkb_single_tpl_sidebar']) && $bkb_data['bkb_single_tpl_sidebar'] != "") {

                $bkb_single_tpl_sidebar =  $bkb_data['bkb_single_tpl_sidebar'];
        }

        ?>
        
        <?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar( $bkb_single_tpl_sidebar )): ?>
 
        <p>

            <i class="fa fa-info-circle"></i> 
            <strong><a href="<?php echo esc_url(home_url('/')); ?>/wp-admin/widgets.php" target="_blank"><?php _e('Click here', 'bkb_tpl'); ?></a></strong> 
           <?php _e('to set sidebar widgets.', 'bkb_tpl')?>

        </p>

        <?php endif; ?>
        
<?php do_action('bkbm_after_sidebar_content', $bkb_single_tpl_layout); ?>