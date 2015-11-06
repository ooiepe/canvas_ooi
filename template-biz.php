<?php
/**
 * Template Name: Business
 *
 * The business page template displays your posts with a "business"-style
 * content slider at the top.
 *
 * @package WooFramework
 * @subpackage Template
 */

global $woo_options, $wp_query;
get_header();

$page_template = woo_get_page_template();
?>
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
	<?php if ( ( isset( $woo_options['woo_slider_biz'] ) && 'true' == $woo_options['woo_slider_biz'] ) && ( isset( $woo_options['woo_slider_biz_full'] ) && 'true' == $woo_options['woo_slider_biz_full'] ) ) { $saved = $wp_query; woo_slider_biz(); $wp_query = $saved; } ?>
    <div id="content" class="col-full business">

    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>

	<?php if ( ( isset( $woo_options['woo_slider_biz'] ) && 'true' == $woo_options['woo_slider_biz'] ) && ( isset( $woo_options['woo_slider_biz_full'] ) && 'false' == $woo_options['woo_slider_biz_full'] ) ) { $saved = $wp_query; woo_slider_biz(); $wp_query = $saved; } ?>

            <section id="main">
<?php
	woo_loop_before();

	if ( have_posts() ) { $count = 0;
		while ( have_posts() ) { the_post(); $count++;
			woo_get_template_part( 'content', 'page-template-business' ); // Get the page content template file, contextually.
		}
	}

	woo_loop_after();
?>

<article class="page">
	<section class="entry">

<div class="fourcol-three">
  <h3>OOI Updates</h3>
  <?php echo do_shortcode('[catlist name=news thumbnail=yes thumbnail_class=lcp_thumbnail thumbnail_size=250,250 numberposts=3 date=yes excerpt=yes template=homepage]');?>
</div>
<div class="fourcol-one last">
  <div style="background-color: #ffeebf; padding: .5em; border-radius: 6px; box-shadow: 0px 1px 5px rgba(0,0,0,.1);">
  <h4>Information for Mariners</h4>
  <p>Learn more about the location of OOI system assets, including moorings and undersea nodes, and other important notices for mariners. Please see <a href="/notice-to-mariners/">Information for Mariners</a></p>
  
  <h4>Upcoming Events</h4>
  <p><strong>2015 AGU Fall Meeting</strong><br>
  Dec 14-18, San Francisco, CA</p>
  
  <p><strong>2016 Ocean Sciences Meeting</strong><br>
  February 21-26, New Orleans, LA</p>
  
  <p align="right"><a href="/events/">More Events</a></p>
  </div>
</div>

<div class="clear"></div>

<div style="background-color: #cfe1e5; padding: 1em 2em 0em 2em; border-radius: 12px; box-shadow: 0px 1px 5px rgba(0,0,0,.1);">
  <div class="threecol-one">
    <a href="/data-portal/"><img class="alignnone size-full wp-image-9079" src="http://ooi.marine.rutgers.edu/wp-content/uploads/2010/05/homepage_data.jpg" alt="OOI Data Portal" width="280" height="200" /></a>
    <h3><a href="/data-portal/">Data Portal</a></h3>
    <p>Access data from the the OOI arrays.</p>
  </div>
  <div class="threecol-one">
    <a href="/educators/ocean-education-portal/"><img class="alignnone size-full wp-image-9084" src="http://ooi.marine.rutgers.edu/wp-content/uploads/2010/05/homepage_students.jpg" alt="OOI Ocean Education Portal" width="280" height="200" /></a>
    <h3><a href="/educators/ocean-education-portal/">Education Portal</a></h3>
    <p>Find and create educational visualizations, concept maps, and data investigations.</p>
  </div>
  <div class="threecol-one last">
    <a href="/researchers/"><img class="alignnone size-full wp-image-9081" src="http://ooi.marine.rutgers.edu/wp-content/uploads/2010/05/homepage_researchers.jpg" alt="Information for Researchers and Proposal Writers" width="280" height="200" /></a>
    <h3><a href="/researchers/">Information for Researchers</a></h3>
    <p>Guidance for proposal writers and data users.</p>
  </div>
  <div class="clear"></div>
</div>

	</section>
</article>


            </section><!-- /#main -->
            <?php woo_main_after(); ?>

			<?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>