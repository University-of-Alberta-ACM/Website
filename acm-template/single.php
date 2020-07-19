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
		<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
		?>
		<div class="row breadcrumb-container">
			<div class="columns small-12">
				<ul class="breadcrumbs">
					<?php ACMUtils::the_breadcrumb(); ?>
				</ul>
			</div>
		</div>
		<div class="row">
			<article class="columns large-9 medium-9 small-12 blocks has-edit-button"
				id="SkipTarget"
				tabindex="-1">
				<h1><?php the_title(); ?></h1>
				<?php the_post_thumbnail( 'post-thumbnail', [
					'class' => 'featuredimage',
				] ); ?>
				<section>
					<?php the_content(); ?>
				</section>
				<hr />
				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					if ( is_singular( 'attachment' ) ) {
						// Parent post navigation.
						the_post_navigation([
							'prev_text' => _x(
							'<span class="meta-nav">Published in</span><span class="post-title">%title</span>',
							'Parent post link',
							'acm'
							),
						]);
					} elseif ( is_singular( 'post' ) ) {
						// Previous/next post navigation.
						the_post_navigation([
							'next_text' => '<span class="meta-nav" aria-hidden="true">&rarr;</span> ' .
							__( 'Next post:', 'acm' ) . ' %title',
							'prev_text' => '<span class="meta-nav" aria-hidden="true">&larr;</span> ' .
							__( 'Previous post:', 'acm' ) . ' %title',
						]);
					}
					// End of the loop.
					endwhile;
				?>
			</article>
			<?php get_sidebar( 'content_right' ); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
