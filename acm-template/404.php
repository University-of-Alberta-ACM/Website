<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage ACM
 * @since ACM 1.0
 */

get_header();
?>
<div class="row breadcrumb-container">
	<div class="columns small-12">
		<ul class="breadcrumbs">
			<?php ACMUtils::the_breadcrumb(); ?>
		</ul>
	</div>
</div>
<?php
	$banner = ACMUtils::get_banner_data( get_the_ID() );
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
			<p><?php echo esc_html( $banner['description'] );?></p>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<div id="maincontent" class="row">
	<article class="has-edit-button columns large-12 medium-12 small-12 zone-1"
		id="SkipTarget"
		tabindex="-1">
		<h1 class="page-title"><?php esc_html_e( 'Not Found - 404', 'ACM' ); ?></h1>
		<section>
			<p><?php esc_html_e( 'Use the search form below to find the page you are looking for.', 'ACM' ); ?></p>
			<?php get_search_form(); ?>
		</section>
	</article>
</div>
<?php get_footer(); ?>
