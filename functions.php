<?php
// ==========================
// SUPORTE AO TEMA
// ==========================
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('custom-logo');
add_theme_support('title-tag');


// ==========================
// REGISTRAR MENUS
// ==========================
add_action('after_setup_theme', function () {
    register_nav_menus([
        'menu-principal' => __('Menu Principal', 'valenet'),
        'menu-footer'    => __('Menu Rodapé', 'valenet'),
        'menu-topbar'    => __('Menu Top Bar', 'valenet'),
        'grandes-contas-menu'   => __('Menu Grandes Contas', 'valenet'), // ✅ ESSENCIAL


    ]);
});


// ==========================
// ENQUEUE CSS/JS PÚBLICO
// ==========================
add_action('wp_enqueue_scripts', function () {
    // Bootstrap
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], '5.3.3');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], '5.3.3', true);

    // Estilo do tema
    wp_enqueue_style('valenet-style', get_stylesheet_uri(), ['bootstrap']);

    // JS do tema (se existir)

     // Swiper CSS + Core
    wp_enqueue_style('swiper-css', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', [], '11.0.0');
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', [], '11.0.0', true);

    // Swiper Home
    wp_enqueue_script('swiper-home', get_template_directory_uri() . '/assets/js/swiper-home.js', ['swiper-js'], null, true);

    // Swiper Planos B2B
    wp_enqueue_script('swiper-planos', get_template_directory_uri() . '/assets/js/planos-b2b-swiper.js', ['swiper-js'], null, true);

    // Swiper Clientes

    wp_enqueue_script('swiper-clientes', get_template_directory_uri() . '/assets/js/swiper-clientes.js', ['swiper-js'], '1.0', true);

      // Swiper Soluções

    wp_enqueue_script('swiper-solucoes', get_template_directory_uri() . '/assets/js/swiper-solucoes.js', ['swiper-js'], '1.0', true);

    // Contador

    wp_enqueue_script('counters', get_template_directory_uri() . '/assets/js/counters.js', [],  '1.0', true);



});


// ==========================
// NAVWALKER (se você usa Bootstrap navwalker)
// ==========================
if (file_exists(get_template_directory() . '/inc/class-bootstrap-navwalker.php')) {
    require_once get_template_directory() . '/inc/class-bootstrap-navwalker.php';
}

// ==========================
// PAINEL DE CONFIG DO TEMA (com abas)
// ==========================
require_once get_template_directory() . '/inc/valenet-theme-config.php';

// ==========================
// CSS DINÂMICO baseado nas opções salvas
// ==========================

