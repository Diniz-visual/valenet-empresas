<?php
/* Template Name: Grandes Contas */
get_header('grandes-contas'); ?>

<!-- Hero Section Grandes Contas -->

<?php if ( have_rows('hero') ) : ?>
  <?php while ( have_rows('hero') ) : the_row();

    // Imagens
    $img_desktop = get_sub_field('imagem_desktop_grandes_contas');
    $img_mobile  = get_sub_field('imagem_mobile_grandes_contas');

    // Conteúdo
    $titulo    = get_sub_field('titulo_grandes_contas');
    $descricao = get_sub_field('descricao_grandes_contas');

    // -------- Overlay (checkbox ou grupo) --------
    $overlay_field = get_sub_field('overlay'); // bool/checkbox OU array (grupo)
    $ov_enabled = false;
    $ov_color   = '#000000';
    $ov_op_raw  = 0.35;

    if (is_array($overlay_field)) {
      $ov_enabled = !empty($overlay_field['enabled']) || !empty($overlay_field['ativar']) || !empty($overlay_field['on']);
      if (!empty($overlay_field['color']))   $ov_color  = $overlay_field['color'];
      if (!empty($overlay_field['cor']))     $ov_color  = $overlay_field['cor'];
      if (isset($overlay_field['opacity']))  $ov_op_raw = $overlay_field['opacity'];
      if (isset($overlay_field['opacidade']))$ov_op_raw = $overlay_field['opacidade'];
    } else {
      $ov_enabled = (bool) $overlay_field;
    }

    if (is_string($ov_op_raw)) $ov_op_raw = str_replace(',', '.', $ov_op_raw);
    $ov_opacity = is_numeric($ov_op_raw) ? (float) $ov_op_raw : 0.35;
    if ($ov_opacity > 1) $ov_opacity = $ov_opacity / 100;
    $ov_opacity = max(0, min(1, $ov_opacity));

    // -------- Cores do título/texto (repeater cores_hero opcional) --------
    $cor_titulo = null;
    $cor_texto  = null;
    if ( have_rows('cores_hero') ) {
      while ( have_rows('cores_hero') ) { the_row();
        $cor_titulo = get_sub_field('cor_titulo_hero') ?: $cor_titulo;
        $cor_texto  = get_sub_field('cor_texto_hero')  ?: $cor_texto;
      }
      // se precisar reusar, poderia dar reset_rows();
    }
    $cor_titulo = $cor_titulo ?: '#ffffff';
    $cor_texto  = $cor_texto  ?: '#ffffff';

    // -------- CTA (pega o primeiro válido) --------
    $cta = null;
    if ( have_rows('cta_hero') ) {
      while ( have_rows('cta_hero') ) { the_row();
        $texto = get_sub_field('texto_cta_grandes_contas');
        $link  = get_sub_field('link_grandes_contas');
        if ($texto && $link) {
          $cta = [
            'texto' => $texto,
            'link'  => $link,
            'bg'    => get_sub_field('cor_cta_grandes_contas')        ?: '#ffffff',
            'bg_h'  => get_sub_field('cor_cta_grandes_contas_hover')  ?: '#e9e9e9',
            'fg'    => get_sub_field('cor_texto_grandes_contas')      ?: '#111111',
          ];
          break;
        }
      }
    }

    $alt = $titulo ?: 'Hero Grandes Contas';
  ?>
    <section class="hero-topo" aria-label="Hero Grandes Contas">
      <div class="hero-grandes-contas">
        <div
          class="slide-home-grandes-contas"
          style="
            --overlay-opacity: <?php echo $ov_enabled ? esc_attr($ov_opacity) : '0'; ?>;
            --slide-text-color: <?php echo esc_attr($cor_texto); ?>;
          "
        >
          <div class="media-wrapper">
            <picture>
              <?php if ($img_mobile): ?>
                <source media="(max-width: 768px)" srcset="<?php echo esc_url($img_mobile); ?>">
              <?php endif; ?>
              <?php if ($img_desktop): ?>
                <img src="<?php echo esc_url($img_desktop); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy">
              <?php endif; ?>
            </picture>
          </div>

          <div class="conteudo-grandes-contas">
            <?php if ($titulo): ?>
              <h2 class="title-slide" style="color: <?php echo esc_attr($cor_titulo); ?>">
                <?php echo esc_html($titulo); ?>
              </h2>
            <?php endif; ?>

            <?php if ($descricao): ?>
              <p class="descricao-slide" style="color: <?php echo esc_attr($cor_texto); ?>">
                <?php echo esc_html($descricao); ?>
              </p>
            <?php endif; ?>

            <?php if ($cta): ?>
              <a class="btn"
                 href="<?php echo esc_url($cta['link']); ?>"
                 style="
                   --btn-bg: <?php echo esc_attr($cta['bg']); ?>;
                   --btn-hover: <?php echo esc_attr($cta['bg_h']); ?>;
                   --btn-text: <?php echo esc_attr($cta['fg']); ?>;
                   --btn-text-hover: <?php echo esc_attr($cta['fg']); ?>;
                   background: var(--btn-bg);
                   color: var(--btn-text);
                 ">
                <?php echo esc_html($cta['texto']); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  <?php endwhile; ?>
