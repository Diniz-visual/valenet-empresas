<?php
// Função do Breadcrumb
if (!function_exists('custom_breadcrumb')) {
    function custom_breadcrumb($background_url = '', $opacidade_decimal = 0.5, $cor_titulo = '#ffffff', $cor_navegacao = '#ffffff') {
        if (!is_front_page()) : ?>

            <div class="breadcrumb-container" 
                 style="--breadcrumb-bg: url('<?php echo esc_url($background_url); ?>');
                        --breadcrumb-overlay: <?php echo esc_attr($opacidade_decimal); ?>;
                        --breadcrumb-title-color: <?php echo esc_attr($cor_titulo); ?>;
                        --breadcrumb-nav-color: <?php echo esc_attr($cor_navegacao); ?>;">
                <div class="breadcrumb-bg">
                    <div class="breadcrumb-overlay">
                        <div class="breadcrumb-content">

                            <h1 class="breadcrumb-title">
                                <?php echo esc_html(get_the_title()); ?>
                            </h1>

                            <nav class="breadcrumb-nav">
                                <a href="<?php echo esc_url(home_url()); ?>">Início</a>
                                <span class="arrow material-symbols-outlined">double_arrow</span>

                                <?php if (is_category() || is_single()) :
                                    $category = get_the_category();
                                    if (!empty($category)) : ?>
                                        <a href="<?php echo esc_url(get_category_link($category[0]->term_id)); ?>">
                                            <?php echo esc_html($category[0]->name); ?>
                                        </a>
                                        <span class="arrow material-symbols-outlined">double_arrow</span>
                                    <?php endif;
                                endif; ?>

                                <?php if (is_page() || is_single()) : ?>
                                    <span><?php echo esc_html(get_the_title()); ?></span>
                                <?php endif; ?>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>

        <?php endif;
    }
}

// Puxar campos do ACF (repeater: topo_beneficios)
if (have_rows('topo_beneficios')) :
    while (have_rows('topo_beneficios')) : the_row();

        $imagem        = get_sub_field('imagem_topo_solucoes');
        $cor_titulo    = get_sub_field('cor_titulo_breadcrumb') ?: '#ffffff';
        $cor_navegacao = get_sub_field('cor_navegacao_breadcrumb') ?: '#ffffff';
        $opacidade     = get_sub_field('opacidade_breadcrumb');

        // URL da imagem
        $background_url = $imagem ? esc_url($imagem['url']) : '';

        // Opacidade via RANGE (0–100 → 0.xx)
        if (is_numeric($opacidade)) {
            $opacidade_decimal = max(0, min(100, intval($opacidade))) / 100;
        } else {
            $opacidade_decimal = 0.5; // fallback
        }

        // Renderiza o breadcrumb
        custom_breadcrumb($background_url, $opacidade_decimal, $cor_titulo, $cor_navegacao);

    endwhile;
endif;
?>
