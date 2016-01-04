<?php get_header(); ?>
  <div id="page">
    <div id="primary" class="content-area">
      <main id="main" class="site-main" role="main">
        <?php if( have_posts() ) : ?>
          <div id="posts">
            <?php /* Start the Loop */ $r = 1; $c=0;
              while ( have_posts() ) : the_post();
                get_template_part( 'content', get_post_format() );
                $r = $r + 1;  
            ?>
              <?php if($r==4 && $c!=3){ ?>
               <div style="width: 100%; margin: 0px 0 30px 0; height: auto; float: left; border-bottom: 1px solid #ccc; padding-bottom: 30px;">  
            		<!-- /9262827/plumasatomicas_300x250_int -->
            		<div id='div-gpt-ad-1444667609777-<?php echo $c; ?>' style="width: 300px; margin: 0 auto;">
            			<script type='text/javascript'>
            				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1444667609777-<?php echo $c; ?>'); });
            			</script>
            		</div>
              </div>
            <?php $r = 0; $c++; } 
            endwhile; ?>
          </div>
          <?php  else : get_template_part( 'content', get_post_format() ); endif; ?>
      </main><!-- #main -->
    </div><!-- #primary -->
  </div><!-- #page -->
<?php get_footer(); ?>