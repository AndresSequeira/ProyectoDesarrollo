<?php
/**
 * Plugin Name:       API Bajoterra - Babosas Interactivas
 * Description:       Directorio interactivo de babosas de Bajoterra con REST API local, filtros, buscador y tarjetas clicables.
 * Version:           1.0.2
 * Author:            Sequeira
 * Text Domain:       bajoterra-babosas-api
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BAJOTERRA_BABOSAS_API_VERSION', '1.0.2' );
define( 'BAJOTERRA_BABOSAS_API_PATH', plugin_dir_path( __FILE__ ) );
define( 'BAJOTERRA_BABOSAS_API_URL', plugin_dir_url( __FILE__ ) );

/**
 * Datos curados del universo Bajoterra.
 *
 * No existe una API publica oficial de babosas de Bajoterra. Por eso el plugin
 * expone estos datos como una REST API propia y complementa el contexto con
 * una consulta publica a Wikipedia usando wp_remote_get().
 */
function bajoterra_babosas_api_get_slugs() {
	return array(
		array(
			'id'          => 1,
			'name'        => 'Burpy',
			'type'        => 'Infurnus',
			'element'     => 'Fuego',
			'rarity'      => 'Legendaria',
			'owner'       => 'Eli Shane',
			'power'       => 'Lanza ataques de fuego concentrado y puede abrir paso en combates intensos.',
			'personality' => 'Valiente, leal y muy protector con Eli.',
			'weakness'    => 'Puede agotarse rapido si se usa sin descanso.',
			'color'       => '#f05d23',
			'icon'        => 'F',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/burpy.png',
		),
		array(
			'id'          => 2,
			'name'        => 'Joules',
			'type'        => 'Tazerling',
			'element'     => 'Energia',
			'rarity'      => 'Rara',
			'owner'       => 'Eli Shane',
			'power'       => 'Genera descargas electricas para paralizar o interrumpir ataques enemigos.',
			'personality' => 'Rapida, inquieta y lista para reaccionar.',
			'weakness'    => 'Sus ataques pierden precision contra objetivos aislantes.',
			'color'       => '#f3b41b',
			'icon'        => 'E',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/joules.png',
		),
		array(
			'id'          => 3,
			'name'        => 'Doc',
			'type'        => 'Boon Doc',
			'element'     => 'Sanacion',
			'rarity'      => 'Legendaria',
			'owner'       => 'Eli Shane',
			'power'       => 'Cura aliados, purifica babosas contaminadas y restaura energia vital.',
			'personality' => 'Paciente, sabio y sereno en momentos dificiles.',
			'weakness'    => 'No esta pensado para dano directo.',
			'color'       => '#4fb06d',
			'icon'        => 'S',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/doc.png',
		),
		array(
			'id'          => 4,
			'name'        => 'Banger',
			'type'        => 'Rammstone',
			'element'     => 'Tierra',
			'rarity'      => 'Comun',
			'owner'       => 'Kord Zane',
			'power'       => 'Embiste con fuerza de roca y rompe defensas frontales.',
			'personality' => 'Terca, fuerte y confiable.',
			'weakness'    => 'Tiene menos movilidad que babosas veloces.',
			'color'       => '#8b6f47',
			'icon'        => 'T',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/banger.png',
		),
		array(
			'id'          => 5,
			'name'        => 'Bubbaleone',
			'type'        => 'Aquabeek',
			'element'     => 'Agua',
			'rarity'      => 'Comun',
			'owner'       => 'Trixie Sting',
			'power'       => 'Dispara chorros de agua a presion y puede desviar proyectiles.',
			'personality' => 'Curiosa, juguetona y adaptable.',
			'weakness'    => 'Su potencia baja en zonas secas o calientes.',
			'color'       => '#2f8fc8',
			'icon'        => 'A',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/bubbaleone.png',
		),
		array(
			'id'          => 6,
			'name'        => 'Fandango',
			'type'        => 'Hoverbug',
			'element'     => 'Aire',
			'rarity'      => 'Rara',
			'owner'       => 'La Banda Shane',
			'power'       => 'Crea corrientes de aire para empujar, levantar o cambiar trayectorias.',
			'personality' => 'Ligera, veloz y muy dificil de atrapar.',
			'weakness'    => 'No rinde igual en espacios cerrados sin flujo de aire.',
			'color'       => '#58a9a2',
			'icon'        => 'V',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/fandango.png',
		),
		array(
			'id'          => 7,
			'name'        => 'Lumer',
			'type'        => 'Phosphoro',
			'element'     => 'Luz',
			'rarity'      => 'Comun',
			'owner'       => 'Eli Shane',
			'power'       => 'Produce destellos brillantes para cegar, revelar rutas y desorientar.',
			'personality' => 'Noble, alerta y expresiva.',
			'weakness'    => 'Pierde ventaja contra enemigos protegidos de la luz.',
			'color'       => '#e8d84f',
			'icon'        => 'L',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/lumer.png',
		),
		array(
			'id'          => 8,
			'name'        => 'Grenuke',
			'type'        => 'Grenuke',
			'element'     => 'Toxico',
			'rarity'      => 'Rara',
			'owner'       => 'Lanzadores expertos',
			'power'       => 'Explota en nubes toxicas que obligan al rival a cambiar de posicion.',
			'personality' => 'Volatil, intensa y dificil de controlar.',
			'weakness'    => 'Requiere buena punteria para no afectar aliados.',
			'color'       => '#6f9f34',
			'icon'        => 'X',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/grenuke.png',
		),
		array(
			'id'          => 9,
			'name'        => 'Enigmo',
			'type'        => 'Enigmo',
			'element'     => 'Energia',
			'rarity'      => 'Muy rara',
			'owner'       => 'Clan de las Sombras',
			'power'       => 'Proyecta mensajes, ilusiones y pistas ocultas en la mente del objetivo.',
			'personality' => 'Misteriosa, silenciosa y observadora.',
			'weakness'    => 'Depende de concentracion y timing.',
			'color'       => '#7861c9',
			'icon'        => '?',
			'image'       => BAJOTERRA_BABOSAS_API_URL . 'assets/images/enigmo.png',
		),
	);
}