<?php endif; ?>



<!-- Seção 01 Site -->

<?php if ( have_rows( 'secao_01' ) ) : ?>
  <?php while ( have_rows( 'secao_01' ) ) : the_row(); 

    $titulo     = get_sub_field( 'titulo_section_01' );
    $descricao  = get_sub_field( 'descricao_section_01' );
    $cta_text   = get_sub_field( 'cta_section_01' );
    $cta_link   = get_sub_field( 'link_cta_01' );
    $imagem     = get_sub_field( 'imagem_cta_01' );
  ?>
  
  <section class="secao-01 feature-two-col">
    <div class="container">
      <div class="section-01 row align-items-center gy-5">
        
        <!-- Imagem -->
        <div class="col-lg-6">
          <div class="media-card">
            <?php if ( $imagem ) : ?>
              <img src="<?php echo esc_url( $imagem['url'] ); ?>" alt="<?php echo esc_attr( $imagem['alt'] ); ?>" class="media-img">
            <?php endif; ?>
            </span>
          </div>
        </div>

        <!-- Texto -->
        <div class="col-lg-6">
          <div class="conteudo-section-01">
             <span class="sub-title-01"><?php the_sub_field( 'sub_titulo_01' ); ?></span>
            <?php if ( $titulo ) : ?>
              <h2 class="section-01-title"><?php echo esc_html( $titulo ); ?></h2>
            <?php endif; ?>

            <?php if ( $descricao ) : ?>
              <p class="section-01-desc"><?php echo esc_html( $descricao ); ?></p>
            <?php endif; ?>

            <?php if ( $cta_text && $cta_link ) : ?>
              <a href="<?php echo esc_url( $cta_link ); ?>" class="btn-pill">
                <?php echo esc_html( $cta_text ); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php endwhile; ?>
<?php endif; ?>


<!-- Seção 02 Site --> 

<?php if ( have_rows('secao_02') ) : ?>
  <?php while ( have_rows('secao_02') ) : the_row();

    // Campos ACF
    $eyebrow    = get_sub_field('eyebrow_secao_02');
    $titulo     = get_sub_field('titulo_section_02');
    $descricao  = get_sub_field('descricao_section_02');
    $cta_text   = get_sub_field('cta_section_02');
    $cta_link   = get_sub_field('link_cta_02');
    $imagem     = get_sub_field('imagem_secao_02');

    // Verificação: só renderiza se houver conteúdo relevante
    if ( $titulo || $descricao || $cta_text || $imagem ) :
  ?>

  <section class="secao-02 feature-two-col is-reverse">
    <div class="container">
      <div class="row align-items-center gy-5">

        <!-- Texto (esquerda) -->
        <div class="col-lg-6">
            <div class="conteudo-section-02">
            <?php if ( $eyebrow ) : ?>
              <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <?php endif; ?>

            <?php if ( $titulo ) : ?>
              <h2 class="section-02-title"><?php echo wp_kses_post($titulo); ?></h2>
            <?php endif; ?>

            <?php if ( $descricao ) : ?>
              <p class="section-02-desc"><?php echo esc_html($descricao); ?></p>
            <?php endif; ?>

            <?php if ( $cta_text ) : ?>
              <a href="<?php echo esc_url( $cta_link ?: '#' ); ?>" class="btn-pill">
                <?php echo esc_html($cta_text); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Imagem (direita) -->
        <div class="col-lg-6">
          <div class="media-card media-right">
            <?php if ( $imagem ) : ?>
              <img src="<?php echo esc_url($imagem['url']); ?>"
                   alt="<?php echo esc_attr($imagem['alt'] ?? ''); ?>"
                   class="media-img"
                   loading="lazy" decoding="async">
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php endif; // fim verificação ?>
  <?php endwhile; ?>
