<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="google-site-verification" content="3Qx6zC0nz1rTLEUbnuAXJeTwNRWjnXGPDmREA-cM3yQ" />	
    <meta http-equiv="Content-Language" content="es"/>
    <meta name="twitter:widgets:csp" content="on">
    <meta name="msvalidate.01" content="7096EBA1F4F35A7CD2212C1686066670" />
    <meta name="Description" content="Plumas Atómicas es un espacio colaborativo, abierto a la crítica y al análisis de la forma en que imaginamos el mundo.">
    <meta name="Keywords" content="Plumas atómicas, Política, Noticias, News, México, Internacional, Videos">
    <link rel="canonical" href="http://plumasatomicas.com/"/>
    <meta name="author" content="Plumas Atómicas">
    <meta name="robots" content="index,follow">
    <meta name="robots" content="noodp,noydir"> 
    <meta name="robots" content="all"/>
<?php

  if(is_single() || is_page()) {
	$twitter_url    = get_permalink();
	$twitter_title  = get_the_title();
	$twitter_desc   = get_the_excerpt();
  $twitter_thumbs = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), full );
  $twitter_thumb  = $twitter_thumbs[0];
  if(!$twitter_thumb) {
   	$twitter_thumb = 'http://www.gravatar.com/avatar/8eb9ee80d39f13cbbad56da88ef3a6ee?rating=PG&size=75';
  }
  $twitter_name   = str_replace('@', '', get_the_author_meta('twitter'));

?>
  <meta name="twitter:card" value="summary" />
  <meta name="twitter:url" value="<?php echo $twitter_url; ?>" />
  <meta name="twitter:title" value="<?php echo $twitter_title; ?>" />
  <meta name="twitter:description" value="<?php echo $twitter_desc; ?>" />
  <meta name="twitter:image" value="<?php echo $twitter_thumb; ?>" />
  <meta name="twitter:site" value="@plumasatomicas" />
<?php
	}
?>

<?php 
  if(is_home()){
?>
    <!-- Open Graph data -->
    <meta property="og:title" content="Plumas Atómicas" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="http://plumasatomicas.com/" />
    <meta property="og:image" content="<?php $thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID,  'featured_post') ); ?> <?php echo trim($thumbnail); ?>" />
    <meta property="og:description" content="Plumas Atómicas" />
<?php
  }
?>    
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title><?php if(is_home()) echo "Plumas Atómicas"; else wp_title(); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="shortcut icon" href="http://plumasatomicas.lisabn.com/wp-content/uploads/sites/7/2015/07/vox_76x76.png" type="image/x-icon">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,500' rel='stylesheet' type='text/css'/>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,700,300,100' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic' rel='stylesheet' type='text/css'>
    <script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.sidr.dark.css">     
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>?20150720" type="text/css" media="screen" />
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.sidr.min.js"></script>
    <?php wp_head(); ?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-65194329-1', 'auto');
      ga('send', 'pageview');
    </script>

    <script type='text/javascript'>
      var googletag = googletag || {};
      googletag.cmd = googletag.cmd || [];
      (function() {
        var gads = document.createElement('script');
        gads.async = true;
        gads.type = 'text/javascript';
        var useSSL = 'https:' == document.location.protocol;
        gads.src = (useSSL ? 'https:' : 'http:') +
          '//www.googletagservices.com/tag/js/gpt.js';
        var node = document.getElementsByTagName('script')[0];
        node.parentNode.insertBefore(gads, node);
      })();
    </script>

    <script type='text/javascript'>
      googletag.cmd.push(function() {
        googletag.defineSlot('/9262827/plumasatomicas_300x250_int2', [300, 250], 'div-gpt-ad-1444667609777-0').addService(googletag.pubads());
        googletag.defineSlot('/9262827/plumasatomicas_300x250_int', [[300, 100], [300, 250]], 'div-gpt-ad-1444667609777-1').addService(googletag.pubads());
        googletag.defineSlot('/9262827/plumas_atomicas_970x90', [970, 250], 'div-gpt-ad-1444667609777-2').addService(googletag.pubads());
        googletag.defineSlot('/9262827/plumasatomicas_728x90_sup', [970, 250], 'div-gpt-ad-1444667609777-3').addService(googletag.pubads());
        googletag.pubads().enableSingleRequest();
        googletag.enableServices();
      });
    </script>
    <script>
      jQuery(document).ready(function($) {
          jQuery('a.popuptw').on('click', function(){
              newwindow=window.open($(this).attr('href'),'','height=300,width=550');
              if (window.focus) {newwindow.focus()}
              return false;
          });
          
          
          jQuery('a.popupfb').on('click', function(){
              newwindow=window.open($(this).attr('href'),'','height=300,width=550');
              if (window.focus) {newwindow.focus()}
              return false;
          });
          
          jQuery('a.popupgp').on('click', function(){
              newwindow=window.open($(this).attr('href'),'','height=300,width=550');
              if (window.focus) {newwindow.focus()}
              return false;
          });
          
           jQuery('a.pinterest').on('click', function(){
              newwindow=window.open($(this).attr('href'),'','height=300,width=550');
              if (window.focus) {newwindow.focus()}
              return false;
          });
      });
