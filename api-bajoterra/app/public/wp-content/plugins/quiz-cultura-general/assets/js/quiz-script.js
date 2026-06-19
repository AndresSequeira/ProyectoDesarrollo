/**
 * Quiz de Cultura General - Script del frontend
 *
 * Funcionalidad:
 *  1. Al hacer clic en "Nueva pregunta", se envía una petición
 *     asíncrona (Fetch API) al endpoint AJAX de WordPress, que
 *     a su vez consulta la API pública de Open Trivia DB.
 *  2. Los filtros de categoría/dificultad determinan qué pregunta
 *     se solicita.
 *  3. Al hacer clic en una respuesta, se valida en el cliente,
 *     se muestra feedback visual y se actualiza el puntaje.
 *  4. "Reiniciar puntaje" vuelve el contador a cero.
 */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var quizzes = document.querySelectorAll( '.qcg-container' );
		quizzes.forEach( initQuiz );
	} );

	/**
	 * Inicializa un widget de quiz individual.
	 * Permite que el shortcode se use varias veces en la misma página.
	 *
	 * @param {HTMLElement} container Contenedor del widget.
	 */
	function initQuiz( container ) {
		var elements = {
			newBtn:     container.querySelector( '.qcg-btn-new' ),
			resetBtn:   container.querySelector( '.qcg-btn-reset' ),
			catSelect:  container.querySelector( '.qcg-filter-categoria' ),
			diffSelect: container.querySelector( '.qcg-filter-dificultad' ),
			question:   container.querySelector( '.qcg-question-text' ),
			answers:    container.querySelector( '.qcg-answers' ),
			feedback:   container.querySelector( '.qcg-feedback' ),
			scoreValue: container.querySelector( '.qcg-score-value' ),
			loader:     container.querySelector( '.qcg-loader' ),
		};

		var state = {
			score: 0,
			correctAnswer: '',
			isAnswered: false,
			isLoading: false,
		};

		elements.newBtn.addEventListener( 'click', function () {
			fetchNewQuestion( elements, state );
		} );

		elements.resetBtn.addEventListener( 'click', function () {
			state.score = 0;
			elements.scoreValue.textContent = '0';
			setFeedback( elements, '', '' );
		} );
	}

	/**
	 * Solicita una nueva pregunta al endpoint AJAX de WordPress
	 * usando la Fetch API 
	 *
	 * @param {Object} elements Referencias a los nodos del DOM.
	 * @param {Object} state    Estado interno del quiz 
	 */
	function fetchNewQuestion( elements, state ) {
		if ( state.isLoading ) {
			return;
		}

		state.isLoading = true;
		state.isAnswered = false;

		toggleLoader( elements, true );
		setFeedback( elements, '', '' );
		elements.answers.innerHTML = '';
		elements.question.textContent = '';

		var formData = new FormData();
		formData.append( 'action', 'qcg_get_question' );
		formData.append( 'nonce', qcgData.nonce );
		formData.append( 'categoria', elements.catSelect.value );
		formData.append( 'dificultad', elements.diffSelect.value );

		fetch( qcgData.ajaxUrl, {
			method: 'POST',
			body: formData,
		} )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( data ) {
				toggleLoader( elements, false );
				state.isLoading = false;

				if ( ! data.success ) {
					var message = ( data.data && data.data.message )
						? data.data.message
						: 'Ocurrió un error al obtener la pregunta.';
					elements.question.textContent = message;
					return;
				}

				renderQuestion( elements, state, data.data );
			} )
			.catch( function () {
				toggleLoader( elements, false );
				state.isLoading = false;
				elements.question.textContent = 'Error de conexión. Verifica tu internet e inténtalo de nuevo.';
			} );
	}

	/**
	 * Pinta la pregunta y las opciones de respuesta en el DOM.
	 *
	 * @param {Object} elements Referencias a los nodos del DOM.
	 * @param {Object} state    Estado interno del quiz.
	 * @param {Object} data     Datos recibidos de la API de Open Trivia DB.
	 */
	function renderQuestion( elements, state, data ) {
		elements.question.textContent = data.question;
		state.correctAnswer = data.correct;
		elements.answers.innerHTML = '';

		data.answers.forEach( function ( answerText ) {
			var btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.className = 'qcg-answer-btn';
			btn.textContent = answerText;

			btn.addEventListener( 'click', function () {
				handleAnswerClick( elements, state, btn, answerText );
			} );

			elements.answers.appendChild( btn );
		} );
	}

	/**
	 * Procesa el clic del usuario sobre una opción de respuesta:
	 * marca correcto/incorrecto, actualiza el puntaje y bloquea
	 * el resto de botones para evitar múltiples respuestas.
	 */
	function handleAnswerClick( elements, state, button, answerText ) {
		if ( state.isAnswered ) {
			return;
		}
		state.isAnswered = true;

		var allButtons = elements.answers.querySelectorAll( '.qcg-answer-btn' );
		allButtons.forEach( function ( btn ) {
			btn.disabled = true;
		} );

		if ( answerText === state.correctAnswer ) {
			button.classList.add( 'qcg-correct' );
			state.score += 1;
			elements.scoreValue.textContent = String( state.score );
			setFeedback( elements, '¡Correcto! ', 'correct' );
		} else {
			button.classList.add( 'qcg-incorrect' );
			setFeedback( elements, 'Incorrecto. La respuesta correcta era: ' + state.correctAnswer, 'incorrect' );

			// Resaltar también cuál era la opción correcta.
			allButtons.forEach( function ( btn ) {
				if ( btn.textContent === state.correctAnswer ) {
					btn.classList.add( 'qcg-correct' );
				}
			} );
		}
	}

	/**
	 * Muestra u oculta el indicador de Cargando
	 */
	function toggleLoader( elements, show ) {
		if ( show ) {
			elements.loader.classList.add( 'is-active' );
		} else {
			elements.loader.classList.remove( 'is-active' );
		}
	}

	/**
	 * Actualiza el mensaje de feedback y su estilo.
	 */
	function setFeedback( elements, text, type ) {
		elements.feedback.textContent = text;
		elements.feedback.classList.remove( 'qcg-feedback-correct', 'qcg-feedback-incorrect' );

		if ( 'correct' === type ) {
			elements.feedback.classList.add( 'qcg-feedback-correct' );
		} else if ( 'incorrect' === type ) {
			elements.feedback.classList.add( 'qcg-feedback-incorrect' );
		}
	}
} )();
