<?php
/**
 * Plugin Name:       Quiz de Cultura General
 * Description:       Plugin que consume la API pública de Open Trivia Database
 * Author:            Jason
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       quiz-cultura-general
 * Version:           1.0.0
 */


// SEGURIDAD: Evita el acceso directo al archivo si no se carga desde WP.
defined( 'ABSPATH' ) || exit;


// CONSTANTES DEL PLUGIN
define( 'QCG_VERSION', '1.0.0' );
define( 'QCG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'QCG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'QCG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// CARGA DE ARCHIVOS DEL PLUGIN
require_once QCG_PLUGIN_DIR . 'includes/class-qcg-settings.php';
require_once QCG_PLUGIN_DIR . 'includes/class-qcg-shortcode.php';

/**
 * Inicializa las clases principales del plugin.
 * Se usa el hook de acción 'plugins_loaded' para asegurarse de que
 * WordPress y todos los plugins ya estén completamente cargados.
 */
function qcg_init_plugin() {
	new QCG_Settings();
	new QCG_Shortcode();
}
add_action( 'plugins_loaded', 'qcg_init_plugin' );

// hooks de activación/desactivación del plugin
function qcg_activate_plugin() {
	add_option( 'qcg_default_category', '9' ); // 9 = General Knowledge en Open Trivia DB.
	add_option( 'qcg_default_difficulty', 'easy' );
}
register_activation_hook( __FILE__, 'qcg_activate_plugin' );


function qcg_deactivate_plugin() {
	delete_transient( 'qcg_categories_cache' );
}
register_deactivation_hook( __FILE__, 'qcg_deactivate_plugin' );

/**
 * Hook de tipo filter.
 * Agrega un enlace directo de "Ajustes" en la lista de plugins
 * (Plugins > Quiz de Cultura General > Ajustes), facilitando el acceso
 * a la pantalla de configuración del administrador.
 */
function qcg_add_settings_link( $links ) {
	$settings_url  = admin_url( 'options-general.php?page=qcg-settings' );
	$settings_link = '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Ajustes', 'quiz-cultura-general' ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links_' . QCG_PLUGIN_BASENAME, 'qcg_add_settings_link' );
