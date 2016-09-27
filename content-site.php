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

// Breadcrumb
if ( is_singular() ) {
?>
  <div class="breadcrumb breadcrumbs woo-breadcrumbs">
    <div class="breadcrumb-trail">
      <a href="/research-arrays/" title="OOI Research Arrays" rel="home" class="trail-begin">Research Arrays</a>
      <span class="sep">›</span>
      <a href="<?=get_post_meta( get_the_ID(), 'array.permalink', true)?>"><?=get_post_meta( get_the_ID(), 'array.post_title', true)?></a>
      <span class="sep">›</span>
      <span class="trail-end"><?=get_post_meta( get_the_ID(), 'site_name', true)?></span>
    </div>
  </div>
<?php
}

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
    <?php //echo $title_before . get_post_meta( get_the_ID(), 'array.post_title', true) . " " . get_post_meta( get_the_ID(), 'site_name', true) . " <small>(" . get_the_title() . ")</small>" . $title_after; ?>
    <?php echo $title_before . get_post_meta( get_the_ID(), 'site_name', true) . " <small>(" . get_the_title() . ")</small>" . $title_after; ?>
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

    </div>
    <div class="fourcol-one last">
      <div>
        <p><strong>Site Diagram</strong><br>
        <?php echo ooi_image( get_post_meta( get_the_ID(), 'site_diagram.ID', true), 'medium');?></p>
      </div>
      
      <?php 
        $depth = get_post_meta( get_the_ID(), 'depth', true);
        if ($depth) { ?>
        <div><p><strong>Water Depth</strong><br>
          <?php echo number_format($depth); ?> meters</p>
        </div>
      <?php } ?>
      
      <?php 
        $latitude = get_post_meta( get_the_ID(), 'latitude', true);
        $longitude = get_post_meta( get_the_ID(), 'longitude', true);
        if (abs($latitude)) { ?>
        <div><p><strong>Site Location</strong><br>
          <?php
            echo sprintf("%s&deg;%s", abs($latitude), $latitude>0 ? 'N' : 'S');
            echo ", ";
            echo sprintf("%s&deg;%s", abs($longitude), $longitude>0 ? 'E' : 'W');
          ?> 
        </p></div>
      <?php } ?>

      <div>
        <?php 
        $themes = $pod->field('science_themes');
        if ( ! empty( $themes ) ) {
          echo "<strong>Research Themes</strong>";
          echo "<ul>";
          foreach ( $themes as $theme ) {
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url(get_permalink($theme['ID'])), $theme['post_title'] );
          }
          echo "</ul>";
        } ?>
      </div>

      <?php 
        $technical_resources = get_post_meta( get_the_ID(), 'technical_resources', true);
        if ($technical_resources) { ?>
        <div><p><strong>Technical Resources</strong><br>
          <?php echo $technical_resources; ?></p></div>
      <?php } ?>

    </div>
    <div class="clear"></div>


      <?php
        $params = array( 'orderby'=>'t.max_depth,t.instrument_name ASC', 'limit'=>-1, 'where'=>'site.post_title="'. $pod->display('name')  . '"'); 
        $instruments = pods('instrument', $params);
        if ( $instruments->total() > 0 ) {
      ?>
      <h3>Instruments</h3>
      <p>This site includes following instruments.  To learn more about an instrument type, select its name on the left. To see the relevant data streams for a particular instrument, select the icon on the right which will take you to the OOI Data Portal.</p>
      <table>
        <tr>
          <th>Instrument</th>
          <th>Design Depth</th>
          <th>Node</th>
          <th>Make &amp; Model</th>
          <th style="text-align:center;">Data<!-- Access Data --></th>
        </tr>
        <?php while ( $instruments->fetch() ) {  ?>
        <tr>
          <td>
            <?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instruments->display('instrument_series.ID'))), 
            $instruments->display('instrument_name') );?>
             <small>(<?php echo $instruments->display('instrument_series');?>)</small><br>
             <small><?php echo $instruments->display('name');?></small></td>
          <td>
            <?php 
              $mindepth = $instruments->display('min_depth');
              $maxdepth = $instruments->display('max_depth');
              if ($mindepth == $maxdepth) {
                echo $mindepth . 'm';
              } else {
                echo $mindepth . ' to ' . $maxdepth . ' meters';
              }
            ?></td>
          <td><?php echo $instruments->display('node_name') ;?></td>
          <td><?php echo $instruments->display('make') ;?> - <?php echo $instruments->display('model') ;?></td>
          <td style="text-align:center;">
            <?php
              $rd = $instruments->display('name');
              if (strpos($rd,'MOAS')) {
                echo '<a href="https://ooinet.oceanobservatories.org/data_access/?search=' . substr($rd,0,10) . '%20' . substr($rd,18,5) . '" target="_blank" title="Data Catalog"><i class="fa fa-database fa-lg"></i></a>';
              } else {
                echo '<a href="https://ooinet.oceanobservatories.org/data_access/?search=' . $rd . '" target="_blank" title="Data Catalog"><i class="fa fa-database fa-lg"></i></a>';
              }
            ?></td>
        </tr>
          <?php } // end while ?>
      </table>
        <?php } //endif empty ?>

      
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