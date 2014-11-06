<?php
/**
* Single post content actions
*
* 
* @package      Customizr
* @subpackage   classes
* @since        3.0.5
* @author       Nicolas GUILLAUME <nicolas@themesandco.com>
* @copyright    Copyright (c) 2013, Nicolas GUILLAUME
* @link         http://themesandco.com/customizr
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

class TC_post {

   //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;

    function __construct () {
        self::$instance =& $this;
        //add post header, content and footer to the __loop
        add_action  ( '__loop'                        , array( $this , 'tc_post_content' ));
        //posts parts actions
        add_action  ( '__after_content'               , array( $this , 'tc_post_footer' ));
    }


    /**
     * The default template for displaying single post content
     *
     * @package Customizr
     * @since Customizr 3.0
     */
    function tc_post_content() {
      //check conditional tags : we want to show single post or single custom post types
      global $post;
      $tc_show_single_post_content = isset($post) && 'page' != $post -> post_type && 'attachment' != $post -> post_type && is_singular() && !tc__f( '__is_home_empty');

      if ( !apply_filters( 'tc_show_single_post_content', $tc_show_single_post_content ) )
          return;

      //display an icon for div if there is no title
      $icon_class = in_array( get_post_format(), array(  'quote' , 'aside' , 'status' , 'link' ) ) ? apply_filters( 'tc_post_format_icon', 'format-icon' ) :'' ;

      ob_start();

      do_action( '__before_content' );

      ?>    

        <section class="entry-content <?php echo $icon_class ?>">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>' , 'customizr' ) ); ?>
            <?php wp_link_pages( array( 'before' => '<div class="pagination pagination-centered">' . __( 'Pages:' , 'customizr' ), 'after' => '</div>' ) ); ?>
        </section><!-- .entry-content -->

      <?php
      do_action( '__after_content' );
                  
      $html = ob_get_contents();
      ob_end_clean();

      echo apply_filters( 'tc_post_content', $html );
    }




    /**
     * The template part for displaying the single post footer
     *
     * @package Customizr
     * @since Customizr 3.0
     */
    function tc_post_footer() {
      //check conditional tags : we want to show single post or single custom post types
      global $post;
      $tc_show_single_post_footer =  'page' != $post -> post_type && 'attachment' != $post -> post_type && is_singular();
      
      if ( !apply_filters( 'tc_show_single_post_footer', $tc_show_single_post_footer ) )
          return;

      if ( !is_singular() || !get_the_author_meta( 'description' ) || !apply_filters( 'tc_show_author_metas_in_post', true ) )
        return;

      $html = sprintf('<footer class="entry-meta">%1$s<div class="author-info"><div class="%2$s">%3$s %4$s</div></div></footer>',
                   '<hr class="featurette-divider">',
                   
                  apply_filters( 'tc_author_meta_wrapper_class', 'row-fluid' ),
                   
                  sprintf('<div class="%1$s">%2$s</div>',
                          apply_filters( 'tc_author_meta_avatar_class', 'comment-avatar author-avatar span2'),
                          get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'tc_author_bio_avatar_size' , 100 ) )
                    ),

                  sprintf('<div class="%1$s"><h3>%2$s</h3><p>%3$s</p><div class="author-link">%4$s</div></div>',
                          apply_filters( 'tc_author_meta_content_class', 'author-description span10' ),
                          sprintf( __( 'About %s' , 'customizr' ), get_the_author() ),
                          get_the_author_meta( 'description' ),
                          sprintf( '<a href="%1$s" rel="author">%2$s</a>',
                            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                            sprintf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>' , 'customizr' ), get_the_author() )
                          )
                    )
      );//end sprintf

      echo apply_filters( 'tc_post_footer', $html );
    }

}//end of class