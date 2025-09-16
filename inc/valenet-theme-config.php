<?php
// ========== ADICIONA PÁGINA AO MENU ==========
add_action('admin_menu', function () {
    add_menu_page(
        'Configurações do Tema',
        'Conf. Tema',
        'manage_options',
        'valenet_config',
        'valenet_render_config_page',
        'dashicons-admin-generic',
        2
    );
});

// ========== SANITIZAÇÃO ==========
// Aceita HEX (#fff ou #ffffff) e RGBA (rgba(255,255,255,0.5))
function valenet_sanitize_color_alpha($value) {
    $value = trim((string)$value);

    // HEX (3 ou 6)
    if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $value)) {
        return $value;
    }

    // RGBA
    if (preg_match('/^rgba\(\s*([01]?\d?\d|2[0-4]\d|25[0-5])\s*,\s*([01]?\d?\d|2[0-4]\d|25[0-5])\s*,\s*([01]?\d?\d|2[0-4]\d|25[0-5])\s*,\s*(0|0?\.\d+|1(\.0+)?)\s*\)$/', $value)) {
        return $value;
    }

    // rgb() -> converte para rgba com alpha 1
    if (preg_match('/^rgb\(\s*([01]?\d?\d|2[0-4]\d|25[0-5])\s*,\s*([01]?\d?\d|2[0-4]\d|25[0-5])\s*\)$/', $value, $m)) {
        return "rgba({$m[1]}, {$m[2]},  {$m[3]}, 1)";
    }

    return ''; // inválido
}

// ========== REGISTRA CONFIGURAÇÕES ==========
add_action('admin_init', function () {
    $fields = [
        // Logos
        'valenet_logo_topo'              => 'sanitize_text_field',
        'valenet_logo_footer'            => 'sanitize_text_field',

        // Tipografia e layout
        'valenet_fonte'                  => 'sanitize_text_field',
        'valenet_tamanho_titulo'         => 'intval',
        'valenet_tamanho_texto'          => 'intval',
        'valenet_botao_raio'             => 'intval',
        'valenet_font_weight_global'     => 'sanitize_text_field',
        'valenet_font_weight_title'      => 'sanitize_text_field',
        'valenet_font_weight_body'       => 'sanitize_text_field',

        // Cores - Top bar
        'valenet_topbar_bg'              => 'valenet_sanitize_color_alpha',
        'valenet_topbar_link'            => 'valenet_sanitize_color_alpha',
        'valenet_topbar_active'          => 'valenet_sanitize_color_alpha',

        // Cores - Menu
        'valenet_menu_color'             => 'valenet_sanitize_color_alpha',
        'valenet_menu_hover'             => 'valenet_sanitize_color_alpha',
        'valenet_menu_active'            => 'valenet_sanitize_color_alpha',

        // Cores - Rodapé
        'valenet_footer_bg'              => 'valenet_sanitize_color_alpha',
        'valenet_footer_text_color'      => 'valenet_sanitize_color_alpha',
        'valenet_footer_title_color'     => 'valenet_sanitize_color_alpha',

        // Cores / Site (Conf. Site)
        'valenet_btn_bg'                 => 'valenet_sanitize_color_alpha',
        'valenet_btn_hover'              => 'valenet_sanitize_color_alpha',
        'valenet_btn_text'               => 'valenet_sanitize_color_alpha',
        'valenet_btn_text_hover'         => 'valenet_sanitize_color_alpha',
        'valenet_text_color'             => 'valenet_sanitize_color_alpha',
        'valenet_valor_plano_color'      => 'valenet_sanitize_color_alpha',
        'valenet_title_global_color'     => 'valenet_sanitize_color_alpha',

        // Textos – Botões
        'valenet_btn_planos_text'        => 'sanitize_text_field',
        'valenet_cta_text_primary'       => 'sanitize_text_field',
        'valenet_cta_text_secondary'     => 'sanitize_text_field',
        'valenet_footer_btn_text'        => 'sanitize_text_field',

        // Telefones
        'valenet_telefone_fixo'          => 'sanitize_text_field',
        'valenet_whatsapp'               => 'sanitize_text_field',

        // Redes sociais
        'valenet_social_facebook'        => 'esc_url_raw',
        'valenet_social_instagram'       => 'esc_url_raw',
        'valenet_social_linkedin'        => 'esc_url_raw',
        'valenet_social_youtube'         => 'esc_url_raw',
        'valenet_social_twitter'         => 'esc_url_raw',
    ];

    foreach ($fields as $key => $callback) {
        register_setting('valenet_config_group', $key, ['sanitize_callback' => $callback]);
    }
});

