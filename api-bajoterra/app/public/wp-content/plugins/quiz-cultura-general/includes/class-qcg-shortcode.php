<?php
/**
 * Clase QCG_Shortcode
 * QCG se refiere a "Quiz Cultura General". 
 * Registra el shortcode [quiz_cultura], agrega los estilos/scripts a la cola
 * solo cuando el shortcode está presente en el contenido, y expone
 * un endpoint AJAX que consulta la API pública de Open Trivia DB mediante wp_remote_get().
 */

defined( 'ABSPATH' ) || exit;

class QCG_Shortcode {

	public function __construct() {
		add_shortcode( 'quiz_cultura', array( $this, 'render_shortcode' ) );

		// Registro condicional de CSS/JS en la cola de carga.
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_assets' ) );

		// Endpoint AJAX: disponible tanto para usuarios logueados como visitantes.
		add_action( 'wp_ajax_qcg_get_question', array( $this, 'ajax_get_question' ) );
		add_action( 'wp_ajax_nopriv_qcg_get_question', array( $this, 'ajax_get_question' ) );
	}

	/**
	 * Agrega los assets del plugin a la cola de carga únicamente si el contenido de la
	 * entrada/página actual contiene el shortcode [quiz_cultura].
	 * Esto evita cargar CSS/JS innecesario en páginas que no lo usan.
	 */
	public function maybe_enqueue_assets() {
		if ( ! is_singular() ) {
			return;
		}

		global $post;

		if ( $post instanceof WP_Post && has_shortcode( $post->post_content, 'quiz_cultura' ) ) {
			$this->enqueue_assets();
		}
	}

