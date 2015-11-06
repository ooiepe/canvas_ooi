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
    <?php echo $title_before . get_post_meta( get_the_ID(), 'instrument_name', true) . " <small>(" . get_the_title() . ")</small>" . $title_after; ?>
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
    <div class="fourcol-three">
      <?php the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); ?>
      
      <h3>Data Products</h3>
      <?php
        $params = array( 'orderby'=>'post_title ASC', 'limit'=>-1); 
        $data_products = $pod->field('data_products',$params);
        if ( ! empty( $data_products ) ) {
        ?>
      <p>This instrument records the following data products.  To learn more about a data product, select its name.</p>
      <ul>
        <?php foreach ($data_products as $data_product) : ?>
        <li><?php echo sprintf( '<a href="%s">%s</a>', 
          esc_url(get_permalink($data_product['ID'])), 
          get_post_meta($data_product['ID'],'data_product_name',true) );?>
        <small>(<?php echo get_the_title($data_product['ID']);?>)</small></li>
        <?php endforeach; ?>
      </ul>
      <?php 
        } else {
          echo "<p>No data products</p>";
        } //endif empty ?>

      <h3>OOI Sites</h3>
      <?php
        $params = array( 'orderby'=>'name ASC', 'limit'=>-1, 
          'groupby'=>'site.post_title',
          'select'=>'t.*,count(site.post_title) as instrument_count',
          'where'=>'instrument_class.post_title="'. $pod->display('name')  . '"'); 
        $instruments = pods('instrument', $params);
        if ( ! empty( $instruments ) ) {
      ?>
      <p>This instrument is used at the following sites.  Select a site to access data for this instrument at that site.</p>
      <table>
        <tr><th>Array and Site Name</th><th style="text-align:center;">Instrument Count</th></tr>
        <?php while ( $instruments->fetch() ) {  ?>
        <tr>
          <td><?php echo sprintf( '<a href="%s">%s - %s</a>', 
            esc_url(get_permalink($instruments->display('site.ID'))), 
            $instruments->display('site.array'),
            $instruments->display('site.site_name') );?>
             <small>(<?php echo $instruments->display('site');?>)</small></td>
          <td style="text-align:center;"><?php echo $instruments->display('instrument_count') ;?></td>
        </tr>
          <?php } // end while ?>
      </table>
      <?php 
        } else {
          echo "<p>No sites.</p>";
        } //endif empty ?>        
    </div>


    <div class="fourcol-one last">
      <?php 
        if ( has_post_thumbnail() ) {
          echo ooi_image_caption(get_post_thumbnail_id(get_the_ID()),'medium');         
        }
      ?>
      <div>
        <p><strong>Primary Science Dicipline</strong><br>
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
          echo "<p>No themes selected.</p>";
        } 
        ?>
      </div>
      <p>
        <a href="/data-products" class="woo-sc-button custom">Data Product List &gt;&gt;</a>
        <a href="/instruments"  class="woo-sc-button custom">Instrument List &gt;&gt;</a>
      </p>
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