function bajoterra_babosas_api_get_public_context( $refresh = false ) {
	$cache_key = 'bajoterra_babosas_api_public_context';

	if ( ! $refresh ) {
		$cached = get_transient( $cache_key );
		if ( false !== $cached ) {
			return $cached;
		}
	}

	$context = array(
		'title'       => 'Bajoterra',
		'extract'     => 'Bajoterra es un mundo subterraneo donde las babosas adquieren habilidades especiales al ser disparadas a gran velocidad.',
		'source_name' => 'Resumen local',
		'source_url'  => '',
	);

	$response = wp_remote_get(
		'https://es.wikipedia.org/api/rest_v1/page/summary/Slugterra',
		array(
			'timeout' => 10,
			'headers' => array(
				'Accept' => 'application/json',
			),
		)
	);

	if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( is_array( $body ) && ! empty( $body['extract'] ) ) {
			$context = array(
				'title'       => sanitize_text_field( $body['title'] ?? 'Slugterra' ),
				'extract'     => wp_strip_all_tags( $body['extract'] ),
				'source_name' => 'Wikipedia REST API',
				'source_url'  => esc_url_raw( $body['content_urls']['desktop']['page'] ?? 'https://es.wikipedia.org/wiki/Slugterra' ),
			);
		}
	}

	set_transient( $cache_key, $context, 12 * HOUR_IN_SECONDS );
	return $context;
}

