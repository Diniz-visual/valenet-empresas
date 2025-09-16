<!-- Nossos clientes -->
<?php
$q = new WP_Query([
  'post_type'      => 'clientes_parceiros',
  'posts_per_page' => -1,
  'orderby'        => 'title',
  'order'          => 'ASC',
]);

if ($q->have_posts()) : ?>
<section class="clientes-parceiros my-5">
  <div class="container">
    <h2 class="titulo-clientes">Nossos Clientes</h2>
    <p class="descricao-clientes">
      Esses são os clientes e empresas que confiam na internet dos mineiros.
    </p>

    <div class="swiper clientes-parceiros-swiper">
      <div class="swiper-wrapper">
        <?php while ($q->have_posts()) : $q->the_post(); ?>
          <div class="swiper-slide">
            <div class="logo-wrap">
              <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) : 
                  $alt = esc_attr(get_the_title());
                  the_post_thumbnail('medium', [
                    'class'   => 'logo-img',
                    'alt'     => $alt,
                    'loading' => 'lazy',
                    'decoding'=> 'async',
                  ]);
                else : ?>
                  <span class="logo-fallback"><?php echo esc_html(get_the_title()); ?></span>
                <?php endif; ?>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <!-- <div class="swiper-pagination"></div> -->
    </div>
  </div>
</section>
<?php
wp_reset_postdata();
endif;
?>
