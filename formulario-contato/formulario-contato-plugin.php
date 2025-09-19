<?php
/**
 * Plugin Name: Formulário de Contato [Shortcode]
 * Plugin URI: https://dinizvisual.com/contato/
 * Description: Formulário de contato nativo com shortcode, máscara de campos e captcha simples.
 * Version: 2.0
 * Author: Pedro Diniz
 * Author URI: https://dinizvisual.com/contato/
 */

if (!defined('ABSPATH')) exit;

// Admin bar item
add_action('admin_bar_menu', function ($wp_admin_bar) {
    if (!current_user_can('manage_options')) return;
    $wp_admin_bar->add_node([
        'id'    => 'contato_plugin',
        'title' => 'Contato',
        'href'  => admin_url('admin.php?page=contato_config')
    ]);
}, 100);

// Menu admin
add_action('admin_menu', function () {
    add_menu_page(
        'Contato',
        'Contato',
        'manage_options',
        'contato_config',
        'render_contato_config_page',
        'dashicons-email',
        60
    );
});

// Página de configuração
function render_contato_config_page() {
    if (isset($_POST['contato_save'])) {
        update_option('contato_plugin_email', sanitize_email($_POST['email_destino']));
        echo '<div class="updated"><p>Configurações salvas com sucesso.</p></div>';
    }

    $email = get_option('contato_plugin_email', get_bloginfo('admin_email'));

    echo '<div class="wrap">';
    echo '<h1>Configuração do Formulário de Contato</h1>';
    echo '<form method="post">';
    echo '<label>Email de destino:</label><br>';
    echo '<input type="email" name="email_destino" value="' . esc_attr($email) . '" required style="width: 300px;"><br><br>';
    echo '<button type="submit" name="contato_save" class="button-primary">Salvar</button>';
    echo '</form>';

    echo '<hr>';
    echo '<h2>Use este shortcode para exibir o formulário:</h2>';
    echo '<input type="text" readonly value="[formulario_contato]" style="width:300px; font-weight:bold;" onclick="this.select();">';
    echo '<p>Clique no campo para copiar.</p>';
    echo '</div>';
}


// Scripts (jQuery Mask)
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], null, true);
    wp_add_inline_script('jquery-mask', "
        jQuery(function($){
            $('#cnpj').mask('00.000.000/0000-00');
            $('#telefone').mask('(00) 00000-0000');
        });
    ");
    wp_enqueue_style('contato-form-style', plugins_url('style.css', __FILE__));
});

// Shortcode do formulário nativo
add_shortcode('formulario_contato', function () {
    ob_start();

    $erro = '';
    $sucesso = '';
    $captcha_num1 = rand(1, 9);
    $captcha_num2 = rand(1, 9);

    // Processa envio
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_contato_nonce']) && wp_verify_nonce($_POST['form_contato_nonce'], 'enviar_form_contato')) {
        $nome      = sanitize_text_field($_POST['nome']);
        $sobrenome = sanitize_text_field($_POST['sobrenome']);
        $cnpj      = sanitize_text_field($_POST['cnpj']);
        $telefone  = sanitize_text_field($_POST['telefone']);
        $email     = sanitize_email($_POST['email']);
        $captcha   = intval($_POST['captcha']);
        $captcha_real = intval($_POST['captcha_num1']) + intval($_POST['captcha_num2']);

        if ($captcha !== $captcha_real) {
            $erro = 'Captcha incorreto. Tente novamente.';
        } else {
            $destino = get_option('contato_plugin_email', get_bloginfo('admin_email'));
            $assunto = "Novo contato recebido";
            $mensagem = "Nome: $nome $sobrenome\nCNPJ: $cnpj\nTelefone: $telefone\nEmail: $email";

            wp_mail($destino, $assunto, $mensagem);

            $sucesso = 'Mensagem enviada com sucesso!';
        }
    }

    if ($erro) {
        echo '<div class="contato-msg erro" style="color:red;">' . esc_html($erro) . '</div>';
    }
    if ($sucesso) {
        echo '<div class="contato-msg sucesso" style="color:green;">' . esc_html($sucesso) . '</div>';
    }
    ?>

    <form method="post" class="contato-form">
        <?php wp_nonce_field('enviar_form_contato', 'form_contato_nonce'); ?>
        
        <label>Nome *</label>
        <input type="text" name="nome" required>

        <label>Sobrenome *</label>
        <input type="text" name="sobrenome" required>

        <label>CNPJ *</label>
        <input type="text" name="cnpj" id="cnpj" required>

        <label>Telefone *</label>
        <input type="text" name="telefone" id="telefone" required>

        <label>Email *</label>
        <input type="email" name="email" required>

        <label>Captcha: Quanto é <?php echo $captcha_num1 . " + " . $captcha_num2; ?> ? *</label>
        <input type="number" name="captcha" required>
        <input type="hidden" name="captcha_num1" value="<?php echo $captcha_num1; ?>">
        <input type="hidden" name="captcha_num2" value="<?php echo $captcha_num2; ?>">

        <button type="submit">Enviar</button>
    </form>
    <?php
    return ob_get_clean();
});