function bajoterra_babosas_api_register_assets() {
	wp_register_style(
		'bajoterra-babosas-api',
		BAJOTERRA_BABOSAS_API_URL . 'assets/css/bajoterra-babosas.css',
		array(),
		BAJOTERRA_BABOSAS_API_VERSION
	);

	wp_register_script(
		'bajoterra-babosas-api',
		BAJOTERRA_BABOSAS_API_URL . 'assets/js/bajoterra-babosas.js',
		array(),
		BAJOTERRA_BABOSAS_API_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'bajoterra_babosas_api_register_assets' );

function bajoterra_babosas_api_register_routes() {
	register_rest_route(
		'bajoterra/v1',
		'/babosas',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'bajoterra_babosas_api_rest_get_slugs',
			'permission_callback' => '__return_true',
			'args'                => array(
				'search'  => array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				'element' => array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				'refresh' => array(
					'sanitize_callback' => 'rest_sanitize_boolean',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'bajoterra_babosas_api_register_routes' );

function bajoterra_babosas_api_rest_get_slugs( WP_REST_Request $request ) {
	$search  = strtolower( trim( (string) $request->get_param( 'search' ) ) );
	$element = trim( (string) $request->get_param( 'element' ) );
	$refresh = (bool) $request->get_param( 'refresh' );
	$slugs   = bajoterra_babosas_api_get_slugs();

	if ( '' !== $element && 'Todos' !== $element ) {
		$slugs = array_values(
			array_filter(
				$slugs,
				function ( $slug ) use ( $element ) {
					return 0 === strcasecmp( $slug['element'], $element );
				}
			)
		);
	}

	if ( '' !== $search ) {
		$slugs = array_values(
			array_filter(
				$slugs,
				function ( $slug ) use ( $search ) {
					$haystack = strtolower( $slug['name'] . ' ' . $slug['type'] . ' ' . $slug['element'] . ' ' . $slug['power'] );
					return false !== strpos( $haystack, $search );
				}
			)
		);
	}

	return rest_ensure_response(
		array(
			'context' => bajoterra_babosas_api_get_public_context( $refresh ),
			'total'   => count( $slugs ),
			'slugs'   => $slugs,
		)
	);
}

function bajoterra_babosas_api_render_shortcode() {
	wp_enqueue_style( 'bajoterra-babosas-api' );
	wp_enqueue_script( 'bajoterra-babosas-api' );

	wp_localize_script(
		'bajoterra-babosas-api',
		'BajoterraBabosas',
		array(
			'restUrl' => esc_url_raw( rest_url( 'bajoterra/v1/babosas' ) ),
		)
	);

	ob_start();
	?>
	<section class="bajoterra-app" data-bajoterra-app data-rest-url="<?php echo esc_url( rest_url( 'bajoterra/v1/babosas' ) ); ?>">
		<div class="bajoterra-hero">
			<div>
				<p class="bajoterra-kicker">API Bajoterra</p>
				<h2>Babosas interactivas</h2>
				<p class="bajoterra-summary" data-bajoterra-summary>Cargando informacion de Bajoterra...</p>
			</div>
			<button class="bajoterra-refresh" type="button" data-bajoterra-refresh>Actualizar</button>
		</div>

		<div class="bajoterra-toolbar">
			<label class="bajoterra-search">
				<span>Buscar</span>
				<input type="search" data-bajoterra-search placeholder="Nombre, tipo o poder">
			</label>
			<div class="bajoterra-filters" data-bajoterra-filters aria-label="Filtros por elemento"></div>
		</div>

		<div class="bajoterra-status" data-bajoterra-status role="status">Preparando babosas...</div>
		<div class="bajoterra-grid" data-bajoterra-grid></div>

		<div class="bajoterra-modal" data-bajoterra-modal aria-hidden="true">
			<div class="bajoterra-modal__panel" role="dialog" aria-modal="true" aria-labelledby="bajoterra-modal-title">
				<button class="bajoterra-modal__close" type="button" data-bajoterra-close aria-label="Cerrar">x</button>
				<div class="bajoterra-modal__content" data-bajoterra-modal-content></div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'bajoterra_babosas', 'bajoterra_babosas_api_render_shortcode' );
