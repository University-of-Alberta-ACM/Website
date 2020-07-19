<!DOCTYPE html>
<html <?php language_attributes(); ?> class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths js-priorityNav wf-robotocondensed-n7-active wf-robotocondensed-n4-active wf-robotocondensed-n3-active wf-robotocondensed-i3-active wf-robotocondensed-i4-active wf-robotocondensed-i7-active wf-roboto-n4-active wf-roboto-n5-active wf-active">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
		<link rel="shortcut icon" href="/favicon.ico?v=2">
		<meta class="foundation-data-attribute-namespace">
		<meta class="foundation-mq-xxlarge">
		<meta class="foundation-mq-xlarge-only">
		<meta class="foundation-mq-xlarge">
		<meta class="foundation-mq-large-only">
		<meta class="foundation-mq-large">
		<meta class="foundation-mq-medium-only">
		<meta class="foundation-mq-medium">
		<meta class="foundation-mq-small-only">
		<meta class="foundation-mq-small">
		<meta class="foundation-mq-topbar">
		<title>Association for Computing Machinery</title>
		<?php wp_head(); ?>
	</head>

	<body class="posttype_<?php echo esc_html( get_post_type( get_the_ID() ) ); ?> <?php echo is_home()?'is_home':'not_home';?>">
		<header id="header" class="row">
			<nav class="top-bar eyebrow show-for-medium-up" data-topbar data-options="is_hover: false">
				<section class="top-bar-section">
					<div id="skiptocontent"><a href="#SkipTarget">skip to main content</a></div>
					<?php if ( has_nav_menu( 'topsmall' ) ) : ?>
					<?php
						wp_nav_menu( [
							'theme_location' => 'topsmall',
							'menu_class'     => 'right',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						] );
					?>
					<?php endif; ?>
				</section>
			</nav>
			<div class="clearfix utilities-area">
				<div class="logo-section">
				<div class="navbar-header show-for-large-up">
					<a class="navbar-brand" href="<?php echo esc_url( get_home_url() ); ?>">
						<?php
							$logo = get_theme_mod( 'logo_image' ) ?
								get_theme_mod( 'logo_image' ) :
								get_theme_root_uri() . '/acm/img/acm_logo.gif';
						?>
						<img alt="ACM Logo"
							height="78"
							class="logo"
							title="Home"
							src="<?php echo esc_url( $logo ); ?>"/>
					</a>
				</div>
				<div class="navbar-header hide-for-large-up">
					<a href="<?php echo esc_url( get_home_url() ); ?>">
						<img alt="ACM Logo" class="img-responsive hide-for-large-up"
							title="Home"
							src="<?php echo esc_url( get_theme_root_uri() ); ?>/acm/img/acm_logo_mobile.svg">
					</a>
				</div>
				</div>
				<div id="acm-description" class="column large-5 show-for-large-up">
				<div>
					<?php echo esc_html( get_bloginfo( 'description' ) ); ?>
					<!-- We're an international society of educators, scientists, technologists and engineers dedicated to the advancement of computer science. We offer a world-class <a href="#">Digital Library</a>, <a href="#">publications</a>, <a href="#">conferences</a>,
					and more. -->
				</div>
				</div>
				<div id="ctas-and-search" class="column large-5 medium-6 no-pad-left ctas-and-search">
				<?php
					if ( has_nav_menu( 'secondary' ) ) {
						wp_nav_menu([
							'theme_location' => 'secondary',
							'menu_class'     => 'block-grid right',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						]);
					}
				?>
				</div>
			</div>
			<nav class="top-bar main-nav" data-topbar data-options="is_hover: false">
				<ul class="title-area">
					<li class="toggle-topbar menu-icon">
						<a href="/#"><span></span></a>
					</li>
				</ul>
				<section class="top-bar-section">
					<div class="mobile-links">
						<div class="mobile-search">
							<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>">
								<i class="fa fa-search left"></i>
								<input class="text" type="text" value=" " name="s" id="s" />
								<input type="submit" class="submit button" name="submit" value="<?php esc_attr_e( 'Search' );?>" />
							</form>
						</div>
					</div>
					<?php
						wp_nav_menu([
							'theme_location' 	=> 'primary',
							'menu_class' 		=> 'nav-menu',
							'menu_id' 			=> 'primary-menu',
						]);
					?>
					<button class="nav__dropdown-toggle">MORE</button>
					<div class="more-list-box"></div>
				</section>
			</nav>
		</header>
		<main id="main" role="main">
