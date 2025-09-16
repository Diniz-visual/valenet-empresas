<!-- Nossos numeros -->

<?php if ( have_rows( 'nossos_numeros_b2b' ) ) : ?>
<section class="stats-section py-5">
  <div class="container">

   <div class="numeros-header">
        <h2 class="nossos-numeros" >Nossos Números</h2>
   



    <div class="row g-4 stats-grid">

	<?php while ( have_rows( 'nossos_numeros_b2b' ) ) : the_row();

        // Valor sanitizado: aceita "1.200" e vira 1200
        $valor_raw = (string) get_sub_field('valor');
        $valor     = (int) preg_replace('/\D/', '', $valor_raw);

        $legenda   = get_sub_field('legenda');           // texto
        $compacto  = (bool) get_sub_field('compacto');   // true/false
        $icone     = get_sub_field('icone');             // URL opcional
        $sufixo    = get_sub_field('sufixo');            // ex.: "º"
        $prefixo   = get_sub_field('prefixo');           // ex.: "+", "R$", "" (opcional)
      ?>
        <!-- 1 coluna no mobile, 3 no tablet, 6 no desktop -->
        <div class="col-12 col-md-4 col-lg-2">
          <div class="stat-card">

            <?php if ($icone): ?>
              <div class="stat-icon">
                <img src="<?php echo esc_url($icone); ?>" alt="" loading="lazy" decoding="async">
              </div>
            <?php endif; ?>

            <div
              class="stat-value"
              data-target="<?php echo esc_attr($valor); ?>"
              data-compact="<?php echo $compacto ? 'true' : 'false'; ?>"
              <?php if (!empty($sufixo))  : ?> data-suffix="<?php  echo esc_attr($sufixo);  ?>"<?php endif; ?>
              <?php if (!empty($prefixo)) : ?> data-prefix="<?php echo esc_attr($prefixo); ?>"<?php endif; ?>
            >0</div>

            <?php if ($legenda): ?>
              <div class="stat-label"><?php echo esc_html($legenda); ?></div>
            <?php endif; ?>

          </div>
        </div>
      <?php endwhile; ?>

    </div>
  </div>
</section>





<?php endif; ?>
