<?php get_header(); ?>
    
    <?php

    if ( have_posts() ) : while (have_posts()) : the_post();

    ?>
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
                <a href="<?php the_permalink() ?>">
                <?php   
                                if ( has_post_thumbnail() )  the_post_thumbnail('featured');  else echo '<img src="' . get_template_directory_uri() . '/images/imagen_destacada.png" />'; 
                        ?>
                </a>
                <?php } ?>      
                    </div>
                    <div class="post">
                            <?php if ( ! has_excerpt() ) echo ''; else the_excerpt(); ?>
                    <div class="more">
                        <?php
                        if ($read == 1){ ?>
                            <a href="<?php the_permalink() ?>">Leer más...</a>
                        <?php } ?>
                    </div>    

                    <div class="socialmedia">
                        <ul>
                            <li><a class="popupfb" onclick="ga('send', 'social', 'facebook', 'Share', 'http://deportes.sopitas.com/');" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="new"><div class="social fb"></div></a></li>
                            <li><a class="popuptw" onclick="ga('send', 'social', 'twitter', 'Share', 'http://deportes.sopitas.com/');" href="https://twitter.com/share?text=<?php the_title(); ?>&amp;url=<?php the_permalink(); ?>&amp;via=sopitasdeportes" target="new"><div class="social tw"></div></a></li>
                            <li><a class="popupgp" onclick="ga('send', 'social', 'google+', 'Share', 'http://deportes.sopitas.com/');" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="new"><div class="social gp"></div></a></li>
                            <li><a onclick="ga('send', 'social', 'pinterest', 'Share', 'http://deportes.sopitas.com/');" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php $thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?> <?php echo $thumbnail; ?>&amp;description=<?php the_title(); ?>" ><div class="social pin"></div></a></li>
                            <li><a onclick="ga('send', 'social', 'mail', 'Share', 'http://deportes.sopitas.com/');" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><div class="social em" style="margin-right: 0px"></div></a></li>
                        </ul>
                    </div> 
                    </div>
                </div> 
            </div> <!-- #post-<?php the_ID(); ?> #b-<?php  echo $b;   ?> -->
        </div>  
    <?php endwhile; endif; ?>
     
     
<?php get_footer(); ?>