add_action('wp_head', function () {
    // Conf. Site
    $fonte          = get_option('valenet_fonte', 'Inter');
    $titulo_tam     = get_option('valenet_tamanho_titulo', '24');
    $texto_tam      = get_option('valenet_tamanho_texto', '16');
    $raio_botao     = get_option('valenet_botao_raio', '4');
    $fw_global      = get_option('valenet_font_weight_global', '400');
    $fw_title       = get_option('valenet_font_weight_title', '700');
    $fw_body        = get_option('valenet_font_weight_body', '400');
    $text_color     = get_option('valenet_text_color', 'rgba(51,51,51,1)');
    $valor_plano_color = get_option('valenet_valor_plano_color', 'rgba(0,0,0,1)');
    $title_global   = get_option('valenet_title_global_color', 'rgba(33,37,41,1)'); // ✅ novo

    // Cores
    $btn_bg         = get_option('valenet_btn_bg', '#0d6efd');
    $btn_hover      = get_option('valenet_btn_hover', '#0b5ed7');
    $btn_text       = get_option('valenet_btn_text', '#ffffff');
    $btn_text_hover = get_option('valenet_btn_text_hover', '#ffffff');

    $topbar_bg      = get_option('valenet_topbar_bg', '#f8f9fa');
    $topbar_link    = get_option('valenet_topbar_link', '#333333');
    $topbar_active  = get_option('valenet_topbar_active', '#ff0000');

    $menu_color     = get_option('valenet_menu_color', '#333333');
    $menu_hover     = get_option('valenet_menu_hover', '#555555');
    $menu_active    = get_option('valenet_menu_active', '#000000');

    $footer_bg          = get_option('valenet_footer_bg', '#222222');
    $footer_text_color  = get_option('valenet_footer_text_color', '#ffffff');

    $cor_primaria   = get_option('valenet_cor_primaria', '#0d6efd');
    $cor_secundaria = get_option('valenet_cor_secundaria', '#6c757d');
    ?>
    <style>
      :root {
        --site-font: "<?php echo esc_html($fonte); ?>", sans-serif;
        --site-title-size: <?php echo (int) $titulo_tam; ?>px;
        --site-text-size: <?php echo (int) $texto_tam; ?>px;
        --site-btn-radius: <?php echo (int) $raio_botao; ?>px;

        --site-text-color: <?php echo esc_html($text_color); ?>;             /* ✅ usa este */
        --valor-plano-color: <?php echo esc_html($valor_plano_color); ?>;
        --title-global-color: <?php echo esc_html($title_global); ?>;         /* ✅ novo */

        --font-weight-global: <?php echo esc_html($fw_global); ?>;
        --font-weight-title: <?php echo esc_html($fw_title); ?>;
        --font-weight-body: <?php echo esc_html($fw_body); ?>;

        --site-primary: <?php echo esc_html($cor_primaria); ?>;
        --site-secondary: <?php echo esc_html($cor_secundaria); ?>;

        /* Top Bar */
        --topbar-bg: <?php echo esc_html($topbar_bg); ?>;
        --topbar-link: <?php echo esc_html($topbar_link); ?>;
        --topbar-active: <?php echo esc_html($topbar_active); ?>;

        /* Menu principal */
        --menu-link-color: <?php echo esc_html($menu_color); ?>;
        --menu-hover-color: <?php echo esc_html($menu_hover); ?>;
        --menu-active-color: <?php echo esc_html($menu_active); ?>;

        /* Rodapé */
        --footer-bg: <?php echo esc_html($footer_bg); ?>;
        --footer-text: <?php echo esc_html($footer_text_color); ?>;

        /* Botões */
        --btn-bg: <?php echo esc_html($btn_bg); ?>;
        --btn-hover: <?php echo esc_html($btn_hover); ?>;
        --btn-text: <?php echo esc_html($btn_text); ?>;
        --btn-text-hover: <?php echo esc_html($btn_text_hover); ?>;
      }

      body {
        font-family: var(--site-font);
        font-size: var(--site-text-size);
        font-weight: var(--font-weight-body);
        color: var(--site-text-color) !important; /* ✅ corrigido */
      }

      h1, h2, h3, h4, h5 {
        font-size: var(--site-title-size);
        font-weight: var(--font-weight-title);
        color: var(--title-global-color) !important; /* ✅ usa a opção */
      }

      a { color: var(--site-primary); transition: color .3s ease; }
      a:hover { color: var(--site-secondary); }

      .btn .btn-pill {
        background-color: var(--btn-bg) !important;
        border-radius: var(--site-btn-radius);
        color: var(--btn-text) !important;
        border: none !important;
      }
     .btn-pill:hover {
        background-color: #00beff !important;
        color: #1d1d1d !important;
        border: none !important;
      }

      /* Top Bar */
      .topbar { background: var(--topbar-bg) !important; padding: 5px 0 !important; }
      .topbar .nav-link {
        color: var(--topbar-link) !important;
        padding: 4px 10px;
        border-radius: 4px;
        transition: background-color .3s ease, color .3s ease;
      }
      .topbar .nav-link:hover,
      .topbar .nav-link.active,
      .topbar .current-menu-item > .nav-link {
        background-color: var(--topbar-active) !important;
        color: var(--topbar-bg) !important;
        font-weight: 600;
      }


      .tipo-conexao {
        font-size: 0.9rem;                 /* menor que título normal */
        font-weight: 600;                  /* destaque */
        color: #00beff !important;         /* cinza elegante (independente da global) */
        display: inline-block;
        border-radius: 4px;
        margin-top: 30px !important;
        margin-bottom: 5px !important;
}

/* ================= NAVIGATION LINKS ================= */

.nav-link {
  color: var(--menu-link-color);
  text-decoration: none;
  font-weight: medium;
  padding: 8px 12px;
  display: inline-block;
  transition: color 0.3s ease, background-color 0.3s ease;
}

.nav-link:hover {
  color: var(--menu-hover-color);
  background-color: transparent;
}

.menu-grandes-contas-nav .nav-link.active,
.menu-grandes-contas-nav .current-menu-item > .nav-link {
  color: var(--menu-active-color) !important;
  font-weight: medium !important;
  background-color: transparent;
}

/* ================= MENU GRANDES CONTAS ================= */
.menu-grandes-contas-nav .nav-link {
  color: var(--menu-link-color);
  font-weight: 500;
  transition: color 0.3s ease;
  padding: 8px 16px;
}

.menu-grandes-contas-nav .nav-link:hover {
  color: var(--menu-hover-color);
}

.menu-grandes-contas-nav .nav-link.active,
.menu-grandes-contas-nav .current-menu-item > .nav-link {
  color: var(--menu-active-color) !important;
  font-weight: 600 !important;
}



    </style>
    <?php
});


