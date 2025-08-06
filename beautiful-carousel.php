<?php
/*
Plugin Name: Beautiful Carousel
Description: Construisez votre propre carousel
Version: 1.0
Author: Tojo Mampionona
*/

/*Style and script*/
add_action('wp_enqueue_scripts', 'btfc_enqueue_assests');
function btfc_enqueue_assests () {
    wp_enqueue_style('btfc-style', plugin_dir_url(__FILE__) . 'index.css');
    wp_enqueue_script('btfc-script', plugin_dir_url(__FILE__) . 'index.js', array(), false, true);
}

/*Shortcode*/
add_shortcode('btfc', 'btfc_shortcode');
function btfc_shortcode ($atts) {
	$image_ids = get_option('btfc_image_ids', '');
	
	if (empty($image_ids)) {
        return '<p>Aucune image sélectionnée dans l\'administration du plugin.</p>';
    }
	
	// Convertit la chaîne d’IDs en tableau
    $ids = array_filter(array_map('trim', explode(',', $image_ids)));
	
    ob_start();
    ?>
        <div class="btfc-carousel">
            <button class="btfc-btn prev">&lt;</button>
            <div class="btfc-track-container">
                <ul class="btfc-track">
					<?php foreach ($ids as $index => $id) : ?>
						<li class="btfc-slide <?php echo $index === 0 ? 'current-slide' : ''; ?>">
							<?php echo wp_get_attachment_image($id, 'large'); ?>
						</li>
					<?php endforeach; ?>
<!--                     <li class="btfc-slide current-slide"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/image1.jpg" alt="slide 1"/></li>
                    <li class="btfc-slide"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/image2.jpeg" alt="slide 2"/></li>
                    <li class="btfc-slide"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/image3.jpeg" alt="slide 3"/></li> -->
                </ul>
            </div>
            <button class="btfc-btn next">&gt;</button>
        </div>
    <?php
    return ob_get_clean();
}

/*Admin Page*/
add_action('admin_menu','btfc_add_admin_menu');
function btfc_add_admin_menu () {
	add_menu_page(
		'Beautiful Carousel',
        'Beautiful Carousel',
        'manage_options',
        'btfc-carousel-settings',
        'btfc_render_admin_page',
        'dashicons-format-gallery'
	);
}

add_action('admin_enqueue_scripts', 'btfc_enqueue_admin_assets');
function btfc_enqueue_admin_assets($hook) {
	if ($hook !== 'toplevel_page_btfc-carousel-settings') return;
	
	wp_enqueue_media(); //Charge la médiathèque
	wp_enqueue_script('btfc-admin', plugin_dir_url(__FILE__) . 'admin/admin.js', [], false, true);
}

function btfc_render_admin_page () {
	$image_ids = get_option('btfc_image_ids', '');
	?>
		<div class="wrap">
			<h1>Images du caroussel</h1>
			<form method="post" action="options.php">
				<?php settings_fields('btfc_settings'); ?>
				<?php do_settings_sections('btfc_settings'); ?>
				<input type="hidden" id="btfc_image_ids" name="btfc_image_ids" value="<?php echo esc_attr($image_ids); ?>" />
				<div id="btfc-preview">
					<?php
						if ($image_ids) {
							$ids = explode(',', $image_ids);
							foreach ($ids as $id) {
								echo wp_get_attachment_image($id, 'thumbnail');
							}
						}
					?>
				</div>
				<button type="button" class="button" id="btfc-select-images">Sélectionner des images</button>
            	<p><input type="submit" class="button-primary" value="Enregistrer" /></p>
			</form>
		</div>
	<?php
}

//Enregistrement des options
add_action('admin_init', 'btfc_register_settings');
function btfc_register_settings() {
	register_setting('btfc_settings', 'btfc_image_ids');
}