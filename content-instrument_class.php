<?php
/**
 * Instrument Class Content Template
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
      <span class="trail-end"><?=get_post_meta( get_the_ID(), 'display_name', true)?></span>
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
    <?php //echo $title_before . get_post_meta( get_the_ID(), 'display_name', true) . " <small>(" . get_the_title() . ")</small>" . $title_after; ?>
    <?php echo $title_before . get_post_meta( get_the_ID(), 'display_name', true) . $title_after; ?>
  </header>
  <?php //woo_post_meta(); ?>
	<section class="entry">
  <?php
    if ( ! is_singular () ) {
      // Listing
      the_excerpt();
    } else { 
      // Single Entry
      $pod = pods('instrument_class',get_the_ID());
    ?>
    <div class="threecol-two">
      <?php the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); ?>
      
      <h3>Data Products</h3>
      <?php
        $params = array( 'orderby'=>'post_title ASC', 'limit'=>-1); 
        $data_products = $pod->field('data_products',$params);
        if ( ! empty( $data_products ) ) {
        ?>
      <p>This instrument measures the following data products.  Select a data product's name to learn more.</p>
      <table>
        <tr>
          <th>Data Product</th>
          <th>Code</th>
          <th>DPS</th>
        </thead>
        <?php foreach ($data_products as $data_product) : ?>
        <tr>
          <td><?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($data_product['ID'])), 
            get_post_meta($data_product['ID'],'data_product_name',true) );?></td>
          <td><?php echo get_the_title($data_product['ID']);?></td>
          <td><?php 
            $dps = get_post_meta( $data_product['ID'], 'dps', true);
            if (is_array($dps)) {
              echo sprintf( 
                '<a href="%s">DPS <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>',
                esc_url(wp_get_attachment_url($dps['ID'])) );
            } ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
      <?php 
        } else {
          echo "<p>No data products</p>";
        } //endif empty ?>

      <?php if ($pod->field('ion_functions_link')) { ?>
      <p>The algorithm code used to generate data products for this instrument is also available in the ion-functions GitHub repository.</p>
      <a href="<?php echo $pod->display('ion_functions_link')?>" target="_blank" title="ion-functions GitHub repository" class="woo-sc-button" style="text-transform: none;"><i class="fa fa-code"></i> Algorithm Code</a>
      <?php } ?>

      <?php
        $params = array( 'orderby'=>'display_name ASC', 'limit'=>-1, 'where'=>'instrument_class.post_title="'. $pod->display('name')  . '"'); 
        $instrument_series = pods('instrument_series', $params);
        if ( $instrument_series->total() > 0 ) {
      ?>
      <h3>Instrument Models &amp; Deployed Locations</h3>
      <p>The OOI includes the following instrument makes and models for this instrument type.  Follow the links below to find out where in the OOI this instrument has been deployed.  You'll also find quick links for each instrument to Data portal, where you can plot and access data.</p>
      <table>
        <tr>
          <th>Series</th>
          <th>Make</th>
          <th>Model</th>
        </tr>
        <?php while ( $instrument_series->fetch() ) {  ?>
        <tr>
          <td>
            <?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instrument_series->display('ID'))), 
            $instrument_series->display('name') );?></td>
          <td><?php echo $instrument_series->display('make') ;?></td>
          <td><?php echo $instrument_series->display('model') ;?></td>
        </tr>
          <?php } // end while ?>
      </table>
        <?php } //endif empty ?>

      <?php 
        if ($pod->field('reference_information')) { 
          echo '<h3>Reference Information</h3>';
          echo $pod->display('reference_information');
        } ?>

    </div>

    <div class="threecol-one last">
      <?php 
        if ( has_post_thumbnail() ) {
          echo ooi_image_caption(get_post_thumbnail_id(get_the_ID()),'large');         
        }
      ?>
      <div>
        <p><strong>Primary Science Discipline</strong><br>
        <?php echo get_post_meta( get_the_ID(), 'primary_science_dicipline', true); ?></p>
      </div>
      <div>
        <strong>Research Themes</strong>
        <?php 
        $themes = $pod->field('science_themes');
        if ( ! empty( $themes ) ) {
          echo "<ul>";
          foreach ( $themes as $theme ) {
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url(get_permalink($theme['ID'])), $theme['post_title'] );
          }
          echo "</ul>";
        } else {
          echo "<p>None</p>";
        } 
        ?>
        <strong>Access Data</strong><br>
        <a href="https://ooinet.oceanobservatories.org/data_access/?search=<?= get_the_title()?>" target="_blank" title="Data Catalog" class="woo-sc-button" style="text-transform: none;"><i class="fa fa-database"></i> <?= get_the_title()?> on the Data Portal</a>

      </div>
<!--
      <p>
        <a href="/data-products" class="woo-sc-button custom">Data Product List &gt;&gt;</a>
        <a href="/instruments"  class="woo-sc-button custom">Instrument List &gt;&gt;</a>
      </p>
-->
      <?php  
        $photos = $pod->field('photos');
        if ( is_array( $photos ) ) {
          foreach ( $photos as $photo ) {
            echo ooi_image_caption($photo['ID'],'large');
          }        
        }
      ?>

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