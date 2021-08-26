<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<!-- вывод заголовка HTML генерируется в WordPress -->
	<title><?php wp_title('|', true, 'right'); ?></title>
 
	<!-- включает bootstrap и темы собственного стиля -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" media="screen" />
 
	<!-- включить jQuery и начальную загрузку JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 
	<!-- вызовите эту функцию, чтобы включить заголовки WordPress -->
	<?php wp_head(); ?>
</head>
<body>
 
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="well">
				<!-- output the site title -->
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
 
				<!-- вывод описания сайта -->
				<?php
				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; ?></p>
				<?php endif; ?>
			</div>
		</div>
		
	</div>
</div>