<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>


<style>

	#main-content {
		min-height: 100vh;
	}
	
	#main {
		margin-top:25vh;
	}

	.primary {
		border: #50668F;
	}
	.primary {
		color: #FFFFFF;
	}
	.primary, .primary:hover, .primary:focus {
		background-color: #50668F;
	}
	.primary {
		background-color: #0070d2;
		color: white;
		transition: all 0.1s;
		border: 1px solid transparent;
		text-decoration: none;
		border-radius: 4px;
		padding: 9px;
		margin: 10px;
	}
</style>


<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			
			
		<main id="main" class="site-main" role="main" >
			

			<a class="primary" href="<?php echo salesforce_oauth_url_admin(); ?>" title="Login Admin">Login as Administrator</a>
			<a class="primary" href="<?php echo salesforce_oauth_url_customer(); ?>" title="Login Customer">Login as Customer</a>


		</main><!-- #main -->
			
			

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php

get_footer();
