<?php

/*
	Plugin Name: Cloud Image gallery
	Plugin URI: http://digitalbd.net/wp/plugin/cloudimagegallery
	Description: Cloud Image Gallery is a responsive image gallery.
	Version: 1.0.1
	Author: Md Rukon Shekh
	Author URI: https://www.odesk.com/o/profiles/users/_~01e2fb69b715750273/
	License: MIT or later
	Text Domain: cloudimagegallery
*/
	
function cloudimagegallery_files() {
  wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'jquery-ui-draggable');
	wp_enqueue_style( 'flat-ui', plugin_dir_url( __FILE__ ).'css/flat-ui.css');
	wp_enqueue_style( 'photopile', plugin_dir_url( __FILE__ ).'css/photopile.css');
	wp_enqueue_style( 'style_cimgl', plugin_dir_url( __FILE__ ).'css/style.css');
	wp_enqueue_script( 'jquery.ui.touch-punch', plugin_dir_url( __FILE__ ) . 'js/jquery.ui.touch-punch.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'photopilejs', plugin_dir_url( __FILE__ ) . 'js/photopile.js', array('jquery'), '', true );
	$cigalleryJSArry = array(
    'pluginUrl' => plugin_dir_url( __FILE__ ),
    'cg_numLayers' => get_option('cg_numLayers'),
    'cg_thumbOverlap' =>  get_option('cg_thumbOverlap'),
    'cg_thumbRotation' =>  get_option('cg_thumbRotation'),
    'cg_thumbBorderWidth' =>  get_option('cg_thumbBorderWidth'),
    'cg_thumbBorderColor' =>  get_option('cg_thumbBorderColor'),
    'cg_thumbBorderHoverColor' =>  get_option('cg_thumbBorderHoverColor'),
    'cg_fadeDuration' =>  get_option('cg_fadeDuration'),
    'cg_pickupDuration' =>  get_option('cg_pickupDuration'),
    'cg_photoZIndex' =>  get_option('cg_photoZIndex'),
    'cg_photoBorder' =>  get_option('cg_photoBorder'),
    'cg_photoBorderColor' =>  get_option('cg_photoBorderColor'),
    'cg_draggable' =>  get_option('cg_draggable'),
    'cg_showInfo' =>  get_option('cg_showInfo'),
    'cg_autoplayGallery' =>  get_option('cg_autoplayGallery'),
    'cg_autoplaySpeed' =>  get_option('cg_autoplaySpeed'),
    'cg_theme' =>  get_option("cg_theme")
	);
	wp_localize_script( 'photopilejs', 'cigalleryJS', $cigalleryJSArry );
}
add_action( 'wp_enqueue_scripts', 'cloudimagegallery_files' );
add_shortcode('cigallery',function($attr,$content){
	$atts = shortcode_atts( array(
    'title' => 'no foo',
		'category' => '',
	), $attr, 'cigallery' );
	$output .= '<div class="photopile-wrapper"><ul class="photopile">';
  if(isset($atts['category'])){
    $arg = array(
      'post_type' =>'cloud_gallery',
      'posts_per_page' =>-1,
      'cloud_gallery_category' =>$atts['category']
    );
  }else{
    $arg = array(
      'post_type' =>'cloud_gallery',
      'posts_per_page' =>-1
    );
  }
  
  $gallery_Query = new WP_Query($arg);
  if($gallery_Query->have_posts()){
    while ($gallery_Query->have_posts()) {
      $gallery_Query->the_post();
      $largeImage = wp_get_attachment_image_src( get_post_thumbnail_id( $gallery_Query->post->ID ), 'full', true );
      $randValue = rand(0,5);
      if(get_option('cg_random_size') == 'true'){
        $thumbImg = wp_get_attachment_image_src( get_post_thumbnail_id( $gallery_Query->post->ID ), 'medium', true );
        switch ($randValue) {
          case 0:
            $width = 150;
            $height = 'auto';
            break;
          case 1:
            $width = 200;
            $height = 'auto';
            break;
          case 2:
            $width = 250;
            $height = 'auto';
            break;
          case 3:
            $width = 300;
            $height = 'auto';
            break;
          case 4:
            $width = 350;
            $height = 'auto';
            break;
          case 5:
            $width = 375;
            $height = 'auto';
            break;
          default:
            $width = 250;
            $height = 'auto';
            break;
        }
        
      }else{
        $thumbImg = wp_get_attachment_image_src( get_post_thumbnail_id( $gallery_Query->post->ID ), 'thumbnail', true );
        $width = 150;
        $height = 150;
      }
      
      $output .= '<li><a href="'.$largeImage[0].'">';
      $output .= '<img src="'.$thumbImg[0].'" alt="'.get_the_title().'" width="'.$width.'" height="'.$height.'"  />';
      $output .= '</a></li>';
    }
  }
	$output .= '</ul></div>';
	$output .= '</ul></div>';
	return $output;
});
// plugin option page
// create custom plugin settings menu
add_action('admin_menu', 'cim_create_menu');

