<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); $p_Actual = $post->ID;  $link_post = get_permalink($post->ID); ?>
    <section class="content-post">
		<?php if( get_post_meta($post->ID, 'hashtag', true) != ''){ ?>
		<div class="hashtag"><span id="t_hashtag"><?php echo get_post_meta($post->ID, 'hashtag', true); ?></span></div>	
		<?php } ?>
		<h1><?php the_title(); ?></h1>
		<div class="kicker"><?php echo get_post_meta($post->ID, 'kicker', true); ?></div>     	    
            <section class="date"><?php the_time('l d.M.y') ?></section>
            <section class="image-destacada">
            <?php
                $class = '';
                $widget = get_post_meta( $post->ID, 'tpublicacion', true );
                if($class != 1)    
                    $class = ( $widget == 4) ? 'media vine':'twitter';    
            ?>    
           <div class="image-post <?php echo $class; ?>">            
                <?php 
                    if ($widget != '' && $widget != 1){
                       if($widget == 5){
                    ?>
                        <style>.embed-container {position: relative; padding-bottom: 120%; height: 0; overflow: hidden;} .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container'><iframe src='//instagram.com/p/<?php echo get_post_meta($post->ID, 'tpublicacion_valor', true); ?>/embed/' frameborder='0' scrolling='no' allowtransparency='true'></iframe></div>
                    <?php
                        }else if($widget == 6){
            
            echo "<iframe src='https://player.vimeo.com/video/" . get_post_meta($post->ID, 'tpublicacion_valor', true) . "' width='640' height='360' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";

}elseif($widget == 2 OR $widget == 4 OR $widget == 7)
                            echo get_post_meta($post->ID, 'tpublicacion_valor', true); 
                        else if($widget == 3)
                            echo "<iframe width='640' height='360' src='//www.youtube.com/embed/" . get_post_meta($post->ID, 'tpublicacion_valor', true) . "' frameborder='0' allowfullscreen></iframe>"; 
                        else echo "";   
                    } else
                        if ( has_post_thumbnail() )  the_post_thumbnail('featured');  else echo '<a href="<img src="' . get_template_directory_uri() . '/images/imagen_destacada.png" />'; 
                ?>    
            </div>
    </section>

    <!-- end image-destacada -->
    
    <section class="single-post" style="padding-bottom: 30px;">
        <section class="post">
            <?php the_content(); ?>
            <?php echo get_the_tag_list('<p class = "tags"> <a class = "title">ETIQUETAS</a> ',' ','</p>'); ?>
        </section>
 <div style="clear:both"></div>
	<?php $hashtag_s = get_post_meta($post->ID, 'hashtag', true); ?>
	<div class="hashtag" style="font-family:Roboto Condensed; font-weight: 900; font-size: 22px;">Seguimiento historia: <span><?php echo $hashtag_s; ?></span></div> 
         <?php 
     
        $the_query  = new WP_Query( array('posts_per_page' => 10, 'post_type' => 'post', 'meta_key' => 'hashtag', 'meta_value' => $hashtag_s,  'post__not_in' => array( $post->ID )) );
        ?>

        
        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
	$date_article = the_date('Y-m-d','', '', FALSE);
	$today 	      = date('Y-m-d'); 
	?>
	<p>	
		<a href="<?php the_permalink(); ?>" class="historias"><?php the_time(); ?> / <?php the_title(); ?></a>
	</p>
	<?php endwhile; 
	
	?>      
         <?php wp_reset_postdata(); ?>
 <div style="clear:both"></div>
     
        <div style="cleat:both"></div>    
           
        <div class="fb-separador"><span style="margin: 12px 50px; float: left; color: #fff; font-size: 17px;">Comentarios</span></div>
        <div class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="10" data-colorscheme="light"></div>        

        </section>
        <!-- end single -->
    <!-- deportes mobile -->
    <div style="width: 100%; margin: 30px 0 30px 0; height: auto; float: left; border-bottom: 1px solid #ccc; padding-bottom: 30px;">
    <!-- /9262827/plumasatomicas_300x250_int -->
<div id='div-gpt-ad-1444667609777-1' style="margin: 0 auto; width: 300px;">
<script type='text/javascript'>
googletag.cmd.push(function() { googletag.display('div-gpt-ad-1444667609777-1'); });
</script>
</div>
    </div>
    </section>
    <!-- end content -->
    <?php endwhile; else: ?>
    <?php endif; ?>

    <?php /* Start the Loop */
        $query = new WP_Query( array( 'post__not_in' => array( $post->ID ) ) );
    ?>
    
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <?php get_post_content(); ?>
    <?php endwhile; ?>




<?php get_footer(); ?>