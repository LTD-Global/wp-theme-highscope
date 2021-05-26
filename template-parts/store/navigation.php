<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage wireframe
 * @since 1.0
 * @version 1.2
 */

?>
<!-- USE ID & CLASSNAME OF "main-navigation" if you want to have the menu collapsible on smaller screens? -->
<nav class="menu collapsible menu-button" role="navigation" aria-label="<?php esc_attr_e( 'Store Navigation', 'highscope' ); ?>">

	<?php if(has_nav_menu('store-nav')):
		wp_nav_menu(
			array(
				'theme_location' => 'store-nav',
				'menu_id'        => 'store-menu',
				'menu_class'		 => 'horizontal light-background'
			)
		);
	endif; ?>

</nav>