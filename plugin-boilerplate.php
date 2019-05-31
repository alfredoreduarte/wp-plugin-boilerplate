<?php
/**
 * Plugin Name: Custom plugin boilerplate
 * Description: Custom plugin boilerplate
 * Version: 1.0.0
 * Author: Alfredo Re
 * Author URI: https://alfredo.re
 * Text Domain: alfredo.re
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/alfredoreduarte
 */

class Plugin_Boilerplate {
	public function __construct() {
		// Hook into the admin menu
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
		add_action( 'admin_init', array( $this, 'setup_sections' ) );
		add_action( 'admin_init', array( $this, 'setup_fields' ) );
	}

	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = 'Plugin Boilerplate';
		$menu_title = 'Plugin Boilerplate';
		$capability = 'manage_options';
		$slug = 'plugin_boilerplate';
		$callback = array( $this, 'plugin_settings_page_content' );
		$icon = 'dashicons-editor-insertmore';
		$position = 300;
		add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
		// add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback ); // as sub-menu
	}

	public function plugin_settings_page_content() { ?>
		<div class="wrap">
			<h2>My Awesome Settings Page</h2>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'plugin_boilerplate' );
				do_settings_sections( 'plugin_boilerplate' );
				submit_button();
				?>
			</form>
		</div> <?php
	}

	public function setup_sections() {
		add_settings_section( 'our_first_section', 'My First Section Title', array( $this, 'section_callback' ), 'plugin_boilerplate' );
	}

	public function section_callback($args) {
		echo "Section Subtitle.";
	}

	public function setup_fields() {
		$fields = array(
			array(
				'uid' => 'our_first_field',
				'label' => 'Field Name',
				'section' => 'our_first_section',
				'type' => 'text',
				'options' => false,
				'placeholder' => 'field name',
				'helper' => 'Does this help?',
				'supplemental' => 'I am underneath!',
				'default' => null
			),
			array(
				'uid' => 'our_second_field',
				'label' => 'Awesome Date',
				'section' => 'our_first_section',
				'type' => 'textarea',
				'options' => false,
				'placeholder' => 'DD/MM/YYYY',
				'helper' => 'Does this help?',
				'supplemental' => 'I am underneath!',
				'default' => '01/01/2015'
			),
			array(
				'uid' => 'our_third_field',
				'label' => 'Awesome Select',
				'section' => 'our_first_section',
				'type' => 'select',
				'options' => array(
					'yes' => 'Yeppers',
					'no' => 'No way dude!',
					'maybe' => 'Meh, whatever.'
				),
				'placeholder' => 'Text goes here',
				'helper' => 'Does this help?',
				'supplemental' => 'I am underneath!',
				'default' => 'maybe'
			)
		);
		foreach( $fields as $field ){
			add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'plugin_boilerplate', $field['section'], $field );
			register_setting( 'plugin_boilerplate', $field['uid'] );
		}
	}

	public function field_callback($args) {
		$value = get_option( $args['uid'] ); // Get the current value, if there is one
		if( ! $value ) { // If no value exists
			$value = $args['default']; // Set to our default
		}
		// Check which type of field we want
		switch( $args['type'] ){
			case 'text': // If it is a text field
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $args['uid'], $args['type'], $args['placeholder'], $value );
				break;
			case 'textarea': // If it is a textarea
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $args['uid'], $args['placeholder'], $value );
				break;
			case 'select': // If it is a select dropdown
				if( ! empty ( $args['options'] ) && is_array( $args['options'] ) ){
				$options_markup = "";
				foreach( $args['options'] as $key => $label ){
				$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
				}
				printf( '<select name="%1$s" id="%1$s">%2$s</select>', $args['uid'], $options_markup );
			}
		}
		// If there is help text
		if( $helper = $args['helper'] ){
			printf( '<span class="helper"> %s</span>', $helper ); // Show it
		}

		// If there is supplemental text
		if( $supplimental = $args['supplemental'] ){
			printf( '<p class="description">%s</p>', $supplimental ); // Show it
		}
	}
}

new Plugin_Boilerplate();

?>