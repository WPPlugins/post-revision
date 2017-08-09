<?php
/*
Plugin Name: Post Revision
Plugin URI: http://buffercode.com/post-revision-wordpress-plugin/
Description: Through this plugin, user can able to show the post revision done on the each post with latest update reason.
Version: 1.2
Author: vinoth06
Author URI: http://buffercode.com/
License: GPLv2
*/

include('post-revision-menu.php');
register_activation_hook(__FILE__,'buffercode_post_revision_install');

function buffercode_post_revision_install(){
add_option('buffercode_post_revision_custom_heading','Revision List');
add_option('buffercode_post_revision_order',2);
add_option('buffercode_post_revision_bg_color','');
}

function buffercode_post_revision() {
wp_enqueue_script( 'captcha-script',plugins_url('js\jscolor.js',__FILE__) );
}

add_action( 'admin_init', 'buffercode_post_revision',1 );

function buffercode_post_revision_mode() {
# placing our meta box in three locations namely attachment, post and page.
    $buffercode_PR_location = array( 'attachment', 'post', 'page');

    foreach ( $buffercode_PR_location as $buffercode_PR_locations ) {
       add_meta_box(
            'buffercode_post_revision_mode_id',
            __( 'Reason for Last Update', 'buffercode_post_revision_domain' ),
            'buffercode_post_revision_mode_post',
            $buffercode_PR_locations
        );
    }
}
#registering our meta boxes in admin dash board.
add_action( 'add_meta_boxes', 'buffercode_post_revision_mode' );


function buffercode_post_revision_mode_post( $post ) {
  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'buffercode_post_revision_post', 'buffercode_post_revision_mode_nonce' );

  /*
   Get the value previous value from the database to display in the admin dashboard
   */
  $buffercode_post_revision_summary = get_post_meta( $post->ID, 'buffercode_post_revision_summary', true );

 ?>
	<!-- Buffercode.com Post Revision Selection --> 
 <textarea name="buffercode_post_revision_summary" cols="45" rows="4" ><?php echo $buffercode_post_revision_summary; ?></textarea>
	<!-- Buffercode.com Post Revision Selection --> 
  <?php
 
  }

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function buffercode_post_revision_save( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['buffercode_post_revision_mode_nonce'] ) )
    return $post_id;

  $nonce = $_POST['buffercode_post_revision_mode_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'buffercode_post_revision_post' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  
  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['buffercode_post_revision_summary'] );
  
  // Update the meta field in the database.
  update_post_meta( $post_id, 'buffercode_post_revision_summary', $mydata );
  
 }
add_action( 'save_post', 'buffercode_post_revision_save' );


function buffercode_post_revision_display_logic($content){
global $post;
 if(!is_attachment()){
$buffercode_post_revision_font_color=get_option('buffercode_post_revision_font_color');
$buffercode_post_revision_bg_color=get_option('buffercode_post_revision_bg_color');
$buffercode_post_revision_order=get_option('buffercode_post_revision_order');
$buffercode_post_revision_custom_heading=get_option('buffercode_post_revision_custom_heading');
$buffercode_post_revision_summary = get_post_meta( $post->ID, 'buffercode_post_revision_summary', true );
$buffercode_revision_no = wp_get_post_revisions($post->ID, array('order'=> $buffercode_post_revision_order));
   	if (count($buffercode_revision_no > 0)) {	$i=1; if(is_single()){
          $content.='<div style="background:#'.$buffercode_post_revision_bg_color.'; padding:5px; color:#'.$buffercode_post_revision_font_color.';"><b style="font-size:23px">	<!-- Buffercode.com Post Revision Selection --> '.$buffercode_post_revision_custom_heading.'</b><br><br>';
		  if(!empty($buffercode_post_revision_summary)){
		  $content.='<b>Reason for Last Update:</b><br><i>&nbsp; &nbsp;&nbsp; &nbsp;'.$buffercode_post_revision_summary.'</i><br><br>';
		  }

		   foreach ($buffercode_revision_no as $buffercode_revision_nos) { 
            $content.='<div id="post-'.$buffercode_revision_nos->ID.'">';
                $content.='<p style="line-height: 0.3">';
                        $content.='<small>#'.$i.'';
                        $content.=' on <abbr class="updated" title="'.mysql2date('Y-m-d', $buffercode_revision_nos->post_date).':'.mysql2date('m:s+Z', $buffercode_revision_nos->post_date).'">'.mysql2date('Y-M-d D', $buffercode_revision_nos->post_date).' &nbsp;'.mysql2date('m:s+Z', $buffercode_revision_nos->post_date);
                        $content.='</abbr></small></p> </div>	<!-- Buffercode.com Post Revision Selection --> ';
                    
           
          $i++;} /* end foreach */ 
		 $content.='</div>';
      } /* end if any revisions */
	  return $content;
}
}
else  return $content;
}
add_filter('the_content','buffercode_post_revision_display_logic');

?>