</script>
<script>
  jQuery.noConflict(); // Reverts '$' variable back to other JavaScript libraries, avoiding namespace difficulties
    jQuery(document).ready(function($){
    jQuery("#search").mouseenter(function() {
      $("#form_busqueda").animate({"right":"-1px"}, {duration:750, queue:false});
      $("#search").fadeOut(0);
      $("#searcho").fadeIn(0);
    });
    
    jQuery("#busqueda").mouseleave(function() {
      if( $("#search-input").is(":focus") === false ){
        $('#search-input').trigger('blur');
      }
    });
    
    jQuery("#searcho").click( function () {
      if( $("#search-input").is(":focus") === true ){
        $('#search-input').trigger('blur');
      }else{
        $('#search-input').focus();
      }
    });
    
    jQuery('#search-input').blur( function () {
      $("#form_busqueda").animate({"right":"-210px"}, {duration:750, queue:false});
      $("#search").fadeIn(0);
      $("#searcho").fadeOut(0);
    });
  });
</script>

<script type="text/javascript">//<![CDATA[ 
    jQuery(window).load(function(){
      jQuery("[data-toggle]").click(function() {
        var toggle_el = jQuery(this).data("toggle");
        jQuery(toggle_el).toggleClass("open-sidebar");
      });
    });//]]> 

    jQuery('.has-sub').click( function(e) {
    e.preventDefault();
    jQuery(this).parent().toggleClass('tap');
}); 
</script>