// ==========================
// ADICIONA CLASSES AOS LINKS DO MENU TOPBAR
// ==========================
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    if (empty($args->theme_location) || $args->theme_location !== 'menu-topbar') {
        return $atts;
    }
    $atts['class'] = isset($atts['class']) ? $atts['class'] : '';
    $classes = trim($atts['class'] . ' nav-link dropdown-item');
    $classes = implode(' ', array_unique(array_filter(explode(' ', $classes))));
    $atts['class'] = $classes;
    return $atts;
}, 10, 3);

// ==========================
// CUSTOM POST TYPE: Planos B2B
// ==========================
add_action('init', function () {
    $labels = [
        'name'                  => 'Planos B2B',
        'singular_name'         => 'Plano B2B',
        'menu_name'             => 'Planos B2B',
        'name_admin_bar'        => 'Plano B2B',
        'add_new'               => 'Adicionar Novo',
        'add_new_item'          => 'Adicionar Novo Plano',
        'new_item'              => 'Novo Plano',
        'edit_item'             => 'Editar Plano',
        'view_item'             => 'Ver Plano',
        'all_items'             => 'Todos os Planos',
        'search_items'          => 'Buscar Planos',
        'not_found'             => 'Nenhum plano encontrado',
        'not_found_in_trash'    => 'Nenhum plano na lixeira',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true,
    ];

    register_post_type('planos_b2b', $args);
});

