<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage ACM
 * @since ACM 1.0
 */
get_header(); ?>
<?php if ( have_posts() ) : ?>
<div id="maincontent" class="row">
	<div class="columns small-12">
		<div class="row breadcrumb-container">
			<div class="columns small-12">
				<ul class="breadcrumbs">
					<?php ACMUtils::the_breadcrumb(); ?>
				</ul>
			</div>
		</div>
		<div class="row">
			<article class="has-edit-button columns large-12 medium-12 small-12 zone-1"
				id="SkipTarget"
				tabindex="-1">
				<section class="search-results">
					<h1 class="srSearchHint search-message">
						<?php
							/* translators: %s: Search title */
							printf( esc_attr__( 'You searched for: %s', 'acm' ), get_search_query() );
						?>
					</h1>
					<main class="search-main">
						<ol>
						<?php while ( have_posts() ) : the_post(); ?>
							<li>
								<h3 class="srTitle">
									<a href="<?php echo esc_url( get_permalink() ); ?>">
										<?php the_title(); ?>
									</a>
								</h3>
								<p class="rSummary">
									<?php
										$content = get_the_excerpt();
										$content = strip_tags( $content );
										echo substr( $content , 0, 200 ); // WPCS: XSS ok.
									?>
									<div class="h-readmore"> <a href="<?php esc_url( the_permalink() ); ?>">Read More</a></div>
								</p>
							</li>
						<?php endwhile; ?>
						</ol>
						<?php
							$big = 999999999; // need an unlikely integer.
						?>
						<?php
							echo paginate_links([ // WPCS: XSS ok.
								'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format' 	=> '?paged=%#%',
								'current' 	=> max( 1, get_query_var( 'paged' ) ),
								'total' 	=> $wp_query->max_num_pages,
							]);
						?>
					</main>
				</section>
			</article>
		</div>
	</div>
</div>
<?php endif; ?>
</div>
<?php get_footer(); ?>
