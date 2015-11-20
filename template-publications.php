<?php
/**
 * Template Name: OOI Publications
 *
 * The publications template displays a list of publications from Pods (Advanced Content Type)
 *
 */

get_header();
?>
       
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
    	<div id="main-sidebar-container">    

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">                       
<?php
	woo_loop_before();
	if (have_posts()) { $count = 0;
		while (have_posts()) { the_post(); $count++;
			woo_get_template_part( 'content', get_post_type() ); // Get the post content template file, contextually.
		}
	}
	woo_loop_after();
?>     

<div id="publication-list">
  <h3>Publication List</h3>
<?php 
  $params = array('limit' => 25, 'orderby'=>'t.year DESC, t.authors ASC');
  $pods = pods( 'publication', $params );
  if ( $pods->total() > 0 ) { 
    while ($pods->fetch() ) {
      $authors = $pods->display('authors');
      $title = $pods->display('title');
      $year = $pods->display('year');
      $source = $pods->display('source');
      $type = $pods->display('type');
      $abstract = $pods->display('abstract');
      $url = $pods->field('url');
      $pdf = $pods->field('pdf._src');
      $permalink = site_url( $pods->field('permalink') );
?>
      <p><?php echo $authors;?> (<?php echo $year;?>). <strong><?php echo $title;?></strong>. <?php echo $source;?>.
        <?php if ($url) { ?>
        <a href="<?php echo esc_url( $url); ?>" rel="bookmark"><i class="fa fa-external-link"> Web</i></a>
        <?php } ?> 
        <?php if ($pdf) { ?>
        <a href="<?php echo esc_url( $pdf); ?>" rel="bookmark"><i class="fa fa-file-pdf-o"> PDF</i></a>
        <?php } ?>
      </p>

<?php
    } //endwhile;
  } else {
    echo "No publications found.";
  } //endif;

  // Output Pagination
	echo $pods->pagination( array ('type'=>'advanced'));
?>
</div>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar('alt'); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>