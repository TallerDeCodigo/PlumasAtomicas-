<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php $read = get_post_meta( $post->ID, 'readmore', true ); ?>
    <div class="post-principal">    
        <div class="single">
	<?php  if(get_post_meta($post->ID, 'hashtag', true) != '') {?>	
	    <div class="hashtag"><span id="t_hashtag"><?php echo get_post_meta($post->ID, 'hashtag', true); ?></span></div>	
	<?php } ?>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	    <div class="kicker"><?php echo get_post_meta($post->ID, 'kicker', true); ?></div>	
            <div class="date"><?php the_time('l d.M.y') ?></div>
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
                        }

                        elseif($widget == 2 OR $widget == 4 OR $widget == 7)
                            echo get_post_meta($post->ID, 'tpublicacion_valor', true); 
                        else if($widget == 3)
                            echo "<iframe width='640' height='360' src='//www.youtube.com/embed/" . get_post_meta($post->ID, 'tpublicacion_valor', true) . "' frameborder='0' allowfullscreen></iframe>"; 
                        else echo "";   
                    } else{
        ?>
        <a href="<?php the_permalink() ?>">
        <?php   
                        if ( has_post_thumbnail() )  the_post_thumbnail('featured');  else echo ''; 
                ?>
        </a>
        <?php } ?>      
            </div>
            <div class="post">
                <?php if ( ! has_excerpt() ) echo ''; else the_excerpt(); ?>
            </div>
	    <div class="more">
                <?php
                if ($read == 1){ ?>
                    <a href="<?php the_permalink() ?>">Continuar leyendo...</a>
                <?php } ?>
            </div> 	
        </div> 
    </div> 
</article><!-- #post-## -->