// ==========================
// TAXONOMIA: Categorias de Planos
// ==========================
add_action('init', function () {
    $labels = [
        'name'              => 'Categorias de Planos',
        'singular_name'     => 'Categoria de Plano',
        'search_items'      => 'Buscar Categorias',
        'all_items'         => 'Todas Categorias',
        'parent_item'       => 'Categoria Pai',
        'parent_item_colon' => 'Categoria Pai:',
        'edit_item'         => 'Editar Categoria',
        'update_item'       => 'Atualizar Categoria',
        'add_new_item'      => 'Adicionar Nova Categoria',
        'new_item_name'     => 'Nova Categoria',
        'menu_name'         => 'Categorias',
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'categoria-plano'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('categoria_plano', ['planos_b2b'], $args);
});


// ========== CUSTOM POST TYPE: Nossas Soluções ==========
add_action('init', function () {
    $labels = [
        'name'               => 'Nossas Soluções',
        'singular_name'      => 'Solução',
        'menu_name'          => 'Nossas Soluções',
        'name_admin_bar'     => 'Solução',
        'add_new'            => 'Adicionar Nova',
        'add_new_item'       => 'Adicionar Nova Solução',
        'new_item'           => 'Nova Solução',
        'edit_item'          => 'Editar Solução',
        'view_item'          => 'Ver Solução',
        'all_items'          => 'Todas as Soluções',
        'search_items'       => 'Buscar Soluções',
        'parent_item_colon'  => 'Solução Pai:',
        'not_found'          => 'Nenhuma solução encontrada.',
        'not_found_in_trash' => 'Nenhuma solução encontrada na lixeira.'
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-lightbulb', // ícone do menu no admin
        'query_var'          => true,
        'rewrite'            => ['slug' => 'nossas-solucoes'],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => true,
        'menu_position'      => 6,
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true, // compatível com Gutenberg/REST API
    ];

    register_post_type('nossas_solucoes', $args);

    // ====== TAXONOMIA (opcional): Categorias para as Soluções ======
    $tax_labels = [
        'name'          => 'Categorias de Soluções',
        'singular_name' => 'Categoria de Solução',
        'search_items'  => 'Buscar Categorias',
        'all_items'     => 'Todas Categorias',
        'edit_item'     => 'Editar Categoria',
        'update_item'   => 'Atualizar Categoria',
        'add_new_item'  => 'Adicionar Nova Categoria',
        'new_item_name' => 'Novo nome de Categoria',
        'menu_name'     => 'Categorias'
    ];

    register_taxonomy('categoria_solucoes', 'nossas_solucoes', [
        'hierarchical'      => true,
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'categoria-solucoes'],
        'show_in_rest'      => true,
    ]);
});



// ========== CPT: Nossos Clientes e Parceiros ==========
add_action('init', function () {
    $labels = [
        'name'               => 'Nossos Clientes e Parceiros',
        'singular_name'      => 'Cliente/Parceiro',
        'menu_name'          => 'Clientes & Parceiros',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Cliente/Parceiro',
        'new_item'           => 'Novo Cliente/Parceiro',
        'edit_item'          => 'Editar Cliente/Parceiro',
        'view_item'          => 'Ver Cliente/Parceiro',
        'all_items'          => 'Todos',
        'search_items'       => 'Buscar',
        'not_found'          => 'Nenhum item encontrado.',
        'not_found_in_trash' => 'Nenhum item na lixeira.'
        
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,         // agora é público
        'publicly_queryable' => true,         // acessível via URL
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-groups',
        'has_archive'        => true,         // permite archive
        'rewrite'            => ['slug' => 'clientes-parceiros'], // URL customizada
        'supports'           => ['title', 'editor', 'thumbnail'], // permite editor
        'show_in_rest'       => true,         // habilita Gutenberg
        'menu_position'      => 7,

    ];

    register_post_type('clientes_parceiros', $args);

    // Taxonomia opcional
    register_taxonomy('tipo_relacao', 'clientes_parceiros', [
        'hierarchical'      => true,
        'labels'            => [
            'name'          => 'Tipos de Relação',
            'singular_name' => 'Tipo de Relação',
        ],
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'tipo-relacao'],
        'show_in_rest'      => true,
    ]);
});


// ========== SUPORTE A WIDGETS NO FOOTER ==========

