<?php
function admin_script_load_cg() {
	wp_enqueue_style( 'wp-color-picker' ); 
    wp_enqueue_style( 'cloud_gallery_admin_style', plugin_dir_url( __FILE__ ). 'css/admin.css' );
    wp_enqueue_script( 'cloud_gallery_admin_script', plugins_url( 'js/admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
}
add_action( 'admin_enqueue_scripts', 'admin_script_load_cg' );

add_action( 'init', 'codex_Gallery_init' );
function codex_Gallery_init() {
	$labels = array(
		'name'               => _x( 'Gallery', 'post type general name', 'cloudimagegallery' ),
		'singular_name'      => _x( 'Gallery', 'post type singular name', 'cloudimagegallery' ),
		'menu_name'          => _x( 'Cloud Gallery', 'admin menu', 'cloudimagegallery' ),
		'name_admin_bar'     => _x( 'New Cloud Image', 'add new on admin bar', 'cloudimagegallery' ),
		'add_new'            => _x( 'Add New Image', 'Gallery', 'cloudimagegallery' ),
		'add_new_item'       => __( 'Add New Image', 'cloudimagegallery' ),
		'new_item'           => __( 'New Gallery', 'cloudimagegallery' ),
		'edit_item'          => __( 'Edit Gallery', 'cloudimagegallery' ),
		'view_item'          => __( 'View Gallery', 'cloudimagegallery' ),
		'all_items'          => __( 'All Images', 'cloudimagegallery' ),
		'search_items'       => __( 'Search Gallery', 'cloudimagegallery' ),
		'parent_item_colon'  => __( 'Parent Gallery:', 'cloudimagegallery' ),
		'not_found'          => __( 'No Gallery found.', 'cloudimagegallery' ),
		'not_found_in_trash' => __( 'No Gallery found in Trash.', 'cloudimagegallery' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'Gallery' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-format-gallery',
		'supports'           => array( 'title','thumbnail' )
	);
	register_post_type( 'cloud_gallery', $args );
	register_taxonomy(
		'cloud_gallery_category',
		'cloud_gallery',
		array(
			'label' => __( 'Gallery Category' ),
			'rewrite' => array( 'slug' => 'cloud-gallery-category' ),
			'hierarchical' => true,
		)
	);
	wp_insert_term(
	  'gallery-default', // the term 
	  'cloud_gallery_category', // the taxonomy
	  array(
	    'description'=> 'Default Cloud Gallery Category',
	    'slug' => 'gallery-default',
	  )
	);
}
add_action('wp_head','customStyle_cg');
function customStyle_cg(){
	?>
	<style>
		<?php
		if(get_option('cg_image_shadow') =='false'){
			?>
			ul.photopile li a{
				-webkit-box-shadow: none;
				-moz-box-shadow: none;
				-o-box-shadow: none;
				-ms-box-shadow: none;
				box-shadow: none;
			}
			<?php
		}
		echo get_option('cg_custom_css');
		?>

	</style>
	<?php
}