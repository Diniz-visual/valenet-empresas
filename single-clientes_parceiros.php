<?php
/* Template: Single Clientes Parceiros */

get_header(); 

// Breadcrumb
get_template_part('template-parts/breadcrumb');
?>

<main class="conteudo-single-clientes">

    <?php
    // Imagem do banner (ACF): pode ser URL, ID ou Array
    $banner = get_field('imagem_banner_clientes');

    if ($banner) {
        if (is_array($banner)) {
            // Array retornado pelo ACF
            $url = $banner['url'] ?? '';
            $alt = $banner['alt'] ?? get_the_title();
            $id  = $banner['ID'] ?? 0;

            if ($id) {
                echo wp_get_attachment_image($id, 'full', false, [
                    'class' => 'image-clientes img-fluid w-100',
                    'alt'   => $alt,
                    'loading' => 'lazy',
                    'decoding' => 'async',
                ]);
            } elseif ($url) {
                echo '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '" class="image-clientes img-fluid w-100" loading="lazy" decoding="async">';
            }

        } elseif (is_numeric($banner)) {
            // Apenas ID
            $alt = get_post_meta($banner, '_wp_attachment_image_alt', true) ?: get_the_title();
            echo wp_get_attachment_image($banner, 'full', false, [
                'class' => 'image-clientes img-fluid w-100',
                'alt'   => $alt,
                'loading' => 'lazy',
                'decoding' => 'async',
            ]);

        } elseif (is_string($banner)) {
            // URL direta
            echo '<img src="' . esc_url($banner) . '" alt="' . esc_attr(get_the_title()) . '" class="image-clientes img-fluid w-100" loading="lazy" decoding="async">';
        }

    } elseif (has_post_thumbnail()) {
        // Fallback: imagem destacada
        the_post_thumbnail('full', [
            'class' => 'image-clientes img-fluid w-100',
            'alt'   => esc_attr(get_the_title()),
            'loading' => 'lazy',
            'decoding' => 'async',
        ]);
    }
    ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <h1 class="titulo-single-cliente"><?php the_title(); ?></h1>

        <div class="descricao-single-cliente">
            <?php the_content(); ?>
        </div>

        <?php if ($site = get_field('link_cliente')): ?>
            <p class="link-cliente">
                <a href="<?php echo esc_url($site); ?>" target="_blank" rel="noopener noreferrer">
                    Visite o site do cliente
                </a>
            </p>
        <?php endif; ?>

    <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
