<?php
/**
 * Template for custom post type conferences
 *
 * @package WordPress
 * @subpackage ACM
 * @since ACM 1.0
 */
get_header();
// Get taxonomies per category.
$categories_args = [
	'type'		=> 'conferences',
	'orderby'	=> 'name',
	'order'		=> 'ASC',
	'taxonomy'	=> 'section',
];
$categories = get_categories( $categories_args );
?>

	<div class="row breadcrumb-container">
		<div class="columns small-12">
			<ul class="breadcrumbs">
				<?php ACMUtils::the_breadcrumb(); ?>
				<li>Conferences</li>
			</ul>
		</div>
	</div>

	<div class="article" id="maincontent">
		<article class="columns" id="SkipTarget">
			<div class="row">
				<div class="columns medium-12">
					<div class="articles">
					<?php
						if ( is_array( $categories ) ) :
							foreach ( $categories as $category ) :
					?>
						<div class="row">
								<div class="medium-12 columns">
									<h2><?php echo esc_html( $category->name ); ?></h2>
									<?php
										// Get customs post oper taxonomy category.
										$custom_posts = new WP_Query(
											[
												'posts_per_page' 	=> 100,
												'post_type' 		=> 'conferences',
												'tax_query' 		=> [
													'taxonomy' 	=> 'section',
													'field' 	=> 'term_id',
													'terms' 	=> $category->term_id,
												],
											]
										);
										if ( 0 !== $custom_posts->found_posts ) :
									?>
										<ul>
											<?php
												while ( $custom_posts->have_posts() ) :
													$custom_posts->the_post();
											?>
											<li>
												<a
													href="<?php the_permalink(); ?>">
													<?php the_title(); ?>
												</a>
											</li>
											<?php
												endwhile;
												wp_reset_postdata();
											?>
										</ul>
									<?php
										endif;
									?>
								</div>
						</div>
					<?php
							endforeach;
						endif;
					?>
					</div>
				</div>
			</div>
		</article>
	</div>
</div>

<?php get_footer(); ?>
