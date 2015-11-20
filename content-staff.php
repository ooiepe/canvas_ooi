<?php
/**
 * Staff Member Template
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
  $first_name = get_post_meta( get_the_ID(), 'first_name', true);
  $last_name = get_post_meta( get_the_ID(), 'last_name', true);
  $role = get_post_meta( get_the_ID(), 'role', true);
  $group = get_post_meta( get_the_ID(), 'group', true);
  $institution = get_post_meta( get_the_ID(), 'institution', true);
  $email = get_post_meta( get_the_ID(), 'email', true);
  $phone = get_post_meta( get_the_ID(), 'phone', true);
  $photo = get_post_meta( get_the_ID(), 'photo.ID', true);
?>
<?php if ($photo) { ?>
  <div class="alignright profile">
    <?php echo ooi_image( $photo, 'medium');?>
  </div>
<?php } ?>

<h3><?php echo $role?> <small><?php echo $group?></small></h3>
<p>
  <?php if ($institution): ?>
    <strong>Institution</strong>: <?php echo $institution; ?><br>
  <?php endif; ?>
  <?php if ($phone): ?>
    <strong>Phone</strong>: <?php echo $phone; ?><br>
  <?php endif; ?>
  <?php if ($email): ?>
    <strong>Email</strong>: <?php echo $email; ?>
  <?php endif; ?>
</p>

<?php
if ( 'content' == $settings['post_content'] || is_single() ) { the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); } else { the_excerpt(); }
if ( 'content' == $settings['post_content'] || is_singular() ) wp_link_pages( $page_link_args );
?>

<a href="/staff">&lt;&lt; Return to Staff List</a>

	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
//woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
//woo_post_after();
?>