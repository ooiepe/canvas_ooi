<?php
/**
 * Data Product Content Template
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
      <a href="/data-products/" title="Data Products" rel="home" class="trail-begin">Data Products</a>
      <span class="sep">›</span>
      <span class="trail-end"><?=get_post_meta( get_the_ID(), 'data_product_name', true)?></span>
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
    <?php echo $title_before . get_post_meta( get_the_ID(), 'data_product_name', true) . " <small>(" . get_the_title() . ")</small>" . $title_after; ?>
  </header>
  <?php //woo_post_meta(); ?>
	<section class="entry">
  <?php
    if ( ! is_singular () ) {
      // Listing
      the_excerpt();
    } else { 
      // Single Entry
      $pod = pods('data_product',get_the_ID());
    ?>
    <div class="fourcol-three">
      <?php 
        if ( has_post_thumbnail() ) {
          echo ooi_image_caption(get_post_thumbnail_id(get_the_ID()),'medium');         
        }
      ?>
      <?php the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); ?>
      
      <h3>Instrument Classes</h3>
      <p>The following instrument classes include this data product:</p>
      <?php
        $params = array( 'orderby'=>'post_title ASC', 'limit'=>-1); 
        $instruments = $pod->field('instrument_classes',$params);
        if ( ! empty( $instruments ) ) {
        ?>
      <ul>
          <?php foreach ($instruments as $instrument) : ?>
          <li><?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instrument['ID'])), 
            get_post_meta($instrument['ID'],'display_name',true) );?>
          <small>(<?php echo get_the_title($instrument['ID']);?>)</small></li>
          <?php endforeach; ?>
      </ul>
      <?php 
        } else {
          echo "<p>No instruments.</p>";
        } //endif empty ?>
    </div>
    <div class="fourcol-one last">

      <div>
        <p>
          <strong>Typical Units</strong><br>
          <?php echo get_post_meta( get_the_ID(), 'typical_units', true); ?>
        </p>
      </div>

      <div>
        <p>
          <strong>Sampling Regime</strong><br>
          <?php echo get_post_meta( get_the_ID(), 'sampling_regime', true); ?>
        </p>
      </div>
      
      <div>
        <p><strong>Data Product Specification</strong><br>
      <?php 
        $dps = $pod->field('dps');
        if (is_array($dps)) {
          echo sprintf( 
            '<a href="%s" class="woo-sc-button" style="text-transform: none;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> %s DPS</a>', 
            esc_url(wp_get_attachment_url($dps['ID'])), 
            get_the_title());
        } else {
          echo 'Not Available';
        }?>
        </p>
      </div>

      <div>
      <?php if ($pod->field('ion_functions_link')) { ?>
        <p><strong>Algorithm Link</strong><br>
        <a href="<?php echo $pod->display('ion_functions_link')?>" target="_blank" title="ion-functions GitHub repository" class="woo-sc-button" style="text-transform: none;"><i class="fa fa-code"></i> Algorithm Code</a></p>
      <?php } ?>
      </div>

<!--
      <p>
        <a href="/data-products" class="woo-sc-button custom">Data Product List &gt;&gt;</a>
        <a href="/instruments"  class="woo-sc-button custom">Instrument List &gt;&gt;</a>
      </p>
-->
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