function valenet_register_footer_widgets() {
    // área 1
    register_sidebar([
        'name'          => 'Widgets do Rodapé 01',
        'id'            => 'footer_widgets_1',
        'description'   => 'Primeira coluna de widgets no rodapé',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ]);

    // área 2
    register_sidebar([
        'name'          => 'Widgets do Rodapé 02',
        'id'            => 'footer_widgets_2',
        'description'   => 'Segunda coluna de widgets no rodapé',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'valenet_register_footer_widgets');

// ========== SUPORTE A WIDGETS NO FOOTER ==========

// Formata telefone brasileiro (fixo ou celular)
function valenet_format_phone($number) {
    // remove tudo que não for número
    $digits = preg_replace('/\D+/', '', $number);

    // celular com 11 dígitos: (XX) 9XXXX-XXXX
    if (strlen($digits) === 11) {
        return sprintf("(%s) %s %s-%s",
            substr($digits, 0, 2),  // DDD
            substr($digits, 2, 1),  // dígito 9
            substr($digits, 3, 4),  // primeiros 4
            substr($digits, 7, 4)   // últimos 4
        );
    }

    // fixo com 10 dígitos: (XX) XXXX-XXXX
    if (strlen($digits) === 10) {
        return sprintf("(%s) %s-%s",
            substr($digits, 0, 2),  // DDD
            substr($digits, 2, 4),  // primeiros 4
            substr($digits, 6, 4)   // últimos 4
        );
    }

    // se não bater, retorna o original
    return $number;
}

// ========== GRANDES CONTAS E GOVERNOS ==========


function cpt_grandes_contas_governo() {
    $labels = array(
        'name'               => 'Grandes contas e governo',
        'singular_name'      => 'Grande conta ou governo',
        'menu_name'          => 'Grandes contas e governo',
        'name_admin_bar'     => 'Grande conta ou governo',
        'add_new'            => 'Adicionar nova',
        'add_new_item'       => 'Adicionar nova grande conta',
        'new_item'           => 'Nova grande conta',
        'edit_item'          => 'Editar grande conta',
        'view_item'          => 'Ver grande conta',
        'all_items'          => 'Todas as grandes contas',
        'search_items'       => 'Buscar grandes contas',
        'not_found'          => 'Nenhuma grande conta encontrada',
        'not_found_in_trash' => 'Nenhuma grande conta na lixeira'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'grandes-contas-governo'),
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'          => 'dashicons-businessperson', // Ícone do menu (opcional)
        'show_in_rest'       => true, // Ativa suporte ao Gutenberg
        'menu_position'      => 5,

    );

    register_post_type('grandes_contas', $args);
}
add_action('init', 'cpt_grandes_contas_governo');


// ========== Cor Slider ACF ==========

add_action('wp_head', function () {
    if (have_rows('cores_slider', 'option')) {
        // só pega a primeira linha, caso use repeater
        while (have_rows('cores_slider', 'option')) {
            the_row();
            $cor_titulo      = get_sub_field('cor_titulo_slider');
            $cor_texto       = get_sub_field('cor_texto_slider');
            $cor_botao       = get_sub_field('cor_botao_slider');
            $cor_botao_h     = get_sub_field('cor_botao_hover_slider');
            $cor_texto_bt    = get_sub_field('cor_texto_botao_slider');
            $borda_cta_topo  = get_sub_field('borda_cta_slider');
            $cor_texto_h   = get_sub_field('cor_texto_botao_slider_hover');



        }

        // garante unidade px na borda se for número
        if (is_numeric($borda_cta_topo)) {
            $borda_cta_topo .= 'px';
        }

        

        ?>



        <style>

            /* ================= NAVIGATION LINKS ================= */



          :root {
            --slider-title-color: <?php echo esc_attr($cor_titulo ?: '#ffffff'); ?>;
            --slider-text-color: <?php echo esc_attr($cor_texto ?: '#ffffff'); ?>;
            --slider-btn-bg: <?php echo esc_attr($cor_botao ?: '#0d6efd'); ?>;
            --slider-btn-hover: <?php echo esc_attr($cor_botao_h ?: '#0b5ed7'); ?>;
            --slider-btn-text: <?php echo esc_attr($cor_texto_bt ?: '#ffffff'); ?>;
            --slider-btn-bord: <?php echo esc_attr($borda_cta_topo ?: '8px'); ?>;
            --slider-btn-text_h: <?php echo esc_attr($cor_texto_h ?: '#ffffff'); ?>;

          }


         /*======================= 
            breadcrumb
         ==========================*/ 

         .breadcrumb-container {
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    position: relative;
    text-align: center;
    color: #fff;
    overflow: hidden;
    --breadcrumb-bg: none;
    --breadcrumb-overlay: 0.5;
    --breadcrumb-title-color: #ffffff;
    --breadcrumb-nav-color: #ffffff;
}

.breadcrumb-bg {
    position: relative;
    background-color: #092444 !important;            /* fallback padrão */
    background-image: var(--breadcrumb-bg);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 200px;
}

.breadcrumb-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, var(--breadcrumb-overlay));
    display: flex;
    align-items: center;
    justify-content: center;
}

.breadcrumb-content {
    text-align: left;
    padding: 40px 20px;
    max-width: 1140px;
    width: 100%;
    margin: 0 auto;
    z-index: 2;
}

.breadcrumb-title {
    font-size: 2.2em;
    margin-bottom: 10px;
    color: var(--breadcrumb-title-color, #00beff) !important ; /* usa a cor padrão se não tiver no PHP */
    font-weight:700 !important;
}

.breadcrumb-nav {
    display: flex;
    justify-content: left;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
}

.breadcrumb-nav a,
.breadcrumb-nav span {
    color: var(--breadcrumb-nav-color) !important;
    text-decoration: none;
    font-size: 1rem;
}

.breadcrumb-nav a:hover {
    text-decoration: underline;
}

.arrow.material-symbols-outlined,
.breadcrumb-nav .material-symbols-outlined {
  font-family: 'Material Symbols Outlined' !important;
  font-variation-settings:
    'FILL' 0,
    'wght' 400,
    'GRAD' 0,
    'opsz' 20;

  font-size: 18px;
  vertical-align: middle;
  margin: 0 6px;
  color: #00beff !important;
}

.nossas-solucoes.swiper-pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 5px;
  margin-top: 30px !important;  /* distância em relação ao conteúdo acima */
  margin-bottom: 40px;          /* 👈 afasta da parte de baixo do card/slider */
  position: relative;           /* mantém na posição padrão */
  z-index: 10;
}

/* Bullets padrão */
.nossas-solucoes .swiper-pagination-bullet {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: rgba(0, 190, 255, 0.4);
  opacity: 1;
  transition: all 0.3s ease;
}

/* Bullet ativo */
.nossas-solucoes .swiper-pagination-bullet-active {
  width: 28px;
  border-radius: 20px;
  background: #00beff;
}


        </style>
        
        <?php
    }
});


