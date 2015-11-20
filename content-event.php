<?php
/**
 * Event Template
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
	<?php the_title( $title_before, $title_after ); ?>
	</header>
<?php
//woo_post_meta();
?>
	<section class="entry">
<?php
if ( 'content' == $settings['post_content'] || is_single() ) { the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); } else { the_excerpt(); }
if ( 'content' == $settings['post_content'] || is_singular() ) wp_link_pages( $page_link_args );
?>


<?php
  $start_date = get_post_meta( get_the_ID(), 'start_date', true);
  $start_time = get_post_meta( get_the_ID(), 'start_time', true);
  $end_date = get_post_meta( get_the_ID(), 'end_date', true);
  $end_time = get_post_meta( get_the_ID(), 'end_time', true);
  $location = get_post_meta( get_the_ID(), 'location', true);
  $venue = get_post_meta( get_the_ID(), 'venue', true);
  
  if (empty($end_date) && empty($end_time)) {
    $datestr = $start_date . ' ' . $start_time;
  } elseif (empty($end_date)) {
    $datestr = $start_date . ' ' . $start_time . '-' . $end_time;
  } elseif (empty($end_time)) {
    $datestr = $start_date . ' ' . $start_time . ' to ' . $end_date;    
  } else {
    $datestr = $start_date . ' ' . $start_time . ' to ' . $end_date . ' ' . $end_time;
  }
  ?>

<div class="event-well">
  <h3>Event Details</h3>
  <dl>
    <dt>Date and Time</dt>
    <dd><?php echo $datestr?></dd>
  </dl>
  <?php if ($venue):?>
  <dl>
    <dt>Venue</dt>
    <dd><?php echo $venue?></dd>
  </dl>
  <?php endif; ?>
  <?php if ($location):?>
  <dl>
    <dt>Location</dt>
    <dd><?php echo $location?></dd>
  </dl>
  <?php endif; ?>
</div>


	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
//woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
//woo_post_after();
$comm = $settings['comments'];
if ( ( 'post' == $comm || 'both' == $comm ) && is_single() ) { comments_template(); }
?>