/**
 * Paleta de cores com nomes (usada em Conf. Site)
 */
function valenet_named_colors_palette() {
    return [
        'rgba(13,110,253,1)'  => 'Azul Primário',
        'rgba(11,94,215,1)'   => 'Azul Escuro',
        'rgba(220,53,69,1)'   => 'Vermelho',
        'rgba(25,135,84,1)'   => 'Verde',
        'rgba(255,193,7,1)'   => 'Amarelo',
        'rgba(33,37,41,1)'    => 'Preto',
        'rgba(85,85,85,1)'    => 'Cinza',
        'rgba(248,249,250,1)' => 'Cinza Claro',
        'rgba(255,255,255,1)' => 'Branco',
        'rgba(0,0,0,1)'       => 'Preto Puro',
    ];
}

/**
 * Helper para renderizar: Select (com nomes) + Color Picker
 */
function valenet_render_named_color_field($label, $name, $default) {
    $palette = valenet_named_colors_palette();
    $current = get_option($name, $default);
    ?>
    <div class="named-color-field">
        <label><span class="field-label"><?php echo esc_html($label); ?></span></label>
        <select class="color-select" data-target="<?php echo esc_attr($name); ?>">
            <option value="">Personalizado</option>
            <?php foreach ($palette as $value => $text): ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($current, $value); ?>>
                    <?php echo esc_html($text); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" class="color-picker"
               id="<?php echo esc_attr($name); ?>"
               name="<?php echo esc_attr($name); ?>"
               value="<?php echo esc_attr($current); ?>"
               data-default-color="<?php echo esc_attr($default); ?>">
    </div>
    <?php
}

