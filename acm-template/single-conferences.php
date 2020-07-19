<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage ACM
 * @since ACM 1.0
 */
get_header(); ?>
<div id="maincontent" class="article row" tabindex="-1">
	<div class="columns">

		<div class="row breadcrumb-container">
			<div class="columns small-12">
				<ul class="breadcrumbs">
					<?php ACMUtils::the_breadcrumb(); ?>
				</ul>
			</div>
		</div>
		<div class="row">
			<?php
				// Start the loop.
				while ( have_posts() ) : the_post();
			?>
			<article class="columns large-9 medium-9 small-12 blocks has-edit-button"
				id="SkipTarget">
				<h1><?php the_title(); ?></h1>
				<section>
					<?php
						the_content();
					?>
				</section>
			</article>
			<?php
				// End of the loop.
				endwhile;
			?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
