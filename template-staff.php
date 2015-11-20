<?php
/**
 * Template Name: OOI Staff Listing
 *
 * The Staff Listing template displays a list of all OOI staff members from Pods (Custom Post Type)
 *
 */

get_header();
?>
       
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
    	<div id="main-sidebar-container">    

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main" class="entry">                       
<?php
	woo_loop_before();
	if (have_posts()) { $count = 0;
		while (have_posts()) { the_post(); $count++;
			woo_get_template_part( 'content', get_post_type() ); // Get the post content template file, contextually.
		}
	}
	woo_loop_after();
?>     

<div id="publication-list">
  <h3>Staff List</h3>
<?php 
  $params = array('limit' => -1, 'orderby'=>'last_name.meta_value ASC, first_name.meta_value ASC');
  $pods = pods( 'staff', $params );
  if ( $pods->total() > 0 ) { ?>
  <table>
    <tr>
      <th>First Name</th><th>Last Name</th><th>Role</th><th>Group</th><th>Email</th><th>Phone</th>
    </tr>
  <?php
    while ($pods->fetch() ) {
      $first_name = $pods->display('first_name');
      $last_name = $pods->display('last_name');
      $role = $pods->display('role');
      $group = $pods->display('group');
      $institution = $pods->display('institution');
      $email = $pods->display('email');
      $phone = $pods->display('phone');
      $permalink = site_url( $pods->field('permalink') );
?>
    <tr>
      <td><a href="<?php echo esc_url( $permalink ); ?>" rel="bookmark"><?php echo $first_name;?></a></td>
      <td><a href="<?php echo esc_url( $permalink ); ?>" rel="bookmark"><?php echo $last_name;?></a></td>
      <td><?php echo $role;?></td>
      <td><?php echo $group;?></td>
      <td><?php echo $email;?></td>
      <td><?php echo $phone;?></td>
    </tr>
<?php
    } //endwhile;
?>
  </table>
<?php
  } else {
    echo "No staff members found.";
  } //endif;

  // Output Pagination
	echo $pods->pagination( array ('type'=>'advanced'));
?>
</div>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar('alt'); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>