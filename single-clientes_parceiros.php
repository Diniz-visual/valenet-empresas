<?php
/* Single Clientes Parceiros */
get_header(); ?>

<main class="conteudo-single-clientes">

    <?php
    // Campo ACF (pode ser URL, ID ou Array)
    $banner = get_field('imagem_banner_clientes');

    if ($banner) {
      if (is_array($banner)) {
        // Retorno = Array
        $url = $banner['url'] ?? '';
        $alt = $banner['alt'] ?? '';
        $id  = $banner['ID'] ?? 0;

        if ($id) {
          echo wp_get_attachment_image(
            $id,
            'full',
            false,
            [
              'class' => 'image-clientes img-fluid w-100',
              'alt'   => $alt ?: get_the_title(),
              'loading' => 'lazy',
              'decoding' => 'async',
            ]
          );
        } elseif ($url) {
          ?>
          <img src="<?php echo esc_url($url); ?>"
               alt="<?php echo esc_attr($alt ?: get_the_title()); ?>"
               class="image-clientes img-fluid w-100"
               loading="lazy" decoding="async">
          <?php
        }
      } elseif (is_numeric($banner)) {
        // Retorno = ID
        $alt = get_post_meta($banner, '_wp_attachment_image_alt', true) ?: get_the_title();
        echo wp_get_attachment_image(
          $banner,
          'full',
          false,
          [
            'class' => 'image-clientes img-fluid w-100',
            'alt'   => $alt,
            'loading' => 'lazy',
            'decoding' => 'async',
          ]
        );
      } elseif (is_string($banner)) {
        // Retorno = URL
        ?>
        <img src="<?php echo esc_url($banner); ?>"
             alt="<?php echo esc_attr(get_the_title()); ?>"
             class="image-clientes img-fluid w-100"
             loading="lazy" decoding="async">
        <?php
      }
    } elseif (has_post_thumbnail()) {
      // Fallback: usar imagem destacada
      the_post_thumbnail('full', [
        'class' => 'image-clientes img-fluid w-100',
        'alt'   => esc_attr(get_the_title()),
        'loading' => 'lazy',
        'decoding' => 'async',
      ]);
    }
    ?>
    <!-- titulo -->
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
