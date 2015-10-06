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
    <?php echo $title_before . get_post_meta( get_the_ID(), 'site_name', true) . $title_after; ?>
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
      
      <h3>Photos</h3>
      <?php  
        $photos = $pod->field('photos');

        if ( is_array( $photos ) ) {
          foreach ( $photos as $photo ) {
            $ids[]=$photo['ID'];
          }
          echo gallery_shortcode( array( 
            'include'=>implode(',',$ids),
            'columns'=>4
          ) );
        } else {
          echo 'No photos';    
        }
      ?>       
      <h3>Instruments</h3>
      <p>This site includes following instruments.</p>
      <?php
        $params = array( 'orderby'=>'name ASC', 'limit'=>-1, 'where'=>'site.post_title="'. $pod->display('name')  . '"'); 
        $instruments = pods('instrument', $params);
        if ( ! empty( $instruments ) ) {
      ?>
      <table>
        <tr><th>Ref Des</th><th>Class Code</th><th>Depth</th><th>Name</th><th>Location</th></tr>
        <?php while ( $instruments->fetch() ) {  ?>
        <tr>
          <td><?php echo $instruments->display('name');?></td>
          <td><?php echo $instruments->display('instrument_class');?></td>
          <td><?php echo $instruments->display('depth');?></td>
          <td><?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($instruments->display('instrument_class.ID'))), 
            $instruments->display('instrument_class.instrument_name') );?></td>
          <td><?php echo $instruments->display('instrument_location') ;?></td>
        </tr>
          <?php } // end while ?>
      </table>
        <?php } //endif empty ?>
    </div>
    <div class="fourcol-one last">
      <div><strong>Site Diagram</strong>
        <?php  echo sprintf( '<a href="%s">%s</a>', 
          get_post_meta( get_the_ID(), 'site_diagram._src.full', true), 
          get_post_meta( get_the_ID(), 'site_diagram._img.medium', true) );
        ?></div>
      <div><p><strong>Array</strong><br>
        <?php echo sprintf( '<a href="%s">%s</a>', 
            esc_url(get_permalink($pod->display('array.ID'))), 
            $pod->display('array') );?></p></div>
      <div><p><strong>Water Depth</strong><br>
        <?php echo $pod->display('depth'); ?></p></div>
      <div><p><strong>Site Location</strong><br>
        <?php echo $pod->display('latitude'); ?>, 
        <?php echo $pod->display('longitude'); ?></p></div>
      <div><strong>Research Themes</strong>
        <ul>
        <?php 
        $themes = $pod->field('science_themes');
        if ( ! empty( $themes ) ) {
          foreach ( $themes as $theme ) {
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url(get_permalink($theme['ID'])), $theme['post_title'] );
          }
        } 
        ?>
        </ul>
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