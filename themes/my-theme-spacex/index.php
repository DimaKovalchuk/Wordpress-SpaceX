<!-- include our header -->
<?php get_header(); ?>

<div class="container">
	<div class="row">
		<?php
			$args = array( 'post_type' => 'spacex', 'posts_per_page' => 10 );
			$the_query = new WP_Query( $args );
			?>
			<?php if ( $the_query->have_posts() ) : ?>
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="entry-content">
			<p class="author">
				<!-- показать автора -->
				Автор поста: <?php the_author(); ?> &bull;
				<!-- показать дату опубликованной статьи -->
				Опубликовано: <?php echo get_the_date(); ?> &bull;
				<!-- показать количество комментариев -->
				<?php comments_number(); ?>
				<!-- показывать метки, присвоенные статьи -->
				<?php the_tags(); echo get_post_meta($post->ID, 'meta1_field_1', true); ?>
			</p>
			<p><b>Дата:</b> <?php echo get_post_meta($post->ID, 'meta1_data', true); ?></p>
			<img src="<?php get_post_meta($post->ID, 'meta1_picture_url', true); ?>" >

			<div class="excerpt">
				<!-- вывести отрывок статьи -->
				<?php the_excerpt(); ?>
			</div>
			</div>
			
			<div class="text-right">
				<!-- показать ссылку "Читать далее" связанной со статьей -->
				<a class="more-link" href="<?php the_permalink(); ?>">Читать далее</a>
			</div>
			<hr>
			<?php wp_reset_postdata(); ?>
			<?php endwhile/*; elseif : */?>
			<p><?php /*_e( 'Записи не найдены.' ); */?></p>
			<?php endif; ?>
	
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="text-center">
				<!-- показать ссылки пагинации на предыдущую и следующую посты -->
				<?php previous_posts_link( '< Предыдущий пост' ); ?> &bull;
				<?php next_posts_link( 'Следующий пост >' ); ?>
			</div>
		</div>
	</div>
</div>



	
<!-- подключить футер -->
<?php get_footer(); ?>