<script>
jQuery( document ).ready( function( $ ) {
	$( document.body ).on( 'post-load', function () {
		(function(d, s, id) {
  		var js, fjs = d.getElementsByTagName(s)[0];
  		if (d.getElementById(id)) return;
  			js = d.createElement(s); js.id = id;
  			js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.4&appId=822037731163755";
  			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	});
});
</script>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
  n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
  document,'script','//connect.facebook.net/en_US/fbevents.js');

  fbq('init', '1641663079439802');
  fbq('track', "PageView");
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1641663079439802&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</head>
<body <?php body_class() ?>>
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.4&appId=822037731163755";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <header id="header">
        
         <section class="header-content">
            <a id="simple-menu" href="#sidr"><img alt="menu" src="http://plumasatomicas.lisabn.com/wp-content/uploads/sites/7/2015/07/ico_menu.png" id="menu"/></a>
            <div class="area-header">
              
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="logo" title="Plumas Atómicas" alt="Plumas Atómicas" src="http://plumasatomicas.lisabn.com/wp-content/uploads/sites/7/2015/08/plumas_atomicas.png" /></a>
              
            </div>

            <nav>
                <ul>
                    <div id="btn_redes" style="float:right">
                      <div id="busqueda">
                        <span id="search">search</span>
                        <span id="searcho">searcho</span>
                        <div id="bus" >
                          <form id="form_busqueda"  method="get" action="http://plumasatomicas.com/">
                            <input type="text" id="search-input" name="s" value="Buscar" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" placeholder="">
                            <input type="submit" value="Buscar">
                            </form><!-- form_busqueda -->
                          </div><!-- end busqueda-->
                        </div>
                        <a href="https://www.facebook.com/Plumasatomicas" target="new"><img alt="facebook" src="<?php echo get_template_directory_uri(); ?>/images/btnfb.png" /></a>
                        <a href="http://twitter.com/plumasatomicas" target="new"><img alt="twitter" src="<?php echo get_template_directory_uri(); ?>/images/btntw.png" /></a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>/feed/" target="new"><img alt="rss" src="<?php echo get_template_directory_uri(); ?>/images/btnrss.png" /></a>
                    </div>
                </ul>
           
            </nav> 
         </section>
    </header> 
  <div id="sidr">
      <div style="margin: 30px auto; text-align: center;"></div>
      <ul>
    	  <li style="border-bottom: 1px solid #404040;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>/category/mexico">México</a></li>
        <li style="border-bottom: 1px solid #404040;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>/category/internacional/">Internacional</a></li>
        <li style="border-bottom: 1px solid #404040;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>/category/opinion/">Opinión</a></li>
        <li style="border-bottom: 1px solid #404040;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>/category/explicandolanoticia">Explicando la noticia</a></li> 
        <li style="border-bottom: 1px solid #404040;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>/category/agenda-cultura/">Cultura</a></li>
    	  <li style="border-bottom: 1px solid #404040;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>/category/video/">Video</a></li>
      </ul>
    </div>  
    <script>
      $(document).ready(function() {
        $('#simple-menu').sidr();
     });
    </script>
 
    <?php 
    if(is_home()){
      $noticias = new WP_Query(array(
        'posts_per_page' => 1,
        'meta_key' => 'p_destacada',
        'meta_value' => '1'));
        if ( $noticias->have_posts() ) : while ( $noticias->have_posts() ) : $noticias->the_post();
        $thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID,  'featured_post') ); 
    ?>
    <div style="background: url('<?php echo trim($thumbnail); ?>') no-repeat center center fixed #000; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
          height: 800px; width: 100%;">
          <div class="titulo-destacado">
  		<div class="hashtag"><span id="t_hashtag"><?php echo get_post_meta($post->ID, 'hashtag', true); ?></span></div>	
  		<a href="<?php the_permalink(); ?>"><h2><b style="background: #FCF90B; clear: both; padding: 2px 2px;"><?php the_title(); ?></b></h2></a>
  		<div class="kicker" style="background:#fff; width:250px;"><?php echo get_post_meta($post->ID, 'kicker', true); ?></div>
    		<div class="socialmedia">
          <ul>
              <li><a class="popupfb" onclick="ga('send', 'social', 'facebook', 'Share', 'http://plumasatomicas.com/');" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="new"><div class="social fb" style="width: 50px;"></div></a></li>
              <li><a class="popuptw" onclick="ga('send', 'social', 'twitter', 'Share', 'http://plumasatomicas.com/');" href="https://twitter.com/share?text=<?php the_title(); ?>&amp;url=<?php the_permalink(); ?>&amp;via=plumasatomicas" target="new"><div class="social tw" style="width: 50px;"></div></a></li>
              <li><a class="popupgp" onclick="ga('send', 'social', 'google+', 'Share', 'http://plumasatomicas.com/');" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="new"><div class="social gp" style="width: 50px;"></div></a></li>
              <li><a onclick="ga('send', 'social', 'mail', 'Share', 'http://plumasatomicas.com/');" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><div class="social em" style="margin-right: 0px; width: 50px;"></div></a></li>
          </ul>
        </div>
      </div>
    </div>
    <?php 
      endwhile; endif; wp_reset_postdata();
    } 
    ?>
    <?php wp_reset_postdata(); ?>
    <!-- /9262827/plumas_atomicas_970x90 -->
    <div id='div-gpt-ad-1444667609777-2' class="banner728x90-head" style='height:250px; width:970px; <?php if(is_home()) { echo ' margin: 40px auto;'; }else{ echo ' margin: 140px auto;'; } ?>  margin-bottom: 0;'>
      <script type='text/javascript'>
        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1444667609777-2'); });
      </script>
    </div>
    <!-- end header -->
    <div id="wrapper"> 