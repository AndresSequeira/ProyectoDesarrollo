<?php
/**
 * Plugin Name:       Bajoterra Gutenberg Content
 * Description:       Bloque Gutenberg, CPT de misiones y contenido base para el home del proyecto.
 * Version:           1.0.0
 * Author:            Sequeira
 * Text Domain:       bajoterra-gutenberg-content
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BGT_VERSION', '1.0.0' );
define( 'BGT_PATH', plugin_dir_path( __FILE__ ) );
define( 'BGT_URL', plugin_dir_url( __FILE__ ) );

function bgt_register_mission_cpt() {
	register_post_type(
		'mision',
		array(
			'labels'       => array(
				'name'          => __( 'Misiones', 'bajoterra-gutenberg-content' ),
				'singular_name' => __( 'Mision', 'bajoterra-gutenberg-content' ),
				'add_new_item'  => __( 'Agregar mision', 'bajoterra-gutenberg-content' ),
				'edit_item'     => __( 'Editar mision', 'bajoterra-gutenberg-content' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-location-alt',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'misiones' ),
		)
	);
}
add_action( 'init', 'bgt_register_mission_cpt' );

function bgt_render_home_showcase_block( $attributes ) {
	$eyebrow = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : 'Proyecto WordPress';
	$title   = isset( $attributes['title'] ) ? $attributes['title'] : 'Bajoterra en blanco y negro';
	$text    = isset( $attributes['text'] ) ? $attributes['text'] : 'Un sitio minimalista con tema hijo, plugins personalizados, API interactiva, quiz y contenido editable desde Gutenberg.';

	ob_start();
	?>
	<section class="bgt-showcase">
		<p class="bgt-showcase__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<h2><?php echo esc_html( $title ); ?></h2>
		<p><?php echo esc_html( $text ); ?></p>
		<div class="bgt-showcase__grid" aria-label="<?php esc_attr_e( 'Resumen del proyecto', 'bajoterra-gutenberg-content' ); ?>">
			<div>
				<strong>01</strong>
				<span>Tema hijo con header, footer y templates.</span>
			</div>
			<div>
				<strong>02</strong>
				<span>Tres plugins personalizados.</span>
			</div>
			<div>
				<strong>03</strong>
				<span>CPT de misiones y contenido inicial.</span>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bgt_register_blocks() {
	register_block_type(
		BGT_PATH . 'build/home-showcase',
		array(
			'render_callback' => 'bgt_render_home_showcase_block',
		)
	);
}
add_action( 'init', 'bgt_register_blocks' );

function bgt_enqueue_assets() {
	wp_enqueue_style(
		'bgt-site',
		BGT_URL . 'assets/css/site.css',
		array(),
		BGT_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'bgt_enqueue_assets' );

function bgt_upsert_page( $title, $slug, $content ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $page instanceof WP_Post ) {
		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
			)
		);
		return (int) $page->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
		)
	);
}

function bgt_seed_post( $title, $slug, $content, $type = 'post' ) {
	$existing = get_page_by_path( $slug, OBJECT, $type );

	if ( $existing instanceof WP_Post ) {
		return (int) $existing->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_type'    => $type,
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
		)
	);
}

function bgt_seed_site_content() {
	bgt_register_mission_cpt();

	$home_content = '<!-- wp:bajoterra/home-showcase {"eyebrow":"Home del proyecto","title":"Bajoterra: guia interactiva minimalista","text":"Una entrega WordPress con tema hijo, API de babosas, quiz de cultura general, bloque Gutenberg y misiones como CPT."} /-->
<!-- wp:heading {"level":2} --><h2>Explora el sitio</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Este home concentra las piezas principales de la entrega: contenido editable, componentes interactivos y una linea visual sencilla en blanco y negro.</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[bajoterra_babosas]<!-- /wp:shortcode -->
<!-- wp:heading {"level":2} --><h2>Quiz interactivo</h2><!-- /wp:heading -->
<!-- wp:shortcode -->[quiz_cultura]<!-- /wp:shortcode -->';

	$about_content = '<!-- wp:heading {"level":1} --><h1>Acerca de</h1><!-- /wp:heading -->
<!-- wp:paragraph --><p>Este sitio fue construido para integrar un tema hijo, tres plugins personalizados, consumo de API, bloques Gutenberg y contenido administrable desde WordPress.</p><!-- /wp:paragraph -->
<!-- wp:list --><ul><li>Estilo minimalista en blanco y negro.</li><li>Plugins propios para API, quiz y contenido Gutenberg.</li><li>CPT de misiones para demostrar estructura de datos personalizada.</li></ul><!-- /wp:list -->';

	$home_id = bgt_upsert_page( 'Inicio', 'inicio', $home_content );
	$blog_id = bgt_upsert_page( 'Blog', 'blog', '<!-- wp:heading {"level":1} --><h1>Blog</h1><!-- /wp:heading -->' );
	bgt_upsert_page( 'Acerca de', 'acerca-de', $about_content );

	if ( $home_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	if ( $blog_id ) {
		update_option( 'page_for_posts', $blog_id );
	}

	bgt_seed_post(
		'Como funciona el directorio de babosas',
		'como-funciona-directorio-babosas',
		'<!-- wp:paragraph --><p>El directorio usa una REST API local para filtrar babosas por elemento, mostrar detalles y mantener una experiencia interactiva desde el frontend.</p><!-- /wp:paragraph -->'
	);

	bgt_seed_post(
		'Decisiones de diseno minimalista',
		'decisiones-diseno-minimalista',
		'<!-- wp:paragraph --><p>El sitio reduce color, sombras y ornamentos para que los plugins y el contenido sean el centro de la experiencia.</p><!-- /wp:paragraph -->'
	);

	bgt_seed_post(
		'Mision: Entrenamiento de babosas',
		'mision-entrenamiento-babosas',
		'<!-- wp:paragraph --><p>Practicar filtros, lectura de estadisticas y seleccion de babosas segun el tipo de desafio.</p><!-- /wp:paragraph -->',
		'mision'
	);

	bgt_seed_post(
		'Mision: Ruta por las cavernas',
		'mision-ruta-cavernas',
		'<!-- wp:paragraph --><p>Organizar una ruta de exploracion usando informacion del sitio y habilidades de cada babosa.</p><!-- /wp:paragraph -->',
		'mision'
	);

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'bgt_seed_site_content' );

function bgt_flush_on_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'bgt_flush_on_deactivation' );