	/**
	 * Agrega la hoja de estilos y el script del frontend a la cola de carga, y pasa
	 * datos de PHP a JavaScript mediante wp_localize_script().
	 */
	private function enqueue_assets() {
		wp_enqueue_style(
			'qcg-style',
			QCG_PLUGIN_URL . 'assets/css/quiz-style.css',
			array(),
			QCG_VERSION
		);

		wp_enqueue_script(
			'qcg-script',
			QCG_PLUGIN_URL . 'assets/js/quiz-script.js',
			array(), 
			QCG_VERSION,
			true 
		);

		wp_localize_script(
			'qcg-script',
			'qcgData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'qcg_nonce' ),
			)
		);
	}

	/**
	 * Renderiza el HTML del shortcode [quiz_cultura].
	 * Atributos soportados:
	 *   categoria  -> ID de categoría de Open Trivia DB (por defecto, el de Ajustes).
	 *   dificultad -> easy | medium | hard (por defecto, el de Ajustes).
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'categoria'  => get_option( 'qcg_default_category', '9' ),
				'dificultad' => get_option( 'qcg_default_difficulty', 'easy' ),
			),
			$atts,
			'quiz_cultura'
		);

		$categories = QCG_Settings::get_categories();

		ob_start();
		?>
		<div class="qcg-container" data-categoria="<?php echo esc_attr( $atts['categoria'] ); ?>" data-dificultad="<?php echo esc_attr( $atts['dificultad'] ); ?>">

			<div class="qcg-header">
				<h3 class="qcg-title"><?php esc_html_e( ' Quiz de Cultura General', 'quiz-cultura-general' ); ?></h3>
				<div class="qcg-score">
					<?php esc_html_e( 'Puntaje', 'quiz-cultura-general' ); ?>:
					<span class="qcg-score-value">0</span>
				</div>
			</div>

			<div class="qcg-filters">
				<label class="qcg-filter-label">
					<?php esc_html_e( 'Categoría', 'quiz-cultura-general' ); ?>
					<select class="qcg-filter-categoria">
						<?php if ( empty( $categories ) ) : ?>
							<option value="9"><?php esc_html_e( 'Conocimiento General', 'quiz-cultura-general' ); ?></option>
						<?php else : ?>
							<?php foreach ( $categories as $cat ) : ?>
								<option value="<?php echo esc_attr( $cat['id'] ); ?>" <?php selected( $atts['categoria'], $cat['id'] ); ?>>
									<?php echo esc_html( html_entity_decode( $cat['name'] ) ); ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</label>

				<label class="qcg-filter-label">
					<?php esc_html_e( 'Dificultad', 'quiz-cultura-general' ); ?>
					<select class="qcg-filter-dificultad">
						<option value="easy" <?php selected( $atts['dificultad'], 'easy' ); ?>><?php esc_html_e( 'Fácil', 'quiz-cultura-general' ); ?></option>
						<option value="medium" <?php selected( $atts['dificultad'], 'medium' ); ?>><?php esc_html_e( 'Media', 'quiz-cultura-general' ); ?></option>
						<option value="hard" <?php selected( $atts['dificultad'], 'hard' ); ?>><?php esc_html_e( 'Difícil', 'quiz-cultura-general' ); ?></option>
					</select>
				</label>
			</div>

			<div class="qcg-question-box">
				<p class="qcg-question-text"><?php esc_html_e( 'Presiona "Nueva pregunta" para comenzar ', 'quiz-cultura-general' ); ?></p>
				<div class="qcg-answers"></div>
				<p class="qcg-feedback" role="status"></p>
				<div class="qcg-loader" aria-live="polite"><?php esc_html_e( 'Cargando pregunta...', 'quiz-cultura-general' ); ?></div>
			</div>

			<div class="qcg-actions">
				<button type="button" class="qcg-btn qcg-btn-new">
					<?php esc_html_e( ' Nueva pregunta', 'quiz-cultura-general' ); ?>
				</button>
				<button type="button" class="qcg-btn qcg-btn-reset">
					<?php esc_html_e( ' Reiniciar puntaje', 'quiz-cultura-general' ); ?>
				</button>
			</div>

		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Manejador AJAX: obtiene una pregunta nueva desde la API de
	 * Open Trivia DB según la categoría y dificultad recibidas,
	 * y la devuelve como JSON al frontend.
	 *
	 * Seguridad:
	 *  - Verifica el nonce con check_ajax_referer().
	 *  - Sanitiza todos los valores recibidos por POST.
	 */
	public function ajax_get_question() {
		check_ajax_referer( 'qcg_nonce', 'nonce' );

		$categoria  = isset( $_POST['categoria'] ) ? absint( $_POST['categoria'] ) : 9;
		$dificultad = isset( $_POST['dificultad'] ) ? sanitize_text_field( wp_unslash( $_POST['dificultad'] ) ) : 'easy';

		// Whitelist de dificultades válidas para evitar valores arbitrarios.
		$dificultades_validas = array( 'easy', 'medium', 'hard' );
		if ( ! in_array( $dificultad, $dificultades_validas, true ) ) {
			$dificultad = 'easy';
		}

		$api_url = add_query_arg(
			array(
				'amount'     => 1,
				'category'   => $categoria,
				'difficulty' => $dificultad,
				'type'       => 'multiple',
				'encode'     => 'url3986',
			),
			'https://opentdb.com/api.php'
		);

		$response = wp_remote_get( $api_url, array( 'timeout' => 10 ) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error(
				array( 'message' => __( 'No se pudo conectar con la API de preguntas. Intenta de nuevo.', 'quiz-cultura-general' ) )
			);
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['results'][0] ) ) {
			wp_send_json_error(
				array( 'message' => __( 'No hay preguntas disponibles para esa combinación de categoría/dificultad. Prueba otra.', 'quiz-cultura-general' ) )
			);
		}

		$result = $data['results'][0];

		// La API devuelve el texto codificado 
		$question  = rawurldecode( $result['question'] );
		$correct   = rawurldecode( $result['correct_answer'] );
		$incorrect = array_map( 'rawurldecode', $result['incorrect_answers'] );
		$category  = isset( $result['category'] ) ? rawurldecode( $result['category'] ) : '';

		// Mezcla las respuestas correctas e incorrectas para que el orden cambie cada vez.
		$answers = array_merge( $incorrect, array( $correct ) );
		shuffle( $answers );

		wp_send_json_success(
			array(
				'question'   => wp_strip_all_tags( $question ),
				'answers'    => array_map( 'wp_strip_all_tags', $answers ),
				'correct'    => wp_strip_all_tags( $correct ),
				'category'   => wp_strip_all_tags( $category ),
				'difficulty' => $dificultad,
			)
		);
	}
}
