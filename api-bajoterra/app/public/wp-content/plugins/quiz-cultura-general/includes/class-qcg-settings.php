<?php
defined( 'ABSPATH' ) || exit;

class QCG_Settings {

	/**
	 * Registra los hooks de administración (acciones).
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Crea la subpágina de ajustes dentro del menú "Ajustes" de wp-admin.
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Quiz de Cultura General - Ajustes', 'quiz-cultura-general' ),
			__( 'Quiz Cultura General', 'quiz-cultura-general' ),
			'manage_options',
			'qcg-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Registra las opciones, secciones y campos del formulario de ajustes.
	 */
	public function register_settings() {
		register_setting(
			'qcg_settings_group',
			'qcg_default_category',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);

		register_setting(
			'qcg_settings_group',
			'qcg_default_difficulty',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);

		add_settings_section(
			'qcg_main_section',
			__( 'Configuración por defecto del Quiz', 'quiz-cultura-general' ),
			array( $this, 'render_section_description' ),
			'qcg-settings'
		);

		add_settings_field(
			'qcg_default_category',
			__( 'Categoría por defecto', 'quiz-cultura-general' ),
			array( $this, 'category_field_html' ),
			'qcg-settings',
			'qcg_main_section'
		);

		add_settings_field(
			'qcg_default_difficulty',
			__( 'Dificultad por defecto', 'quiz-cultura-general' ),
			array( $this, 'difficulty_field_html' ),
			'qcg-settings',
			'qcg_main_section'
		);
	}

	/**
	 * Texto descriptivo de la sección de ajustes.
	 */
	public function render_section_description() {
		echo '<p>' . esc_html__( 'Estos valores se usan cuando el shortcode [quiz_cultura] se inserta sin atributos, y como selección inicial de los filtros en el frontend.', 'quiz-cultura-general' ) . '</p>';
	}

	/**
	 * Renderiza el select de categorías 
	 */
	public function category_field_html() {
		$selected   = get_option( 'qcg_default_category', '9' );
		$categories = self::get_categories();

		if ( empty( $categories ) ) {
			echo '<p class="description">' . esc_html__( 'No se pudo obtener la lista de categorías desde la API en este momento.', 'quiz-cultura-general' ) . '</p>';
			return;
		}

		echo '<select name="qcg_default_category">';
		foreach ( $categories as $cat ) {
			printf(
				'<option value="%d" %s>%s</option>',
				absint( $cat['id'] ),
				selected( $selected, $cat['id'], false ),
				esc_html( html_entity_decode( $cat['name'] ) )
			);
		}
		echo '</select>';
	}

	/**
	 * Renderiza el select de dificultad.
	 */
	public function difficulty_field_html() {
		$selected = get_option( 'qcg_default_difficulty', 'easy' );

		$options = array(
			'easy'   => __( 'Fácil', 'quiz-cultura-general' ),
			'medium' => __( 'Media', 'quiz-cultura-general' ),
			'hard'   => __( 'Difícil', 'quiz-cultura-general' ),
		);

		echo '<select name="qcg_default_difficulty">';
		foreach ( $options as $value => $label ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $value ),
				selected( $selected, $value, false ),
				esc_html( $label )
			);
		}
		echo '</select>';
	}

	/**
	 * Imprime el formulario completo de la página de ajustes.
	 */
	public function render_settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Quiz de Cultura General - Ajustes', 'quiz-cultura-general' ); ?></h1>
			<p>
				<?php esc_html_e( 'Inserta el quiz en cualquier entrada o página usando el shortcode:', 'quiz-cultura-general' ); ?>
				<code>[quiz_cultura]</code>
			</p>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'qcg_settings_group' );
				do_settings_sections( 'qcg-settings' );
				submit_button( __( 'Guardar cambios', 'quiz-cultura-general' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Obtiene la lista de categorías de la API de Open Trivia DB,
	 * usando un transient para no consultar la API en cada carga.
	 */
	public static function get_categories() {
		$categories = get_transient( 'qcg_categories_cache' );

		if ( false !== $categories ) {
			return $categories;
		}

		$response = wp_remote_get(
			'https://opentdb.com/api_category.php',
			array( 'timeout' => 10 )
		);

		// Si la API falla, devuelve un arreglo vacío sin romper la página.
		if ( is_wp_error( $response ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		$categories = isset( $data['trivia_categories'] ) ? $data['trivia_categories'] : array();

		/**
		 * Filtro: permite a otros plugins modificar cuánto tiempo
		 * se cachea la lista de categorías 
		 */
		$cache_duration = apply_filters( 'qcg_categories_cache_duration', DAY_IN_SECONDS );

		set_transient( 'qcg_categories_cache', $categories, $cache_duration );

		return $categories;
	}
}