/** Cores Guthemberg padrão tema */

function meu_tema_gutenberg_cores_personalizadas() {
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Titulo', 'meu-tema'),
            'slug'  => 'titulo',
            'color' => '#134888',
        ),
        array(
            'name'  => __('Cor Secundária', 'meu-tema'),
            'slug'  => 'cor-secundaria',
            'color' => '#0e65c3',
        ),
        array(
            'name'  => __('Azul Claro', 'meu-tema'),
            'slug'  => 'azul_claro',
            'color' => '#00beff',
        ),
        array(
            'name'  => __('Texto', 'meu-tema'),
            'slug'  => 'texto',
            'color' => '#1d1d1d',
        ),
    ));
}
add_action('after_setup_theme', 'meu_tema_gutenberg_cores_personalizadas');

  /*======================= 
            Titillium Web Font
         ==========================*/
add_action('wp_enqueue_scripts', function () {
    $fonte = get_option('valenet_fonte');
    if ($fonte === 'Titillium Web') {
        wp_enqueue_style(
            'valenet-font-titillium',
            'https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap',
            false
        );
    }
});

add_action('wp_head', function () {
    $fonte = get_option('valenet_fonte');

    if ($fonte === 'Titillium Web') {
        ?>
        <style>
            body,
            h1, h2, h3, h4, h5, h6,
            p, a, span, button,
            input, select, textarea {
                font-family: 'Titillium Web', sans-serif !important;
            }
        </style>
        <?php
    }
});

// Força a classe nav-link em todos os menus
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    // Adiciona apenas para menus principais e grandes contas
    $locations = ['menu-principal', 'grandes-contas-menu', 'menu-topbar'];
    if (isset($args->theme_location) && in_array($args->theme_location, $locations)) {
        $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'nav-link';
    }
    return $atts;
}, 10, 3);
