<?php
/**
 * Array Content Template
 *
 */
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
  <?php woo_post_inside_before(); ?>
  <header>
    <?php the_title( $title_before, $title_after ); ?>
  </header>
  <?php //woo_post_meta(); ?>
	<section class="entry">
  <?php
    if ( ! is_singular () ) {
      // Listing
      the_excerpt();
    } else { 
      // Single Entry
      $pod = pods('array',get_the_ID());
    ?>
    <div class="fourcol-three">
      <?php the_content( __( 'Continue Reading &rarr;', 'woothemes' ) );    ?> 
      
      <h3>Photos</h3>
      <?php  
        $photos = get_post_meta( get_the_ID(), 'photos', false);

        if ( ! empty( $photos ) ) {
          foreach ( $photos as $photo ): ?>
          <div class="fivecol-one">
            <?php echo the_attachment_link($photo['ID'],false,'',true);?>
          </div>
          <?php endforeach; ?>
          <div class="clear"></div>
          <?php
        } else {
          echo 'No photos';    
        }
      ?>       
      <h3>Sites</h3>
      <p>This array includes following research sites and platforms.</p>
      <?php
        $params = array( 'orderby'=>'post_title ASC', 'limit'=>-1); 
        $sites = $pod->field('sites',$params);
        if ( ! empty( $sites ) ) {
        ?>
      <table>
        <tr><th>Site Name</th><th>Water Depth</th></tr>
          <?php foreach ( $sites as $site ): ?>
        <tr>
          <td><?php echo sprintf( '<a href="%s">%s</a>', esc_url(get_permalink($site['ID'])), get_post_meta($site['ID'],'site_name',true) );?>
          (<?php echo get_the_title($site['ID']);?>)</td>
          <td><?php 
            $depth = get_post_meta($site['ID'],'depth',true);
            if ($depth) echo $depth;?></td>
        </tr>
          <?php endforeach; ?>
      </table>
      <?php } //endif empty ?>        
    </div>
    <div class="fourcol-one last">
      <div><strong>Array Map</strong>
        <?php  echo sprintf( '<a href="%s">%s</a>', 
          get_post_meta( get_the_ID(), 'array_map._src.full', true), 
          get_post_meta( get_the_ID(), 'array_map._img.medium', true) );
        ?></div>
      <div><p><strong>Approximate Water Depth</strong><br>
        <?php echo get_post_meta( get_the_ID(), 'depth', true); ?></p></div>
      <div><p><strong>Central Mooring Location</strong><br>
        <?php echo get_post_meta( get_the_ID(), 'latitude', true); ?>, 
        <?php echo get_post_meta( get_the_ID(), 'longitude', true); ?></p></div>
      <div><p><strong>Research Setting</strong><br>
        <?php echo get_post_meta( get_the_ID(), 'setting', true); ?></p></div>
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
    </div>
    <div class="clear"></div>
    
    
    <pre>
    <div>Map: <?php print_r(get_post_meta( get_the_ID(), 'array_map', false)); ?></div>
    <div>Photos: <?php print_r(get_post_meta( get_the_ID(), 'photos', false)); ?></div>


      
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