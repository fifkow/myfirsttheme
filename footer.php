<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

?>
            </div><!-- .nv-content-wrap -->
        </div><!-- .nv-container -->
    </div><!-- #content -->
    
    <?php
    /**
     * Hook: neve_lite_before_footer
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_footer' );
    ?>
    
    <footer id="colophon" class="nv-footer">
        <div class="nv-container">
            
            <?php
            /**
             * Hook: neve_lite_footer_widgets
             *
             * @since 1.0.0
             */
            do_action( 'neve_lite_footer_widgets' );
            
            // Footer Widgets
            neve_lite_footer_widgets();
            ?>
            
            <div class="nv-footer-bottom-wrap">
                <?php
                /**
                 * Hook: neve_lite_footer_bottom
                 *
                 * @since 1.0.0
                 */
                do_action( 'neve_lite_footer_bottom' );
                
                // Footer Navigation
                neve_lite_footer_navigation();
                
                // Copyright
                neve_lite_footer_copyright();
                ?>
            </div>
            
        </div><!-- .nv-container -->
    </footer><!-- #colophon -->
    
    <?php
    /**
     * Hook: neve_lite_after_footer
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_footer' );
    ?>
    
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
