<?php
/**
 * Template Name: Two columns
 *
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage ACM
 * @since ACM 1.0
 */
get_header();
?>

<?php
	$banner = ACMUtils::get_banner_data( $post->ID );
	if ( '' !== $banner['title'] ) :
?>
<div class="banner-container">
	<div class="acm-banner-container"
		style="background-image: url('<?php echo esc_url( $banner['image'] ); ?>');">
		<div class="gradient-wrapper"></div>
		<div class="overlay"></div>
		<div class="row">
			<div class="columns large-12 medium-12 banner-content">
				<p class="banner-heading">
					<small><?php echo esc_html( $banner['title'] );?></small>
					<?php echo esc_html( $banner['sub_title'] );?>
				</p>
				<p><?php echo esc_html( $banner['description'] ); ?></p>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
	if ( have_posts() ) :
		if ( is_home() && ! is_front_page() ) :
			?>
			<header>
			<h1 class="page-title screen-reader-text">
			<?php single_post_title(); ?>
			</h1>
			</header>
			<?php
		endif;
	endif;
?>
	<div id="maincontent" class="row">
		<div class="columns <?php echo '' !== $banner['title'] ? 'column--up' : ''; ?>">
			<div class="row breadcrumb-container">
				<div class="columns small-12">
					<ul class="breadcrumbs">
						<?php ACMUtils::the_breadcrumb(); ?>
					</ul>
				</div>
			</div>
			<div class="row">
				<article class="has-edit-button columns large-8 medium-8 small-12 zone-1"
					id="SkipTarget"
					tabindex="-1">
					<section>
					<?php
						if ( have_posts() ) :
							while ( have_posts() ) :
								the_post();
					?>
						<div class="post">
							<div class="entrytext">
								<?php the_content(); ?>
							</div>
						</div>
					<?php
							endwhile;
						endif;
					?>
					</section>
				</article>
				<?php get_sidebar( 'content' ); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
