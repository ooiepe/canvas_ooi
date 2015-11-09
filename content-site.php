<?php
/**
 * Site Content Template
 *
 */
$settings = array(
				'thumb_w' => 100,
				'thumb_h' => 100,
				'thumb_align' => 'alignleft',
				'post_content' => 'excerpt',
				'comments' => 'both'
				);

$settings = woo_get_dynamic_values( $settings );

$title_before = '<h1 class="title entry-title">';
$title_after = '</h1>';

// Setup Header Styles
if ( ! is_single() ) {
  $title_before = '<h2 class="title entry-title">';
  $title_after = '</h2>';
  $title_before = $title_before . '<a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
  $title_after = '</a>' . $title_after;
}

$page_link_args = apply_filters( 'woothemes_pagelinks_args', array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) );

// Start Generating Content
woo_post_before();
?>
<article <?php post_class(); ?>>
  <?php woo_post_inside_before(); 
    if ( 'content' != $settings['post_content'] && ! is_singular() )
    	woo_image( 'width=' . esc_attr( $settings['thumb_w'] ) . '&height=' . esc_attr( $settings['thumb_h'] ) . '&class=thumbnail ' . esc_attr( $settings['thumb_align'] ) );    
  ?>
  <header>
    <?php //the_title( $title_before, $title_after ); ?>
    <?php echo $title_before . get_post_meta( get_the_ID(), 'array.post_title', true) . " " . get_post_meta( get_the_ID(), 'site_name', true) . " <small>(" . get_the_title() . ")</small>" . $title_after; ?>
  </header>
  <?php //woo_post_meta(); ?>
	<section class="entry">
  <?php
    if ( ! is_singular () ) {
      // Listing
      the_excerpt();
    } else { 
      // Single Entry
      $pod = pods('site',get_the_ID());
    ?>
    <div class="fourcol-three">
      <?php the_content( __( 'Continue Reading &rarr;', 'woothemes' ) );    ?> 
      
      <?php  
        $photos = $pod->field('photos');
        if (is_array($photos)) { ?>
      <h3>Deployment Photos</h3>
        <?php
        echo ooi_gallery($photos);
        };
      ?>

      <?php
        $params = array( 'orderby'=>'name ASC', 'limit'=>-1, 'where'=>'site.post_title="'. $pod->display('name')  . '"'); 
        $instruments = pods('instrument', $params);
        //if ( ! empty( $instruments ) ) {
        if ( $instruments->total() > 0 ) {
      ?>
      <h3>Instruments</h3>
      <p>This site includes following instruments.  To learn more about an instrument, select its name on the left; to access data for an instrument, select an icon on the right.</p>
      <table>
        <tr><th>Instrument</th><th>Design Depth</th><th>Location</th><th width="70px">Access Data</th></tr>
        <?php while ( $instruments->fetch() ) {  ?>
        <tr>
          <td><?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instruments->display('instrument_class.ID'))), 
            $instruments->display('instrument_class.instrument_name') );?>
             <small>(<?php echo $instruments->display('instrument_class');?>)</small></td>
          <td>
            <?php 
              $mindepth = $instruments->display('min_depth');
              $maxdepth = $instruments->display('max_depth');
              if ($mindepth == $maxdepth) {
                echo $mindepth . 'm';
              } else {
                echo $mindepth . ' to ' . $maxdepth . 'm';
              }
            ?></td>
          <td><?php echo $instruments->display('instrument_location') ;?></td>
          <td>
            <a href="https://ui.ooi.rutgers.edu/plotting/#<?php echo $instruments->display('name');?>" target="_blank" title="Plotting">
              <i class="fa fa-bar-chart fa-lg"></i></a>
            <a href="https://ui.ooi.rutgers.edu/streams/#<?php echo $instruments->display('name');?>" target="_blank" title="Data Catalog">
              <i class="fa fa-database fa-lg"></i></a>
            <a href="https://ui.ooi.rutgers.edu/assets/list/#<?php echo $instruments->display('name');?>" target="_blank" title="Asset Management">
              <i class="fa fa-sitemap fa-lg"></i></a>
            </td>
        </tr>
          <?php } // end while ?>
      </table>
        <?php } //endif empty ?>
    </div>
    <div class="fourcol-one last">
      <div><p><strong>Parent Array</strong><br>
        <?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($pod->display('array.ID'))), 
            $pod->display('array') );?></p>
      </div>
      <div>
        <p><strong>Site Diagram</strong>
        <?php echo ooi_image( get_post_meta( get_the_ID(), 'site_diagram.ID', true), 'medium');?></p>
      </div>
      <div><p><strong>Water Depth</strong><br>
        <?php echo $pod->display('depth'); ?></p></div>
      <div><p><strong>Site Location</strong><br>
        <?php echo $pod->display('latitude'); ?>, 
        <?php echo $pod->display('longitude'); ?></p></div>
      <div><strong>Research Themes</strong>
        <?php 
        $themes = $pod->field('science_themes');
        if ( ! empty( $themes ) ) {
          echo "<ul>";
          foreach ( $themes as $theme ) {
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url(get_permalink($theme['ID'])), $theme['post_title'] );
          }
          echo "</ul>";
        } else {
          echo "<p>No themes selected.</p>";
        } 
        ?>
      </div>
      <div><p><strong>Technical Resources</strong><br>
        <?php echo get_post_meta( get_the_ID(), 'technical_resources', true); ?></p></div>
    </div>
    <div class="clear"></div>
      
    <?php } //endif is_singular else
    //wp_link_pages( $page_link_args );      
  ?>
	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
//woo_post_inside_after();
?>
</article><!-- /.post -->

<?php
//woo_post_after();
?>