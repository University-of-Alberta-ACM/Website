<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage ACM
 * @since ACM 1.0
 */
	$social_networks = get_option( 'acm_social_networks' );
	if ( $social_networks === false ) {
		$social_networks = [
			'facebook' => 'https://www.facebook.com/AssociationForComputingMachinery/',
			'flickr' => 'https://www.flickr.com/photos/theofficialacm',
			'google_plus' => 'https://plus.google.com/101763122146287444610',
			'linkedin' => 'https://www.linkedin.com/company/association-for-computing-machinery',
			'email' => 'acmhelp@acm.org',
			'twitter' => 'https://twitter.com/theofficialacm',
			'instagram' => 'https://www.instagram.com/theofficialacm/',
			'youtube' => 'https://www.youtube.com/user/TheOfficialACM',
		];
	}
	$custom_logos = get_option( 'acm_custom_logos' );
	$id_logos = [];
	foreach ( $custom_logos as $name => $value ) {
		if ( $value ) {
			array_push( $id_logos, $value );
		}
	}
?>
	<div class="row">
		<footer>
			<?php
				if ( count( $id_logos ) > 0 ) :
			?>
			<div class="acm__custom-logos">
				<?php
					$total_items = count( $id_logos );
					$items_in_row = 0;
					$items = 0;
					// Loop through the rows of data.
					foreach ( $id_logos as $id ) :
						$items_in_row++;
						$items++;
						// Get logo url.
						$logo = wp_get_attachment_url( $id );
						// Open row div if it's the first element of the row.
						if ( 1 === $items_in_row ) :
				?>
				<div class="acm__custom-logos-row">
					<?php
						endif;
					?>
					<div class="acm__custom-logo"
						style="background-image: url('<?php echo esc_url( $logo ); ?>')">
					</div>
					<?php
						// Close row div and reset counter to 0.
						if ( 3 === $items_in_row || $items === $total_items ) :
							$items_in_row = 0;
					?>
				</div>
				<?php
						endif;
					endforeach;
				?>
			</div>
			<?php
				else :
			?>
			<nav>
				<div class="footer-nav">
				<?php get_sidebar( 'footer' ); ?>
				</div>
			</nav>
			<?php
				endif;
			?>
			<div class="logo_social_group">
				<hr />
				<?php if ( get_theme_mod( 'footer_logo' ) ) { ?>
				<img alt="ACM Logo"
					width="200"
					height="70"
					class="img-responsive" src="<?php echo esc_url( get_theme_mod( 'footer_logo' ) ); ?>">
				<?php } ?>
				<ul class="footer__social">
				<?php
					if ( $social_networks && count( $social_networks ) > 0 ) :
						// Loop through the rows of data.
						foreach ( $social_networks as $social_network => $url ) :
							// Display social networks.
							if  ( $url ) :
				?>
					<li>
						<a href="<?php echo ($social_network === 'email') ? "mailto:".$url : esc_url( $url ); ?>"
							<?php echo ($social_network !== 'email') ? "target='_blank'" : ''; ?>
							class="acm_social-network acm_social-network--<?php echo $social_network; ?>">
						<?php echo $social_network; ?>
						</a>
					</li>
				<?php
							endif;
						endforeach;
					endif;
				?>
				</ul>
			</div>
		</footer>
		<script>
			$(document).ready(function(){
				var wrapper = document.querySelector(".section-nav");
				var nav = priorityNav.init({
					mainNavWrapper: ".section-nav-wrapper",
					breakPoint: 0,
					navDropdownLabel: "MORE"
					});
			});
		</script>
	</div>
</main>
</body>
<?php wp_footer(); ?>
</html>