// ========== RENDERIZA A PÁGINA ==========
function valenet_render_config_page() { ?>
    <div class="wrap">
        <h1>Configurações do Tema Valenet</h1>
        <?php settings_errors(); ?>

        <!-- Abas -->
        <h2 class="nav-tab-wrapper">
            <a href="#tab_logos" class="nav-tab">Logos</a>
            <a href="#tab_site" class="nav-tab">Conf. Site</a>
            <a href="#tab_topbar" class="nav-tab">Cor Top Bar</a>
            <a href="#tab_menu" class="nav-tab">Cor Menu</a>
            <a href="#tab_botoes" class="nav-tab">Botões</a>
            <a href="#tab_footer" class="nav-tab">Rodapé</a>
            <a href="#tab_telefone" class="nav-tab">Telefone</a>
            <a href="#tab_redes" class="nav-tab">Redes Sociais</a>
        </h2>

        <form method="post" action="options.php">
            <?php settings_fields('valenet_config_group'); ?>

            <!-- LOGOS -->
            <div class="tab-content" id="tab_logos">
                <table class="form-table">
                    <tr>
                        <th>Logo Topo</th>
                        <td>
                            <input type="text" name="valenet_logo_topo" id="valenet_logo_topo" value="<?php echo esc_attr(get_option('valenet_logo_topo')); ?>" />
                            <input type="button" class="button" value="Selecionar" onclick="openMediaUploader('valenet_logo_topo')">
                            <?php if ($url = get_option('valenet_logo_topo')): ?>
                                <img src="<?php echo esc_url($url); ?>" style="max-height:40px;margin-left:10px;">
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Logo Rodapé</th>
                        <td>
                            <input type="text" name="valenet_logo_footer" id="valenet_logo_footer" value="<?php echo esc_attr(get_option('valenet_logo_footer')); ?>" />
                            <input type="button" class="button" value="Selecionar" onclick="openMediaUploader('valenet_logo_footer')">
                            <?php if ($url = get_option('valenet_logo_footer')): ?>
                                <img src="<?php echo esc_url($url); ?>" style="max-height:40px;margin-left:10px;">
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- CONF. SITE (em BLOCOS) -->
            <div class="tab-content" id="tab_site">
                <div class="site-grid">

                    <!-- Bloco: Tipografia -->
                    <div class="site-card">
                        <h3>Tipografia</h3>
                        <label>Fonte
                            <select name="valenet_fonte">
                                <?php foreach (['Inter','Montserrat','Gotham','Nunito'] as $fonte): ?>
                                    <option value="<?php echo esc_attr($fonte); ?>" <?php selected(get_option('valenet_fonte'), $fonte); ?>>
                                        <?php echo esc_html($fonte); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>Peso da Fonte (Global)
                            <select name="valenet_font_weight_global">
                                <?php foreach (['300'=>'Light','400'=>'Normal','500'=>'Medium','600'=>'Semibold','700'=>'Bold','800'=>'Extra Bold'] as $val => $label): ?>
                                    <option value="<?php echo $val; ?>" <?php selected(get_option('valenet_font_weight_global', '400'), $val); ?>>
                                        <?php echo "$label ($val)"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>Peso dos Títulos
                            <select name="valenet_font_weight_title">
                                <?php foreach (['300'=>'Light','400'=>'Normal','500'=>'Medium','600'=>'Semibold','700'=>'Bold','800'=>'Extra Bold'] as $val => $label): ?>
                                    <option value="<?php echo $val; ?>" <?php selected(get_option('valenet_font_weight_title', '700'), $val); ?>>
                                        <?php echo "$label ($val)"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>Peso do Texto
                            <select name="valenet_font_weight_body">
                                <?php foreach (['300'=>'Light','400'=>'Normal','500'=>'Medium','600'=>'Semibold','700'=>'Bold','800'=>'Extra Bold'] as $val => $label): ?>
                                    <option value="<?php echo $val; ?>" <?php selected(get_option('valenet_font_weight_body', '400'), $val); ?>>
                                        <?php echo "$label ($val)"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <!-- Bloco: Tamanhos -->
                    <div class="site-card">
                        <h3>Tamanhos</h3>
                        <label>Tamanho Título
                            <select name="valenet_tamanho_titulo">
                                <?php foreach ([20, 24, 28, 32, 36] as $size): ?>
                                    <option value="<?php echo $size; ?>" <?php selected(get_option('valenet_tamanho_titulo'), $size); ?>>
                                        <?php echo $size; ?>px
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>Tamanho Texto
                            <select name="valenet_tamanho_texto">
                                <?php foreach ([12, 14, 16, 18] as $size): ?>
                                    <option value="<?php echo $size; ?>" <?php selected(get_option('valenet_tamanho_texto'), $size); ?>>
                                        <?php echo $size; ?>px
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <!-- Bloco: Layout -->
                    <div class="site-card">
                        <h3>Layout</h3>
                        <label>Raio Botão
                            <select name="valenet_botao_raio">
                                <?php foreach ([0, 4, 8, 12, 16, 9999] as $raio): ?>
                                    <option value="<?php echo $raio; ?>" <?php selected(get_option('valenet_botao_raio'), $raio); ?>>
                                        <?php echo $raio; ?>px
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <!-- Bloco: Cores – Botões -->
                    <div class="site-card">
                        <h3>Cores – Botões</h3>
                        <?php
                            valenet_render_named_color_field('Cor do Botão', 'valenet_btn_bg', 'rgba(13,110,253,1)');
                            valenet_render_named_color_field('Cor do Botão (Hover)', 'valenet_btn_hover', 'rgba(11,94,215,1)');
                            valenet_render_named_color_field('Cor do Texto do Botão', 'valenet_btn_text', 'rgba(255,255,255,1)');
                            valenet_render_named_color_field('Cor do Texto do Botão (Hover)', 'valenet_btn_text_hover', 'rgba(255,255,255,1)');
                        ?>
                    </div>

                    <!-- Bloco: Cores – Texto do Site -->
                    <div class="site-card">
                        <h3>Cores – Texto</h3>
                        <?php
                            valenet_render_named_color_field('Cor do Texto do Site', 'valenet_text_color', 'rgba(51,51,51,1)');
                            valenet_render_named_color_field('Cor do Título Global', 'valenet_title_global_color', 'rgba(33,37,41,1)');
                            valenet_render_named_color_field('Cor do Valor Plano', 'valenet_valor_plano_color', 'rgba(0,0,0,1)');
                        ?>
                    </div>

                </div>
            </div>

            <!-- COR TOP BAR -->
            <div class="tab-content" id="tab_topbar">
                <table class="form-table">
                    <tr><th>Cor de Fundo</th><td><input type="text" class="color-picker" name="valenet_topbar_bg" value="<?php echo esc_attr(get_option('valenet_topbar_bg', 'rgba(248,249,250,1)')); ?>" data-default-color="rgba(248,249,250,1)"></td></tr>
                    <tr><th>Cor do Link</th><td><input type="text" class="color-picker" name="valenet_topbar_link" value="<?php echo esc_attr(get_option('valenet_topbar_link', 'rgba(51,51,51,1)')); ?>" data-default-color="rgba(51,51,51,1)"></td></tr>
                    <tr><th>Cor do Link Ativo</th><td><input type="text" class="color-picker" name="valenet_topbar_active" value="<?php echo esc_attr(get_option('valenet_topbar_active', 'rgba(255,0,0,1)')); ?>" data-default-color="rgba(255,0,0,1)"></td></tr>
                </table>
            </div>

            <!-- COR MENU -->
            <div class="tab-content" id="tab_menu">
                <table class="form-table">
                    <tr><th>Cor do Menu</th><td><input type="text" class="color-picker" name="valenet_menu_color" value="<?php echo esc_attr(get_option('valenet_menu_color', 'rgba(51,51,51,1)')); ?>" data-default-color="rgba(51,51,51,1)"></td></tr>
                    <tr><th>Cor do Hover</th><td><input type="text" class="color-picker" name="valenet_menu_hover" value="<?php echo esc_attr(get_option('valenet_menu_hover', 'rgba(85,85,85,1)')); ?>" data-default-color="rgba(85,85,85,1)"></td></tr>
                    <tr><th>Cor do Ativo</th><td><input type="text" class="color-picker" name="valenet_menu_active" value="<?php echo esc_attr(get_option('valenet_menu_active', 'rgba(0,0,0,1)')); ?>" data-default-color="rgba(0,0,0,1)"></td></tr>
                </table>
            </div>

            <!-- TELEFONE -->
            <div class="tab-content" id="tab_telefone">
                <table class="form-table">
                    <tr>
                        <th>Telefone Fixo</th>
                        <td>
                            <input type="text" name="valenet_telefone_fixo" value="<?php echo esc_attr(get_option('valenet_telefone_fixo')); ?>" placeholder="(XX) XXXX-XXXX" />
                        </td>
                    </tr>
                    <tr>
                        <th>WhatsApp</th>
                        <td>
                            <input type="text" name="valenet_whatsapp" value="<?php echo esc_attr(get_option('valenet_whatsapp')); ?>" placeholder="(XX) XXXXX-XXXX" />
                        </td>
                    </tr>
                </table>
            </div>

            <!-- REDES SOCIAIS -->
            <div class="tab-content" id="tab_redes">
                <table class="form-table">
                    <tr><th colspan="2"><h3>Selecione e insira os links das redes sociais</h3></th></tr>
                    <?php
                    $redes = ['facebook', 'instagram', 'linkedin', 'youtube', 'twitter'];
                    foreach ($redes as $rede): ?>
                        <tr>
                            <th><label for="valenet_social_<?php echo $rede; ?>"><?php echo ucfirst($rede); ?></label></th>
                            <td>
                                <input type="text" name="valenet_social_<?php echo $rede; ?>" id="valenet_social_<?php echo $rede; ?>"
                                    value="<?php echo esc_attr(get_option('valenet_social_' . $rede)); ?>"
                                    placeholder="https://<?php echo $rede; ?>.com/seu-perfil" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- BOTÕES (apenas textos) -->
            <div class="tab-content" id="tab_botoes">
                <table class="form-table">
                    <tr><th colspan="2"><h3>Botões planos</h3></th></tr>
                    <tr>
                        <th>ID botões planos</th>
                        <td>
                            <input type="text" name="valenet_btn_planos_text" value="<?php echo esc_attr(get_option('valenet_btn_planos_text')); ?>" placeholder="EX.:ID botões planos" />
                        </td>
                    </tr>

                    <tr><th colspan="2"><h3>Botões CTA</h3></th></tr>
                    <tr>
                        <th>Texto CTA (ID 01)</th>
                        <td>
                            <input type="text" name="valenet_cta_text_primary" value="<?php echo esc_attr(get_option('valenet_cta_text_primary')); ?>" placeholder="EX.:ID botões planos" />
                        </td>
                    </tr>
                    <tr>
                        <th>Texto CTA (ID 02)</th>
                        <td>
                            <input type="text" name="valenet_cta_text_secondary" value="<?php echo esc_attr(get_option('valenet_cta_text_secondary')); ?>" placeholder="EX.:ID botões planos" />
                        </td>
                    </tr>

                    <tr><th colspan="2"><h3>Botão footer</h3></th></tr>
                    <tr>
                        <th>ID CTA Footer</th>
                        <td>
                            <input type="text" name="valenet_footer_btn_text" value="<?php echo esc_attr(get_option('valenet_footer_btn_text')); ?>" placeholder="EX.:ID botões planos" />
                        </td>
                    </tr>
                </table>
            </div>

            <!-- RODAPÉ -->
            <div class="tab-content" id="tab_footer">
                <table class="form-table">
                    <tr>
                        <th>Cor de Fundo</th>
                        <td><input type="text" class="color-picker" name="valenet_footer_bg"
                            value="<?php echo esc_attr(get_option('valenet_footer_bg', 'rgba(34,34,34,1)')); ?>"
                            data-default-color="rgba(34,34,34,1)"></td>
                    </tr>
                    <tr>
                        <th>Cor do Texto</th>
                        <td><input type="text" class="color-picker" name="valenet_footer_text_color"
                            value="<?php echo esc_attr(get_option('valenet_footer_text_color', 'rgba(255,255,255,1)')); ?>"
                            data-default-color="rgba(255,255,255,1)"></td>
                    </tr>
                    <tr>
                        <th>Cor do Título</th>
                        <td><input type="text" class="color-picker" name="valenet_footer_title_color"
                            value="<?php echo esc_attr(get_option('valenet_footer_title_color', 'rgba(255,255,255,1)')); ?>"
                            data-default-color="rgba(255,255,255,1)"></td>
                    </tr>
                </table>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>

    <!-- Estilos -->
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; margin-top: 20px; }
        .nav-tab-active { background: #fff; border-bottom: 1px solid transparent; }

        /* Tabelas padrão (outras abas) */
        .form-table td { display: flex; align-items: center; gap: 1rem; }
        .form-table input[type="text"] { min-width: 280px; }
        .form-table h3 { margin: 8px 0 0; }

        /* ====== BLOCOS na aba Conf. Site ====== */
        #tab_site .site-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(220px, 1fr));
            gap: 20px;
        }
        #tab_site .site-card {
            background: #fff;
            border: 1px solid #dcdcde;
            border-radius: 6px;
            padding: 16px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        #tab_site .site-card h3 {
            margin: 0 0 12px;
            font-size: 14px;
            font-weight: 600;
        }
        #tab_site label {
            display: block;
            margin-bottom: 10px;
        }
        #tab_site select,
        #tab_site input[type="text"] {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        #tab_site .named-color-field { margin-bottom: 12px; }
        #tab_site .named-color-field .field-label { display:block; margin-bottom:6px; font-weight:600; }

        /* Responsivo */
        @media (max-width: 1500px) { #tab_site .site-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 1100px) { #tab_site .site-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 700px)  { #tab_site .site-grid { grid-template-columns: 1fr; } }
    </style>

    <!-- Tabs + Color Picker + Sincronização -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabs = document.querySelectorAll(".nav-tab");
            const contents = document.querySelectorAll(".tab-content");

            function activateTab(hash) {
                tabs.forEach(t => t.classList.remove("nav-tab-active"));
                contents.forEach(c => c.classList.remove("active"));
                document.querySelector(hash)?.classList.add("active");
                document.querySelector(`.nav-tab[href='${hash}']`)?.classList.add("nav-tab-active");
                localStorage.setItem("valenet_active_tab", hash);
            }

            tabs.forEach(t => t.addEventListener("click", function(e) {
                e.preventDefault();
                activateTab(this.getAttribute("href"));
            }));

            activateTab(localStorage.getItem("valenet_active_tab") || "#tab_logos");

            // Inicializa o wpColorPicker com transparência (alpha) e paleta
            jQuery(function($) {
                $('.color-picker').wpColorPicker({
                    alpha: true,
                    palettes: ['#000000', '#ffffff', '#0d6efd', '#0b5ed7', '#ffc107', '#212529', '#333333', '#555555']
                });

                // Sincroniza select (nome da cor) <-> color picker
                $('.color-select').on('change', function(){
                    const target = '#' + $(this).data('target');
                    const val = $(this).val();
                    if (val) {
                        $(target).wpColorPicker('color', val);
                        $(target).val(val).trigger('change');
                    }
                });
                $('.color-picker').on('change', function(){
                    const val = $(this).val();
                    const select = $('.color-select[data-target="'+this.id+'"]');
                    if (select.length) {
                        if (select.find('option[value="'+val+'"]').length) {
                            select.val(val);
                        } else {
                            select.val(''); // Personalizado
                        }
                    }
                });
            });
        });
    </script>
<?php }

// ========== MEDIA UPLOADER ==========
add_action('admin_footer', function () {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'toplevel_page_valenet_config') return;
    ?>
    <script>
        function openMediaUploader(fieldId) {
            const customUploader = wp.media({
                title: "Selecionar imagem",
                button: { text: "Usar esta imagem" },
                multiple: false
            }).on("select", function () {
                const attachment = customUploader.state().get("selection").first().toJSON();
                document.getElementById(fieldId).value = attachment.url;
            }).open();
        }
    </script>
    <?php
});

// ========== ENQUEUE MEDIA, COLOR PICKER E ALPHA ==========
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_valenet_config') {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // Suporte a transparência no wpColorPicker (alpha slider)
        wp_enqueue_script(
            'wp-color-picker-alpha',
            'https://cdnjs.cloudflare.com/ajax/libs/wp-color-picker-alpha/3.0.2/wp-color-picker-alpha.min.js',
            ['wp-color-picker'],
            '3.0.2',
            true
        );
    }
});
