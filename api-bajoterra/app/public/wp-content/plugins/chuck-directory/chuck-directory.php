<?php
/**
 * Plugin Name: Directorio de Chuck Norris
 * Description: Un plugin para mostrar un directorio de frases de Chuck Norris
 * Version: 1.0
 * Author: José Zumbado
 * Text Domain: chuck-directory
 */

if (!defined("ABSPATH"))
    exit;


function shortcode_chuck_buscador() {
    $html = '<div style="text-align: center; font-family: sans-serif; background: #ffffff; border: 2px solid #e2e8f0; padding: 25px; border-radius: 12px; max-width: 450px; margin: 20px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">';
    
    $html .= '   <img src="https://api.chucknorris.io/img/chucknorris_logo_coloured_small@2x.png" alt="Chuck Norris" style="width: 70px; margin-bottom: 10px;">';
    $html .= '   <h3 style="margin: 0 0 15px 0; color: #1a202c; font-size: 1.2rem;">Buscador de factos</h3>';
    
    $html .= '   <div style="display: flex; gap: 10px; margin-bottom: 20px;">';
    $html .= '       <input type="text" id="chuck-search-input" placeholder="Ej: computer, money, animal..." style="flex: 1; padding: 10px; border: 2px solid #cbd5e0; border-radius: 6px; font-size: 0.9rem; outline: none;">';
    $html .= '       <button id="chuck-search-btn" onclick="buscarChiste()" style="background: #dd6b20; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">Buscar</button>';
    $html .= '   </div>';
    
    $html .= '   <div id="chuck-result-container" style="min-height: 50px; display: flex; align-items: center; justify-content: center;">';
    $html .= '       <p id="chuck-text" style="color: #a0aec0; font-style: italic; line-height: 1.6; font-size: 1rem; margin: 0;">Escribe una palabra en inglés para desafiar a Chuck...</p>';
    $html .= '   </div>';
    
    $html .= '</div>';

    $html .= '<script>
    function buscarChiste() {
        const input = document.getElementById("chuck-search-input");
        const textoFrase = document.getElementById("chuck-text");
        const boton = document.getElementById("chuck-search-btn");
        const palabra = input.value.trim().toLowerCase();

        // Validación: Si el usuario no escribe nada
        if (palabra === "") {
            textoFrase.innerHTML = "<span style=\"color: #e53e3e;\">Por favor, escribe una palabra primero.</span>";
            return;
        }

        // Estado de carga
        boton.innerText = "...";
        boton.disabled = true;
        textoFrase.style.opacity = "0.5";
        textoFrase.innerText = "Buscando en los archivos secretos de Chuck...";

        // Llamada a la API de búsqueda
        fetch(`https://api.chucknorris.io/jokes/search?query=${palabra}`)
            .then(response => response.json())
            .then(data => {
                boton.innerText = "Buscar";
                boton.disabled = false;
                textoFrase.style.opacity = "1";

                // Si la API no encontró chistes con esa palabra
                if (data.total === 0) {
                    textoFrase.innerHTML = `<span style="color: #e53e3e;">Chuck Norris no reconoce la palabra "${palabra}". Intenta con otra.</span>`;
                } else {
                    // La API devuelve un array (lista). Elegimos uno al azar de los resultados
                    const randomIndex = Math.floor(Math.random() * data.result.length);
                    const chisteElegido = data.result[randomIndex].value;
                    
                    // Mostramos el chiste en pantalla
                    textoFrase.innerHTML = `"${chisteElegido}"`;
                }
            })
            .catch(error => {
                boton.innerText = "Buscar";
                boton.disabled = false;
                textoFrase.style.opacity = "1";
                textoFrase.innerText = "Error de conexión. Chuck bloqueó la búsqueda.";
            });
    }

    // Permitir buscar también al presionar la tecla "Enter"
    document.getElementById("chuck-search-input").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            buscarChiste();
        }
    });
    </script>';

    return $html;
}
add_shortcode('frase_chuck', 'shortcode_chuck_buscador');