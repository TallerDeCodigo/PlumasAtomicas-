<?php get_header(); ?>

<?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>
<?php $read = get_post_meta( $post->ID, 'readmore', true ); ?>

    <div class="post-principal">    
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <!-- #post-<?php the_ID(); ?> -->
            <div class="single">
                <a href="<?php the_permalink(); ?>"><h2><b><?php the_title(); ?></b></h2></a>
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
                        }elseif($widget == 2 OR $widget == 4)
                            echo get_post_meta($post->ID, 'tpublicacion_valor', true); 
                        else if($widget == 3)
                            echo "<iframe width='600' height='315' src='//www.youtube.com/embed/" . get_post_meta($post->ID, 'tpublicacion_valor', true) . "' frameborder='0' allowfullscreen></iframe>"; 
                        else echo "";   
                    } else{
                    ?>
                    <a href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail() )  the_post_thumbnail('featured');  else echo '<img src="' . get_template_directory_uri() . '/images/imagen_destacada.png" />'; ?></a>
                    <?php } ?>      
                </div>
                <div class="post">
                    <?php if ( ! has_excerpt() ) echo ''; else the_excerpt(); ?>
                    <div class="more">
                        <?php if ($read == 1){ ?>
                            <a href="<?php the_permalink() ?>">Continuar leyendo...</a>
                        <?php } ?>
                    </div>    
                </div>
            </div> 
        </div> <!-- #post-<?php the_ID(); ?> #b-<?php  echo $b;   ?> -->
    </div>  
    <?php endwhile; endif; ?>

<?php get_footer(); ?>
