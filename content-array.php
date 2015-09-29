<?php
/**
 * Array Content Template
 *
 */

$title_before = '<h1 class="title entry-title">';
$title_after = '</h1>';

if ( ! is_single() ) {
  $title_before = '<h2 class="title entry-title">';
  $title_after = '</h2>';
  $title_before = $title_before . '<a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
  $title_after = '</a>' . $title_after;
}

$page_link_args = apply_filters( 'woothemes_pagelinks_args', array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) );

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
        the_excerpt();
      } else {
        the_content( __( 'Continue Reading &rarr;', 'woothemes' ) );      
        $pod = pods('array',get_the_ID());
      ?>
      <div>Depth: <?php echo get_post_meta( get_the_ID(), 'depth', true); ?></div>
      <div>Latitude: <?php echo get_post_meta( get_the_ID(), 'latitude', true); ?></div>
      <div>Longitude: <?php echo get_post_meta( get_the_ID(), 'longitude', true); ?></div>
      <div>Setting: <?php echo get_post_meta( get_the_ID(), 'setting', true); ?></div>
      <div>Map: <?php echo get_post_meta( get_the_ID(), 'array_map', true); ?></div>
      <?php
        $params = array( 'orderby'=>'site_name ASC', 'limit'=>-1); 
        $sites = $pod->field('sites',$params);
        if ( ! empty( $sites ) ) {
        ?>
      <h3>Sites</h3>
      <table>
        <tr><th>Code</th><th>Name</th><th>Depth</th></tr>
          <?php foreach ( $sites as $site ): ?>
        <tr>
          <td><?php echo get_the_title($site['ID']);?></td>
          <td><?php echo sprintf( '<a href="%s">%s</a>', esc_url(get_permalink($site['ID'])), get_post_meta($site['ID'],'site_name',true) );?></td>
          <td><?php print_r( get_post_meta($site['ID'],'depth',true) );?></td>
        </tr>
          <?php endforeach; ?>
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