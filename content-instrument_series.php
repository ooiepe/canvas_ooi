<?php
/**
 * Instrument Series Content Template
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
      <a href="/instruments/" title="Instruments" rel="home" class="trail-begin">Instruments</a>
      <span class="sep">›</span>
      <a href="<?=get_post_meta( get_the_ID(), 'instrument_class.permalink', true)?>"><?=get_post_meta( get_the_ID(), 'instrument_class.display_name', true)?></a>
      <span class="sep">›</span>
      <span class="trail-end"><?= get_the_title()?></span>
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
    <?php echo $title_before . get_the_title() . " - " . get_post_meta( get_the_ID(), 'display_name', true) . $title_after; ?>
  </header>
  <?php //woo_post_meta(); ?>
	<section class="entry">
  <?php
    if ( ! is_singular () ) {
      // Listing
      the_excerpt();
    } else { 
      // Single Entry
      $pod = pods('instrument_series',get_the_ID());
    ?>
    <div class="threecol-two">
      <p>
        <strong>Make: </strong> <?php echo $pod->display('make')?><br>
        <strong>Model: </strong> <?php echo $pod->display('model')?>
      </p>
      <?php 
        $dps = get_post_meta( get_the_ID(), 'description', true);
        if ($dps) { ?>
        <div>
        <?php echo $dps; ?>
        </div>
      <?php } ?>
      
      <?php the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); ?>

    </div>


    <div class="threecol-one last">
      <?php 
        if ( has_post_thumbnail() ) {
          echo ooi_image_caption(get_post_thumbnail_id(get_the_ID()),'medium');         
        }
      ?>

<!--
      <p>
        <a href="/data-products" class="woo-sc-button custom">Data Product List &gt;&gt;</a>
        <a href="/instruments"  class="woo-sc-button custom">Instrument List &gt;&gt;</a>
      </p>
-->

    </div>
    <div class="clear"></div>
      
      <h3>Deployed Instruments</h3>
      <?php
        $params = array( 'orderby'=>'name ASC', 'limit'=>-1, 'where'=>'instrument_series.post_title="'. $pod->display('name')  . '"'); 
        $instruments = pods('instrument', $params);
        if ( $instruments->total() > 0 ) {
      ?>
      <p>The following instruments of this make/model are deployed throughout the OOI.  To learn more about an Array or Site, select its name. To see the relevant data streams for a particular instrument, select the instrument code in the first column which will take you to the OOI Data Portal.</p>
      <table>
        <tr>
          <th>Instrument</th>
          <th>Depth</th>
          <th>Array</th>
          <th>Site</th>
          <th>Node</th>
        </tr>
        <?php while ( $instruments->fetch() ) {  ?>
        <tr>
          <td><?php
              $rd = $instruments->display('name');
              if (strpos($rd,'MOAS')) {
                echo '<a href="https://ooinet.oceanobservatories.org/data_access/?search=' . substr($rd,0,10) . '%20' . substr($rd,18,5) . '" target="_blank" title="Data Catalog">' . $instruments->display('name') . '</a>'; //<i class="fa fa-database"></i>
              } else {
                echo '<a href="https://ooinet.oceanobservatories.org/data_access/?search=' . $rd . '" target="_blank" title="Data Catalog">' . $instruments->display('name') . '</a>';
              }
            ?></td>
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
          <td>
            <?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instruments->display('site.array.ID'))), 
            $instruments->display('site.array.post_title') );?></td>
          <td>
            <?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instruments->display('site.ID'))), 
            $instruments->display('site.site_name') );?>
             <small>(<?php echo $instruments->display('site.post_title');?>)</small></td>
          <td><?php echo $instruments->display('node_name') ;?></td>
        </tr>
          <?php } // end while ?>
      </table>
      <?php 
        } else {
          echo "<p>No instruments found.</p>";
        } //endif empty ?>        

      
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