function cim_create_menu() {

	//create new top-level menu
  add_submenu_page( 'edit.php?post_type=cloud_gallery', __('Cloud Settings'),  __('Settings'),'administrator', 'cloud_gallery_settings', 'cim_settings_page');
	//call register settings function
	add_action( 'admin_init', 'register_settings' );
}


function register_settings() {
	//register our settings
	register_setting( 'cloud-image-gallery-settings', 'cg_border_color' );
	register_setting( 'cloud-image-gallery-settings', 'cg_numLayers' );
	register_setting( 'cloud-image-gallery-settings', 'cg_thumbOverlap' );
	register_setting( 'cloud-image-gallery-settings', 'cg_thumbRotation' );
  register_setting( 'cloud-image-gallery-settings', 'cg_thumbBorderWidth' );
	register_setting( 'cloud-image-gallery-settings', 'cg_draggable' );
	register_setting( 'cloud-image-gallery-settings', 'cg_thumbBorderColor' );
	register_setting( 'cloud-image-gallery-settings', 'cg_thumbBorderHoverColor' );
	register_setting( 'cloud-image-gallery-settings', 'cg_fadeDuration' );
	register_setting( 'cloud-image-gallery-settings', 'cg_pickupDuration' );
	register_setting( 'cloud-image-gallery-settings', 'cg_photoZIndex' );
	register_setting( 'cloud-image-gallery-settings', 'cg_photoBorder' );
	register_setting( 'cloud-image-gallery-settings', 'cg_photoBorderColor' );
	register_setting( 'cloud-image-gallery-settings', 'cg_showInfo' );
	register_setting( 'cloud-image-gallery-settings', 'cg_autoplayGallery' );
  register_setting( 'cloud-image-gallery-settings', 'cg_autoplaySpeed' );
  register_setting( 'cloud-image-gallery-settings', 'cg_random_size' );
  register_setting( 'cloud-image-gallery-settings', 'cg_image_shadow' );
  register_setting( 'cloud-image-gallery-settings', 'cg_theme' );
	register_setting( 'cloud-image-gallery-settings', 'cg_custom_css' );
}

