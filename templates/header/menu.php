<div class="<?php echo $menu_model -> wrapper_class ?>">
<?php
wp_nav_menu( array(
  'theme_location'  => $menu_model -> theme_location,
  'menu_class'      => $menu_model -> menu_class,
  'fallback_cb'     => $menu_model -> fallback_cb,
  'waker'           => $menu_model -> walker
) );
?>
</div>
