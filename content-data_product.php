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
    <?php echo $title_before . get_post_meta( get_the_ID(), 'data_product_name', true) . $title_after; ?>
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
          $image = get_post_featured_image(get_the_ID(),'medium');
          echo sprintf( '<div style="width: 260px" class="wp-caption alignright"><a href="%s">%s</a><p class="wp-caption-text">%s</p></div>', 
            $image['attachment_link'],
            $image['img'],
            $image['caption']);
        }
      ?>
      <?php the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); ?>
      
      <div><p><strong>Typical Units</strong><br>
        <?php echo get_post_meta( get_the_ID(), 'typical_units', true); ?></p></div>
      <div><p><strong>Sampling Regime</strong><br>
        <?php echo get_post_meta( get_the_ID(), 'sampling_regime', true); ?></p></div>

      <h3>Instrument Classes</h3>
      <p>The following instrument classes have this data product available.</p>
      <?php
        $params = array( 'orderby'=>'post_title ASC', 'limit'=>-1); 
        $instruments = $pod->field('instrument_classes',$params);
        if ( ! empty( $instruments ) ) {
        ?>
      <table>
        <tr><th>Instrument Class</th><th></th></tr>
          <?php foreach ($instruments as $instrument) : ?>
        <tr>
          <td><?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instrument['ID'])), 
            get_post_meta($instrument['ID'],'instrument_name',true) );?>
          (<?php echo get_the_title($instrument['ID']);?>)</td>
          <td></td>
        </tr>
          <?php endforeach; ?>
      </table>
      <?php } //endif empty ?>        
    </div>
    <div class="fourcol-one last">
      <div>
        <h3>OOI Data Products</h3>
        <?php
          $params = array( 'orderby'=>'data_product_name ASC', 'limit'=>-1); 
          $products = pods('data_product',$params);
          if ( ! empty( $products ) ): ?>
          <ul>
            <?php while ( $products->fetch() ): ?>
            <li><?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($products->id())), 
            $products->display('data_product_name') );?></li>
          <?php endwhile; ?>
          </ul>
          <?php endif; ?>
      </div>
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