function cim_settings_page() {
?>
<div class="wrap">
  <div class="postbox cg_wraper">
    <h3 class="hndle cg_heading">Cloud Image Gallery</h3>
    <form method="post" class="cg_form_box" action="options.php">
        <?php settings_fields( 'cloud-image-gallery-settings' ); ?>
        <table class="form-table">
            
            <tr valign="top">
              <th scope="row">Layers</th>
              <td><input type="text" name="cg_numLayers" value="<?php echo get_option('cg_numLayers','5'); ?>" /></td>
              <td>number of layers in the pile (max zindex)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Thumb Overlap</th>
              <td><input type="text" name="cg_thumbOverlap" value="<?php echo get_option('cg_thumbOverlap','50'); ?>" /></td>
              <td style="text-align:left;">Overlap amount (px)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Thumb Rotation</th>
              <td><input type="text" name="cg_thumbRotation" value="<?php echo get_option('cg_thumbRotation','45'); ?>" /></td>
              <td style="text-align:left;">Maximum rotation (deg)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Thumb Border Width</th>
              <td><input type="text" name="cg_thumbBorderWidth" value="<?php echo get_option('cg_thumbBorderWidth','2'); ?>" /></td>
              <td style="text-align:left;">Border width (px)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Thumb Border Color</th>
              <td><input type="text" class="showColorPicker" id="cg_thumbBorderColor" name="cg_thumbBorderColor" value="<?php echo get_option('cg_thumbBorderColor','white'); ?>" /></td>
              <td style="text-align:left;">Border color</td>
            </tr>
            <tr valign="top">
              <th scope="row">Thumb Border Hover Color</th>
              <td><input type="text" class="showColorPicker" name="cg_thumbBorderHoverColor" id="cg_thumbBorderHoverColor" value="<?php echo get_option('cg_thumbBorderHoverColor','#EAEAEA'); ?>" /></td>
              <td style="text-align:left;">Border hover color</td>
            </tr>
            <tr valign="top">
              <th scope="row">Draggable</th>
              <td>
                <label for="draggable_true">True <input type="radio" <?php if(get_option('cg_draggable') == 'true') echo 'checked="checked"'; ?> id="draggable_true" name="cg_draggable" value="true" /></label>
                <label for="draggable_false">False <input type="radio" <?php if(get_option('cg_draggable') == 'false') echo 'checked="checked"'; ?> id="draggable_false" name="cg_draggable" value="false" /></label>
              </td>
              <td style="text-align:left;">Enable draggable thumbnails</td>
            </tr>
            <tr valign="top">
              <th scope="row">Fade Duration</th>
              <td><input type="text" name="cg_fadeDuration" value="<?php echo get_option('cg_fadeDuration','200'); ?>" /></td>
              <td style="text-align:left;">Speed at which photo fades (ms)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Pickup Duration</th>
              <td><input type="text" name="cg_pickupDuration" value="<?php echo get_option('cg_pickupDuration',500); ?>" /></td>
              <td style="text-align:left;">speed at which photo is picked up & put down (ms)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Photo ZIndex</th>
              <td><input type="text" name="cg_photoZIndex" value="<?php echo get_option('cg_photoZIndex',100); ?>" /></td>
              <td style="text-align:left;">z-index (show above all).<br/> <strong>Note: Please don't change if you no need. default value 100</strong></td>
            </tr>
            <tr valign="top">
              <th scope="row">Photo Border</th>
              <td><input type="text" name="cg_photoBorder" value="<?php echo get_option('cg_photoBorder',10); ?>" /></td>
              <td style="text-align:left;">Border width around fullsize image</td>
            </tr>
            <tr valign="top">
              <th scope="row">Photo Border Color</th>
              <td><input type="text" class="showColorPicker" name="cg_photoBorderColor" id="cg_photoBorderColor" value="<?php echo get_option('cg_photoBorderColor','white'); ?>" /></td>
              <td style="text-align:left;">Border color</td>
            </tr>
            <tr valign="top">
              <th scope="row">Show Info</th>
              <td>
                <label for="showInfo_true">True <input type="radio" <?php if(get_option('cg_showInfo') == 'true') echo 'checked="checked"'; ?> id="showInfo_true" name="cg_showInfo" value="true" /></label>
                <label for="showInfo_false">False <input type="radio" <?php if(get_option('cg_showInfo') == 'false') echo 'checked="checked"'; ?> id="showInfo_false" name="cg_showInfo" value="false" /></label>
              </td>
              <td style="text-align:left;">Image Title Visible</td>
            </tr>
            <tr valign="top">
              <th scope="row">Autoplay Gallery</th>
              <td>
                <label for="autoplayGallery_true">True <input type="radio"<?php if(get_option('cg_autoplayGallery') == 'true') echo 'checked="checked"'; ?> id="autoplayGallery_true" name="cg_autoplayGallery" value="true" /></label>
                <label for="autoplayGallery_false">False <input type="radio" <?php if(get_option('cg_autoplayGallery') == 'false') echo 'checked="checked"'; ?> id="autoplayGallery_false" name="cg_autoplayGallery" value="false" /></label>
              </td>
              <td style="text-align:left;">Please select true if you need Autoplay Gallery</td>
            </tr>
            <tr valign="top">
              <th scope="row">Autoplay Speed</th>
              <td><input type="text" name="cg_autoplaySpeed" value="<?php echo get_option('cg_autoplaySpeed','5000'); ?>" /></td>
              <td style="text-align:left;">Autoplay speed (ms)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Random Size</th>
              <td>
                <label for="cg_random_size">True <input type="radio"<?php if(get_option('cg_random_size') == 'true') echo 'checked="checked"'; ?> id="cg_random_size" name="cg_random_size" value="true" /></label>
                <label for="cg_random_size_false">False <input type="radio" <?php if(get_option('cg_random_size') == 'false') echo 'checked="checked"'; ?> id="cg_random_size_false" name="cg_random_size" value="false" /></label>
              </td>
              <td style="text-align:left;">Autoplay speed (ms)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Image Shadow</th>
              <td>
                <label for="cg_image_shadow">True <input type="radio"<?php if(get_option('cg_image_shadow') == 'true') echo 'checked="checked"'; ?> id="cg_image_shadow" name="cg_image_shadow" value="true" /></label>
                <label for="cg_image_shadow_false">False <input type="radio" <?php if(get_option('cg_image_shadow') == 'false') echo 'checked="checked"'; ?> id="cg_image_shadow_false" name="cg_image_shadow" value="false" /></label>
              </td>
              <td style="text-align:left;">Autoplay speed (ms)</td>
            </tr>
            <tr valign="top">
              <th scope="row">Custom Css</th>
              <td>
                <textarea name="cg_custom_css" cols="30" rows="10" ><?php echo get_option('cg_custom_css'); ?></textarea>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row">Use Theme</th>
              <td>
                <select name="cg_theme" id="cg_theme">
                  <option  value="cg_notheme">None</option>
                  <option <?php if(get_option('cg_theme') == 'Turquoise') echo 'selected'; ?> value="Turquoise">Turquoise</option>
                  <option <?php if(get_option('cg_theme') == 'Concrete') echo 'selected'; ?> value="Concrete">Concrete</option>
                  <option <?php if(get_option('cg_theme') == 'Emerald') echo 'selected'; ?> value="Emerald">Emerald</option>
                  <option <?php if(get_option('cg_theme') == 'Nephritis') echo 'selected'; ?> value="Nephritis">Nephritis</option>
                  <option <?php if(get_option('cg_theme') == 'GreenSea') echo 'selected'; ?> value="GreenSea">Green Sea</option>
                  <option <?php if(get_option('cg_theme') == 'Peterriver') echo 'selected'; ?> value="Peterriver">Peter river</option>
                  <option <?php if(get_option('cg_theme') == 'Amethyst') echo 'selected'; ?> value="Amethyst">Amethyst</option>
                  <option <?php if(get_option('cg_theme') == 'Alizarin') echo 'selected'; ?> value="Alizarin">Alizarin</option>
                  <option <?php if(get_option('cg_theme') == 'wetasphalt') echo 'selected'; ?> value="wetasphalt">Wet asphalt</option>
                </select>
              </td>
              <td>Please Select 'None' if you want to use custom colors</td>
            </tr>
            <tr valign="top">
              <th scope="row"></th>
              <td>
                <p class="submit">
                  <input type="submit" class="button button-primary button-hero" value="<?php _e('Save') ?>" />
                </p>
              </td>
            </tr>
            
        </table>
    </form>
  </div>
</div>
<?php }
require_once( 'functions.php' );
//echo get_option( "border_color","eoro" );