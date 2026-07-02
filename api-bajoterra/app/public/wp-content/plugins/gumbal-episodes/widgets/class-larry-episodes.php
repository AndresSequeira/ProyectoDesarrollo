<?php
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Elementor_Widget_Larry_Episodes extends Widget_Base {

//sección para mostrar el widget en el apartado de widgets de Elementor
    public function get_name() {
        return 'larry_episodes';
    }

    public function get_title() {
        return __('Larry Episodes', 'elementor-custom-widgets');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['custom_widgets_category'];
    }

    //interfáz de interacción con el widget
    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Configuración General', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Título', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Episodios con Larry', 'elementor-custom-widgets'),
                'placeholder' => __('Ej: Episodios destacados', 'elementor-custom-widgets'),
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columnas', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_control(
            'max_episodes',
            [
                'label' => __('Episodios a mostrar', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 9,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filters',
            [
                'label' => __('Filtros', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
//sección de filtros por barra de busqueda
        $this->add_control(
            'show_search',
            [
                'label' => __('Barra de búsqueda', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Mostrar', 'elementor-custom-widgets'),
                'label_off' => __('Ocultar', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
//sección de filtros por temporada
        $this->add_control(
            'show_season_filter',
            [
                'label' => __('Filtro por temporada', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Mostrar', 'elementor-custom-widgets'),
                'label_off' => __('Ocultar', 'elementor-custom-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

//sección de estilos de controles
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Estilos', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Color del título', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .larry-episodes-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label' => __('Color de acento', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => '#C32E23',
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Fondo de tarjeta', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'card_text_color',
            [
                'label' => __('Color de texto', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1e293b',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Borde redondeado', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 40, 'step' => 1],
                ],
                'default' => ['size' => 12, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();
    }
//sección de edición de contenido del widget
    private function get_episodes() {
        return [
            [
                'title' => 'El Trabajo',
                'season' => '1',
                'year' => '2011',
                'rating' => '4.2',
                'desc' => 'Richard consigue un trabajo en el supermercado de Elmore para pagar una multa. Larry, el cajero, termina haciendo todo el trabajo mientras Richard solo causa problemas.',
                'img' => 'https://i.pinimg.com/1200x/bf/13/2e/bf132e5c9a1e8d88bcfc702adb31049f.jpg',
            ],
            [
                'title' => 'Los Caballeros',
                'season' => '1',
                'year' => '2011',
                'rating' => '4.0',
                'desc' => 'Gumball y Darwin quieren convertirse en caballeros medievales para impresionar a una chica. Terminan en una feria renacentista donde Larry es el dragón.',
                'img' => 'https://i.pinimg.com/736x/e1/4b/5f/e14b5fe6771995ed04a149fdd4636c2e.jpg',
            ],
            [
                'title' => 'El Reembolso',
                'season' => '2',
                'year' => '2012',
                'rating' => '4.5',
                'desc' => 'Después de comprar un videojuego defectuoso, Gumball y Darwin intentan devolverlo. Larry se niega y comienza una guerra de bromas entre ellos.',
                'img' => 'https://i.pinimg.com/1200x/3c/e4/50/3ce4500b4224a1c1816d1db4e51c67ea.jpg',
            ],
            [
                'title' => 'El Santo',
                'season' => '2',
                'year' => '2012',
                'rating' => '4.3',
                'desc' => 'Larry intenta ser una buena persona después de que Gumball le hace sentir culpable por su actitud negativa. Pero ser bueno resulta más difícil de lo que parece.',
                'img' => 'https://i.pinimg.com/1200x/bc/39/3b/bc393b7ca4ddc02ce2252d480c6e470c.jpg',
            ],
            [
                'title' => 'El Coloso',
                'season' => '2',
                'year' => '2012',
                'rating' => '4.1',
                'desc' => 'Gumball descubre que Hector, un amigo gigante, no puede controlar su fuerza. Terminan en el arcade donde Larry intenta mantener el orden.',
                'img' => 'https://i.pinimg.com/1200x/7a/27/b8/7a27b8863d2b881193def0ade17a9ab8.jpg',
            ],
            [
                'title' => 'La Receta',
                'season' => '2',
                'year' => '2012',
                'rating' => '4.0',
                'desc' => 'Gumball intenta cocinar una receta especial y termina yendo al supermercado varias veces. Larry, el cajero, se frustra cada vez que Gumball vuelve a la fila.',
                'img' => 'https://i.pinimg.com/736x/89/7c/e7/897ce7bafceb6d60199efecea1d551d0.jpg',
            ],
        ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $episodes = $this->get_episodes();
        $max = (int) $settings['max_episodes'];
        $columns = (int) $settings['columns'];

        if ($max > 0) {
            $episodes = array_slice($episodes, 0, $max);
        }

        $accent = esc_attr($settings['accent_color']);
        $card_bg = esc_attr($settings['card_bg_color']);
        $card_text = esc_attr($settings['card_text_color']);
        $radius = esc_attr($settings['border_radius']['size'] . $settings['border_radius']['unit']);

        ?>
        <div class="larry-episodes-widget"
             style="--larry-accent: <?php echo $accent; ?>; --larry-card-bg: <?php echo $card_bg; ?>; --larry-text-color: <?php echo $card_text; ?>; --larry-border-radius: <?php echo $radius; ?>; --larry-columns: <?php echo $columns; ?>;">

            <div class="larry-episodes-header">
                <?php if (!empty($settings['title'])) : ?>
                    <h2 class="larry-episodes-title"><?php echo esc_html($settings['title']); ?></h2>
                <?php endif; ?>

                <div class="larry-episodes-controls">
                    <?php if ($settings['show_search'] === 'yes') : ?>
                        <input type="text" class="larry-search-input" placeholder="Buscar episodio..." aria-label="Buscar episodios">
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($settings['show_season_filter'] === 'yes') : ?>
                <div class="larry-season-filters">
                    <button class="larry-season-btn is-active" data-season="all">Todas</button>
                    <?php
                    $seasons = array_unique(array_column($episodes, 'season'));
                    sort($seasons);
                    foreach ($seasons as $s) : ?>
                        <button class="larry-season-btn" data-season="<?php echo esc_attr($s); ?>">Temporada <?php echo esc_html($s); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="larry-episodes-grid" role="list">
                <?php foreach ($episodes as $ep) : ?>
                    <?php $img_url = !empty($ep['img']) ? $ep['img'] : ''; ?>
                    <div class="larry-episode-card"
                         role="listitem"
                         data-season="<?php echo esc_attr($ep['season']); ?>"
                         data-title="<?php echo esc_attr($ep['title']); ?>"
                         data-desc="<?php echo esc_attr($ep['desc']); ?>"
                         data-year="<?php echo esc_attr($ep['year']); ?>"
                         data-rating="<?php echo esc_attr($ep['rating']); ?>"
                         data-img="<?php echo esc_attr($img_url); ?>"
                         tabindex="0"
                         role="button"
                         aria-label="<?php echo esc_attr($ep['title']); ?>">

                        <?php if ($img_url) : ?>
                            <img class="card-image" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($ep['title']); ?>">
                        <?php else : ?>
                            <div class="card-image" style="background: linear-gradient(135deg, <?php echo $accent; ?> 0%, #e85a4a 100%); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.3); font-size: 3rem;">
                                <?php echo esc_html(substr($ep['title'], 0, 2)); ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <span class="card-season">Temporada <?php echo esc_html($ep['season']); ?></span>
                            <h3 class="card-title"><?php echo esc_html($ep['title']); ?></h3>
                            <p class="card-desc"><?php echo esc_html($ep['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="larry-no-results" style="display:none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <p>No se encontraron episodios con esos criterios.</p>
                </div>
            </div>

            <!-- Modal -->
            <div class="larry-modal-overlay" role="dialog" aria-modal="true" aria-label="Detalles del episodio">
                <div class="larry-modal">
                    <button class="larry-modal-close" aria-label="Cerrar">&times;</button>
                    <img class="larry-modal-img" src="" alt="">
                    <div class="larry-modal-content">
                        <span class="modal-season-badge">Temporada</span>
                        <h2>Título</h2>
                        <div class="modal-meta">
                            <span class="meta-year"></span>
                            <span class="meta-rating"></span>
                        </div>
                        <p class="modal-desc">Descripción</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
