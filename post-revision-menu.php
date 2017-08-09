<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_menu', 'buffercode_post_revision_menu');

function buffercode_post_revision_menu() {

	add_options_page( 'Post Revision', 'Post Revision', 'manage_options', __FILE__, 'buffercode_post_revision_settings' );

	//call register settings function
	add_action( 'admin_init', 'buffercode_post_revision_register_settings' );
}

function buffercode_post_revision_register_settings() {
	//register both settings Text Field and Combo box
	register_setting( 'buffercode-post-revision-settings-group', 'buffercode_post_revision_custom_heading' );
	register_setting( 'buffercode-post-revision-settings-group', 'buffercode_post_revision_order' );
	register_setting( 'buffercode-post-revision-settings-group', 'buffercode_post_revision_bg_color' );
	register_setting( 'buffercode-post-revision-settings-group', 'buffercode_post_revision_font_color' );
}

function buffercode_post_revision_settings() {
$buffercode_post_revision_order = get_option('buffercode_post_revision_order');
?>
	<!-- Buffercode.com Post Revision Selection --> 
<div class="wrap">
<h2>Post Revision Setting Page</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'buffercode-post-revision-settings-group' ); ?>
    <?php do_settings_sections( 'buffercode-post-revision-settings-group' );?>
	<!-- Buffercode.com Post Revision Selection --> 
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Custom Revision Title</th>
        <td><input type="text" maxlength="25" name="buffercode_post_revision_custom_heading" value="<?php echo get_option('buffercode_post_revision_custom_heading'); ?>" /></td>
		</tr>
          <tr valign="top">
        <th scope="row">Display Order</th>
		 <td>
        <select name="buffercode_post_revision_order">
		<option value="ASC"<?php echo selected('ASC',$buffercode_post_revision_order); ?> >Ascending</option>
		<option value="DES"<?php echo selected('DES',$buffercode_post_revision_order); ?> >Descending</option>
		</select>
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row">Background Color</th>
        <td><input type="text" class="color {required:false,pickerClosable:true}"  name="buffercode_post_revision_bg_color"  value="<?php echo get_option('buffercode_post_revision_bg_color'); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Font Color</th>
        <td><input type="text" class="color {required:false,pickerClosable:true}"  name="buffercode_post_revision_font_color"  value="<?php echo get_option('buffercode_post_revision_font_color'); ?>" /></td>
        </tr>
		
		
		 <tr valign="top">
        <th scope="row">Designed by - <a href="http://buffercode.com">Buffercode</a></th>
        </tr>
    </table>
	<!-- Buffercode.com Post Revision Selection --> 
	
        <?php submit_button(); ?>

</form>
</div>
<?php } ?>