<?php endif; ?>


<!-- Seção 03 Site -->
<?php if (have_rows('secao_03')) : ?>
    <?php while (have_rows('secao_03')) : the_row();
        $sub_titulo = get_sub_field('sub_titulo_03');
        $titulo = get_sub_field('titulo_section_03');
        $descricao = get_sub_field('descricao_section_03');
        $cta_texto = get_sub_field('cta_section_03');
        $cta_link = get_sub_field('link_cta_03');
        $imagem = get_sub_field('imagem_secao_03');

        // Verifica se ao menos um campo essencial foi preenchido
        if ($sub_titulo || $titulo || $descricao || ($cta_texto && $cta_link) || $imagem) :
    ?>
          <section class="secao-03 feature-two-col is-reverse">
            <div class="container">
                <div class="row align-items-center gy-5">

                    <!-- Imagem -->
                    <?php if ($imagem): ?>
                    <div class="col-lg-6">
                        <div class="media-card">
                            <img src="<?php echo esc_url($imagem['url']); ?>"
                                 alt="<?php echo esc_attr($imagem['alt']); ?>" 
                                 class="media-img">
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Conteúdo -->
                    <div class="col-lg-6">
                        <div class="conteudo-section-03">
                            <?php if ($sub_titulo): ?>
                            <span class="sub-title-03"><?php the_sub_field( 'sub_titulo_03' ); ?></span>
                              <?php endif; ?>

                            <?php if ($titulo): ?>
                                <h2 class="section-03-title"><?php echo esc_html($titulo); ?></h2>
                            <?php endif; ?>

                            <?php if ($descricao): ?>
                                <p class="section-03-desc"><?php echo esc_html($descricao); ?></p>
                            <?php endif; ?>

                            <?php if ($cta_texto && $cta_link): ?>
                                <a href="<?php echo esc_url($cta_link); ?>" class="btn-pill-dark">
                                    <?php echo esc_html($cta_texto); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <?php
        endif; // fim do if de verificação
    endwhile; ?>
<?php endif; ?>

<!-------------------------------------------------------------
Nossos clientes
-------------------------------------------------------------->

 <?php get_template_part( 'template-parts/nossos-cliente' ); ?>

 <!-------------------------------------------------------------
Nossos clientes
-------------------------------------------------------------->

 <?php get_template_part( 'template-parts/nossos-numeros' ); ?>


<!-------------------------------------------------------------
Section Formulario de contato
-------------------------------------------------------------->
<?php if (have_rows('secao_formulario')) : ?>
  <?php while (have_rows('secao_formulario')) : the_row(); 
    $titulo         = get_sub_field('titulo_form_sectios');
    $imagem         = get_sub_field('imagem_form_section');
    $form_shortcode = trim((string) get_sub_field('id_formulario'));

    // Fallback: se o campo do ACF estiver vazio, usa o shortcode nativo do plugin
    if (!$form_shortcode) {
      $form_shortcode = '[formulario_contato]';
    }

    // Só renderiza se houver pelo menos um conteúdo
    if ($titulo || $imagem || $form_shortcode):
  ?>
  <section class="contact-section">
    <div class="contact-container">

      <!-- Lado esquerdo: imagem + info -->
      <div class="contact-left">
        <?php if ($imagem): ?>
          <img src="<?php echo esc_url($imagem['url']); ?>" 
               alt="<?php echo esc_attr($imagem['alt']); ?>" 
               class="contact-image">
        <?php endif; ?>


      </div> <!-- ✅ FECHA contact-left -->

      <!-- Lado direito: conteúdo e formulário -->
      <div class="contact-right">
        <h2 class="titulo-forms">Contato</h2>

        <?php if ($titulo): ?>
          <h2><?php echo esc_html($titulo); ?></h2>
        <?php endif; ?>

        <div class="acf-form-wrapper">
          <?php echo do_shortcode($form_shortcode); ?>
        </div>
      </div>

    </div>
  </section>
  <?php endif; ?>
  <?php endwhile; ?>
<?php endif; ?>



<!-- Footer Site --> 

  <?php get_footer(); ?>








