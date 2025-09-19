<?php
/* Template Name: Home */
get_header(); ?>

<!-- Chama os scripts -->
<?php
wp_enqueue_style('swiper'); // bundle CSS
wp_enqueue_script('swiper'); // bundle JS
wp_enqueue_script('swiper-planos'); // seu inicializador (assets/js/swiper-planos.js)
?>

<!-- Slider Home Topo -->
<section class="slider-topo" aria-label="Destaques do site">
  <?php if ( have_rows('imagens', 'option') ) : ?>
    <div class="swiper swiper-home" role="region">
      <div class="swiper-wrapper">

        <?php 
        $slide_index = 0;

        while ( have_rows('imagens', 'option') ) : the_row(); 
          if ( have_rows('slider') ) :
            while ( have_rows('slider') ) : the_row(); 

              // Campos básicos
              $desktop = get_sub_field('imagem_desktop');
              $mobile  = get_sub_field('imagem_mobile');
              $titulo  = get_sub_field('titulo_slider');
              $desc    = get_sub_field('descricao_imagem');
              $btn_txt = get_sub_field('titulo_botao');
              $btn_url = get_sub_field('link_do_botao');

              // ========= OPACIDADE (range ACF) =========
              // Aceita 0–1, 0–100 e "0,5"
              $opacidade_raw = get_sub_field('opacidade_slider');

              if (is_string($opacidade_raw)) {
                $opacidade_raw = str_replace(',', '.', $opacidade_raw);
              }

              $opacidade = is_numeric($opacidade_raw) ? (float)$opacidade_raw : 0.5;

              // Se vier 0–100, converte para 0–1
              if ($opacidade > 1) {
                $opacidade = $opacidade / 100;
              }
              // Garante o intervalo
              $opacidade = max(0, min(1, $opacidade));

              // ========= COR DO TEXTO =========
              // prioridade: por slide > global (opções) > branco
              $cor_texto_slide  = get_sub_field('cor_texto_slider'); 
              $cor_texto_global = get_field('cor_texto_slider', 'option');
              $cor_texto = $cor_texto_slide ? $cor_texto_slide : ($cor_texto_global ? $cor_texto_global : '#ffffff');

              // ========= ACESSIBILIDADE =========
              $alt_text = $titulo ? $titulo : 'Slide do carrossel';
              $lazy = $slide_index === 0 ? '' : 'loading="lazy" decoding="async"';
        ?>

              <div 
                class="swiper-slide slide-home" 
                aria-roledescription="slide" 
                aria-label="<?php echo esc_attr(($slide_index+1)); ?>"
                style="--overlay-opacity: <?php echo esc_attr($opacidade); ?>; --slide-text-color: <?php echo esc_attr($cor_texto); ?>;"
              >
                <div class="media-wrapper">
                  <picture>
                    <?php if ($mobile): ?>
                      <source media="(max-width: 768px)" srcset="<?php echo esc_url($mobile); ?>">
                    <?php endif; ?>
                    <?php if ($desktop): ?>
                      <img 
                        src="<?php echo esc_url($desktop); ?>" 
                        alt="<?php echo esc_attr($alt_text); ?>"
                        <?php echo $lazy; ?>
                        sizes="(max-width: 768px) 100vw, 100vw"
                      >
                    <?php endif; ?>
                  </picture>
                  <!-- overlay aplicado via CSS ::after -->
                </div>

                <?php if ($titulo || $desc || ($btn_txt && $btn_url)) : ?>
                  <div class="conteudo-slide">
                    <?php if ($titulo): ?>
                      <h2 class="title-slide"><?php echo esc_html($titulo); ?></h2>
                    <?php endif; ?>

                    <?php if ($desc): ?>
                      <p class="descricao-slide"><?php echo esc_html($desc); ?></p>
                    <?php endif; ?>

                    <?php if ($btn_txt && $btn_url): ?>
                      <a href="<?php echo esc_url($btn_url); ?>" class="btn" aria-label="<?php echo esc_attr($btn_txt); ?>">
                        <?php echo esc_html($btn_txt); ?>
                      </a>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>

        <?php 
              $slide_index++;
            endwhile; 
          endif; 
        endwhile; 
        ?>
      </div>

      <!-- Controles -->
      <button class="swiper-button-prev" aria-label="Slide anterior"></button>
      <button class="swiper-button-next" aria-label="Próximo slide"></button>
      <div class="swiper-pagination" aria-label="Paginação do slider"></div>
    </div>
  <?php endif; ?>
</section>

<noscript>
  <!-- Fallback simples se JS estiver desativado -->
  <style>
    .swiper, .swiper-wrapper, .swiper-slide {display:block !important}
    .swiper-slide{margin-bottom:1rem}
  </style>
</noscript>

<!-- Planos B2B ----->
<?php
// ----- WP_Query dos planos -----
$current = get_queried_object();
$args = [
  'post_type'      => 'planos_b2b',
  'posts_per_page' => -1,
  'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
];

if (isset($current->taxonomy) && $current->taxonomy === 'categoria_plano') {
  $args['tax_query'] = [[
    'taxonomy' => 'categoria_plano',
    'field'    => 'term_id',
    'terms'    => $current->term_id,
  ]];
}

$q = new WP_Query($args);

// ✅ Só renderiza a <section> se houver posts
if ($q->have_posts()) :

  $offcanvases = [];
  $btn_text_padrao = get_option('valenet_btn_planos_text', 'Saiba mais');
  $modais_sva = [];
  $sva_index  = 0;
?>
  
<section class="planos-b2b-section py-5">
  <div class="container">

    <?php if ($titulo_servicos = get_field('titulo_servicos')) : ?>
      <h2 class="titulo-paginas"><?php echo esc_html($titulo_servicos); ?></h2>
    <?php endif; ?>

    <!-- Swiper slider -->
    <div class="swiper planos-b2b-swiper">
      <div class="swiper-wrapper">

        <?php while ($q->have_posts()) : $q->the_post();

          // Campos ACF
          $valor_plano        = get_field('valor_plano');
          $link_oferta        = get_field('link_oferta');
          $texto_legal        = get_field('texto_legal');
          $tipo_conexao       = get_field('subtitulo_oferta');
          $desc_plano         = get_field('descricao_plano');
          $informacoes_plano  = get_field('informacoes_plano');
          $adicional          = get_field('adicional');
          $plano_personalizado = get_field('plano_personalizado');

          $terms = get_the_terms(get_the_ID(), 'categoria_plano');
          $primary_term = (is_array($terms) && !is_wp_error($terms)) ? reset($terms) : null;

          $offcanvas_id = 'offcanvasInfo_' . get_the_ID();

          if (!empty($informacoes_plano) || !empty($texto_legal)) {
            $offcanvases[] = [
              'id'      => $offcanvas_id,
              'title'   => get_the_title(),
              'content' => $informacoes_plano,
              'legal'   => $texto_legal,
            ];
          }
        ?>
            
        <div class="swiper-slide d-flex">
          <div class="card plano-card shadow-sm w-100 d-flex flex-column rounded-4 border-0 position-relative">
            
            <!-- Badge -->
            <?php if ($primary_term): ?>
              <a class="badge bg-success position-absolute top-0 start-50 translate-middle-x px-3 py-2 rounded-pill text-decoration-none"
                 href="<?php echo esc_url(get_term_link($primary_term)); ?>">
                <?php echo esc_html($primary_term->name); ?>
              </a>
            <?php endif; ?>

            <div class="card-body d-flex flex-column flex-grow-1">

              <!-- Tipo de conexão -->
              <?php if ($tipo_conexao): ?>
                <h6 class="tipo-conexao"><?php echo esc_html($tipo_conexao); ?></h6>
              <?php endif; ?>

              <!-- Velocidade -->
              <?php if (get_field('velocidade_da_oferta')) : ?>
                <h2 class="velocidade-plano">
                  <span class="velocidade"><?php the_field('velocidade_da_oferta'); ?></span>
                </h2>

                <div class="plano-adicional">
                  <?php if ($adicional = trim((string) get_field('adicional'))): ?>
                    <p class="adicional-plano">
                      <span class="material-symbols-outlined no-upper">
                        <svg class="icon-plus" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
</svg>

                      </span>

                      <span class="adicional-texto"><?php echo esc_html($adicional); ?></span>
                    </p>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

                     <!-- Plano personalizado -->
              <?php if ($plano_personalizado): ?>
                <h2 class="plano-personalizado">
                  <span class="personalizado-plano"><?php echo esc_html($plano_personalizado); ?></span>
              </h2>
              <?php endif; ?>


              <!-- Conteúdo principal -->
              <?php the_content(); ?>

       
              <!-- Descrição -->
              <?php if ($desc_plano): ?>
                <p class="text-secondary small mb-3"><?php echo esc_html($desc_plano); ?></p>
              <?php endif; ?>

              <!-- Serviços adicionais -->
              <?php
              $sva_idx = 0;

              if (have_rows('servicos_adicionais')): ?>
                <div class="d-flex gap-2 flex-wrap mb-3">
                  <?php while (have_rows('servicos_adicionais')): the_row(); 
                    $icone     = get_sub_field('icone_sva');
                    $titulo    = get_sub_field('titulo_sva');
                    $descricao = get_sub_field('descricao_sva');
                    $modal_id  = 'svaModal_' . get_the_ID() . '_' . $sva_idx;
                  ?>

                    <?php if ($icone): ?>
                      <img
                        src="<?php echo esc_url($icone); ?>"
                        alt="<?php echo esc_attr($titulo ?: 'Serviço adicional'); ?>"
                        class="rounded-circle"
                        style="width:32px;height:32px;cursor:pointer;"
                        <?php if ($descricao || $titulo): ?>
                          data-bs-toggle="modal" data-bs-target="#<?php echo esc_attr($modal_id); ?>"
                        <?php endif; ?>
                      >
                    <?php endif; ?>

                    <?php
                    if ($descricao || $titulo) {
                      $modais_sva[] = [
                        'id'        => $modal_id,
                        'titulo'    => $titulo ?: 'Serviço Adicional',
                        'descricao' => $descricao ?: '',
                      ];
                    }
                    $sva_idx++;
                  endwhile; ?>
                </div>
              <?php endif; ?>

              <!-- Mais informações -->
              <?php if (!empty($informacoes_plano) || !empty($texto_legal)): ?>
                <span class="mais-info" 
                      data-bs-toggle="offcanvas" 
                      data-bs-target="#<?php echo esc_attr($offcanvas_id); ?>"  
                      aria-controls="<?php echo esc_attr($offcanvas_id); ?>">
                  Mais informações
                </span>
              <?php endif; ?>

              <hr>

              <!-- Valor -->
              <?php
              $valor_formatado = '';

              if (!empty($valor_plano)) {
                $valor_limpo = preg_replace('/^R\$ ?/', '', $valor_plano);
                $valor_limpo = preg_replace('/(\/|\s)?m(e|ê)s$/i', '', $valor_limpo);

                if (!empty($valor_limpo)) {
                  $prefixo = '<span class="prefixo-valor">R$</span> ';
                  $sufixo  = ' <span class="sufixo-valor">/mês</span>';
                  $valor_formatado = $prefixo . esc_html($valor_limpo) . $sufixo;
                }
              }
              ?>

              <?php if (!empty($valor_formatado)): ?>
                <h4 class="valor-plano fw-bold text-success"><?php echo $valor_formatado; ?></h4>
              <?php endif; ?>

            </div>

            <!-- Footer fixo com botão -->
            <?php if ($link_oferta): ?>
              <div class="card-footer bg-transparent border-0 mt-auto">
                <a href="<?php echo esc_url($link_oferta); ?>" class="btn btn-warning w-100 fw-bold">
                  Contratar Plano
                </a>
              </div>
            <?php endif; ?>

          </div>
        </div>

        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- Modais SVA -->
<?php if (!empty($modais_sva)): ?>
  <?php foreach ($modais_sva as $modal): ?>
    <div class="modal fade" id="<?php echo esc_attr($modal['id']); ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo esc_html($modal['titulo']); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <?php echo wp_kses_post($modal['descricao']); ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Offcanvas -->
<?php if (!empty($offcanvases)): ?>
  <?php foreach ($offcanvases as $oc): ?>
    <div class="info-offcanvas offcanvas offcanvas-start"
         tabindex="-1"
         id="<?php echo esc_attr($oc['id']); ?>"
         aria-labelledby="<?php echo esc_attr($oc['id']); ?>_label"
         data-bs-scroll="true">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="<?php echo esc_attr($oc['id']); ?>_label">
          <?php echo esc_html($oc['title']); ?>
        </h5>
        <button type="button" class="close-planos btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
      </div>
      <div class="offcanvas-body">
        <?php if (!empty($oc['content'])) echo wp_kses_post($oc['content']); ?>

        <?php if (!empty($oc['legal'])): ?>
          <hr class="my-3">
          <p class="texto-legal small text-muted mb-0">*<?php echo esc_html($oc['legal']); ?></p>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php endif; // fecha if ($q->have_posts()) ?>

<!-- Fim Planos B2B ----->


<?php if ( have_rows('cta') ) : ?>
  <?php while ( have_rows('cta') ) : the_row(); 
    // Pega os campos
    $imagem  = get_sub_field('imagem_cta');
    $titulo  = get_sub_field('titulo_cta');
    $desc    = get_sub_field('descricao_cta');
    $link    = get_sub_field('link_cta');

    // Verifica se tem pelo menos 1 conteúdo válido
    if ( empty($imagem) && empty($titulo) && empty($desc) && empty($link) ) {
      continue; // pula este item vazio
    }

    // Prepara o link (suporta ACF Link ou string)
    $link_url   = '#';
    $link_label = 'Monte seu pacote';
    $link_target= '_self';

    if (is_array($link)) {
      $link_url    = !empty($link['url'])   ? $link['url']   : '#';
      $link_label  = !empty($link['title']) ? $link['title'] : 'Monte seu pacote';
      $link_target = !empty($link['target'])? $link['target']: '_self';
    } elseif (!empty($link)) {
      $link_url = $link;
    }

    // Imagem (com fallback)
    $img_url = $imagem['url'] ?? '';
    $img_alt = $imagem['alt'] ?? ($titulo ?: 'CTA');
  ?>
  
  <!-- Section Não se decidiu -->
  <section class="section-nao-se-decidiu my-5">
    <div class="container">
      <div class="nao-decidiu-card rounded-4 overflow-hidden">
        <div class="row g-0 align-items-stretch">

          <!-- Lado esquerdo: imagem -->
          <?php if ($img_url): ?>
          <div class="col-12 col-lg-7 position-relative">
            <div class="ndaose-media h-100">
              <img src="<?php echo esc_url($img_url); ?>"
                   alt="<?php echo esc_attr($img_alt); ?>"
                   class="img-fluid w-100 h-100 object-cover">
              <span class="ndaose-gradient"></span>
            </div>
          </div>
          <?php endif; ?>

          <!-- Lado direito: conteúdo -->
          <div class="col-12 col-lg-5 d-flex">
            <div class="nao-decidiu-content p-4 p-lg-5 my-auto">
              

              <small class="subtitle-cta fw-semibold text-success mb-2 d-inline-block ls-1">
                		<p class="subtitulo-cta"><?php the_sub_field( 'subtitulo_cta' ); ?></p>

              </small>

              <?php if (!empty($titulo)) : ?>
                <h2 class="titulo-cta display-6 fw-bold text-dark mb-3">
                  <?php echo esc_html($titulo); ?>
                </h2>
              <?php endif; ?>

              <?php if (!empty($desc)) : ?>
                <p class="content-nao-decidiu mb-4">
                  <?php echo esc_html($desc); ?>
                </p>
              <?php endif; ?>

              <?php if (!empty($link)) : ?>
                <a href="<?php echo esc_url($link_url); ?>"
                   target="<?php echo esc_attr($link_target); ?>"
                   class="btn btn-warning btn-lg fw-semibold px-4">
                  <?php echo esc_html($link_label); ?>
                </a>
              <?php endif; ?>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <?php endwhile; ?>
<?php endif; ?>

  


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

    <div class="clientes-header">
      <?php if ($titulo = get_field('titulo_clientes')) : ?>
        <h2 class="titulo-clientes"><?php echo esc_html($titulo); ?></h2>
      <?php else : ?>
        <h2 class="titulo-clientes"></h2>
      <?php endif; ?>

      <?php if ($desc = get_field('descricao_clientes')) : ?>
        <p class="descricao-clientes"><?php echo esc_html($desc); ?></p>
      <?php endif; ?>
    </div>

    <div class="swiper clientes-parceiros-swiper">
      <div class="swiper-wrapper">
        <?php while ($q->have_posts()) : $q->the_post(); ?>
          <div class="swiper-slide">
            <div class="logo-wrap">
              <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) : 
                  $alt = esc_attr(get_the_title());
                  the_post_thumbnail('medium', [
                    'class' => 'logo-img',
                    'alt'   => $alt,
                    'loading' => 'lazy',
                    'decoding' => 'async',
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


<!-- Nossos numeros -->

<?php if ( have_rows('nossos_numeros') ) : ?>
<section class="stats-section py-5">
  <div class="container">

   <div class="numeros-header">
      <?php if ($titulo = get_field('Titulo_nossos_numeros')) : ?>
        <h2 class="nossos-numeros" ><?php echo esc_html($titulo); ?></h2>
      <?php else : ?>
        <h2 class="nossos-numeros"></h2>
      <?php endif; ?>



    <div class="row g-4 stats-grid">

      <?php while ( have_rows('nossos_numeros') ) : the_row();

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

<!-- Nossas soluções -->
<?php
/**
 * ===== Helper: obter somente TEXTO do conteúdo =====
 * - Ignora botões/anchors/shortcodes
 * - Mantém apenas parágrafos, headings, listas e citações
 * - $limit: nº de palavras (null para não limitar)
 */
if ( ! function_exists('dv_get_text_only_from_content') ) {
  function dv_get_text_only_from_content( $post_id = null, $limit = 30 ) {
    $post = get_post( $post_id );
    if ( ! $post ) return '';

    $content = $post->post_content;

    if ( function_exists('parse_blocks') ) {
      // Editor de blocos
      $blocks = parse_blocks( $content );
      $allowed = ['core/paragraph','core/heading','core/list','core/quote','core/pullquote'];
      $parts = [];

      $walk = function($b) use (&$walk, $allowed, &$parts) {
        $name = isset($b['blockName']) ? $b['blockName'] : null;

        // Ignora blocos não permitidos (ex.: botões, embeds, galerias etc.)
        if ( $name && ! in_array($name, $allowed, true) ) {
          return;
        }

        if ( ! empty($b['innerHTML']) ) {
          $html = $b['innerHTML'];

          // Remove anchors/botões
          $html = preg_replace('#<a\b[^>]*>.*?</a>#si', '', $html);
          $html = preg_replace('#<button\b[^>]*>.*?</button>#si', '', $html);

          // Texto puro
          $txt  = trim( wp_strip_all_tags( $html, true ) );
          if ( $txt ) $parts[] = $txt;
        }

        // Varre blocos internos
        if ( ! empty($b['innerBlocks']) ) {
          foreach ( $b['innerBlocks'] as $ib ) $walk($ib);
        }
      };

      foreach ( $blocks as $b ) $walk($b);
      $text = trim( preg_replace('/\s+/', ' ', implode(' ', $parts) ) );

    } else {
      // Fallback editor clássico
      $html = apply_filters('the_content', $content);

      // Remove containers Gutenberg de botões
      $html = preg_replace('#<div[^>]*class="[^"]*(?:wp-block-buttons|wp-block-button)[^"]*"[^>]*>.*?</div>#si', '', $html);
      // Remove anchors com cara de botão (classe btn, role=button, wp-block-button__link)
      $html = preg_replace('#<a[^>]*(?:class="[^"]*(?:wp-block-button__link|btn)[^"]*"|role="button")[^>]*>.*?</a>#si', '', $html);
      // Remove tags <button>
      $html = preg_replace('#<button\b[^>]*>.*?</button>#si', '', $html);
      // Remove shortcodes
      $html = strip_shortcodes( $html );

      // Texto puro
      $text = trim( preg_replace('/\s+/', ' ', wp_strip_all_tags($html, true) ) );
    }

    if ( $limit !== null && $limit > 0 ) {
      $text = $text ? wp_trim_words( $text, $limit, '…' ) : '';
    }
    return $text;
  }
}
?>

<!-- Nossas soluções -->
<?php
/**
 * ===== Helper: extrair SOMENTE TEXTO do conteúdo =====
 * - Suporta Gutenberg (parse_blocks + render_block)
 * - Ignora blocos não textuais (buttons, embed, etc.)
 * - Remove <a>, <button>, shortcodes, SVGs
 * - Compacta espaços e limita palavras
 */
if ( ! function_exists('dv_get_text_only_from_content') ) {
  function dv_get_text_only_from_content( $post_id = null, $limit = 30 ) {
    $post = get_post( $post_id );
    if ( ! $post ) return '';

    $content = $post->post_content;
    $text    = '';

    // Preferência: se a função has_blocks existir e houver blocos
    if ( function_exists('has_blocks') && function_exists('parse_blocks') && function_exists('render_block') && has_blocks($content) ) {
      $blocks  = parse_blocks( $content );
      // Blocos permitidos (textuais)
      $allowed = [
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/list-item',
        'core/quote',
        'core/pullquote',
        'core/table',        // opcional
        'core/preformatted', // opcional
      ];

      // Caminha pelos blocos e renderiza apenas os permitidos
      $render_allowed = function($blocks) use (&$render_allowed, $allowed) {
        $html = '';
        foreach ($blocks as $b) {
          $name = isset($b['blockName']) ? $b['blockName'] : null;
          if ( $name && in_array($name, $allowed, true) ) {
            $html .= render_block($b);
          } elseif (!empty($b['innerBlocks'])) {
            // Se o bloco não é permitido, mas contém filhos, tenta renderizar os filhos
            $html .= $render_allowed($b['innerBlocks']);
          }
        }
        return $html;
      };

      $html = $render_allowed($blocks);
    } else {
      // Editor clássico / fallback
      $html = apply_filters('the_content', $content);
    }

    // Limpeza: remove containers de botões do Gutenberg (se passarem)
    $html = preg_replace('#<div[^>]*class="[^"]*(?:wp-block-buttons|wp-block-button)[^"]*"[^>]*>.*?</div>#si', '', $html);
    // Remove qualquer link/âncora (inclui botões-âncora) e <button>
    $html = preg_replace('#<a\b[^>]*>.*?</a>#si', '', $html);
    $html = preg_replace('#<button\b[^>]*>.*?</button>#si', '', $html);
    // Remove svgs (caso existam em ícones)
    $html = preg_replace('#<svg[\s\S]*?</svg>#i', '', $html);
    // Remove shortcodes [shortcode]
    if ( function_exists('strip_shortcodes') ) {
      $html = strip_shortcodes( $html );
    }

    // Converte para texto puro
    $text = wp_strip_all_tags( $html, true );
    $text = html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );
    $text = trim( preg_replace('/\s+/', ' ', $text) );

    // Fallback: se ficou vazio, tenta excerpt
    if ( $text === '' ) {
      $excerpt = get_the_excerpt( $post );
      $text = $excerpt ? trim( preg_replace('/\s+/', ' ', $excerpt) ) : '';
    }

    // Limite de palavras
    if ( $limit !== null && $limit > 0 ) {
      $text = $text ? wp_trim_words( $text, $limit, '…' ) : '';
    }

    return $text;
  }
}
?>

<!-- Nossas soluções -->
<?php
/**
 * ===== Helper: extrair SOMENTE TEXTO do conteúdo =====
 * - Remove headings (h1..h6 e wp-block-heading)
 * - Remove botões/links (<a>, <button>), shortcodes, figuras/imagens, svgs, forms
 * - Mantém apenas o texto; limita palavras
 */
if ( ! function_exists('dv_get_text_only_from_content_no_headings') ) {
  function dv_get_text_only_from_content_no_headings( $post_id = null, $limit = 30 ) {
    $post = get_post( $post_id );
    if ( ! $post ) return '';

    $content = $post->post_content;
    $html    = '';

    if ( function_exists('has_blocks') && has_blocks($content) && function_exists('parse_blocks') && function_exists('render_block') ) {
      // Gutenberg: renderiza apenas blocos textuais (sem 'core/heading')
      $blocks  = parse_blocks( $content );
      $allowed = [
        'core/paragraph',
        'core/list',
        'core/quote',
        'core/pullquote',
        'core/preformatted',
        'core/table',
      ];
      $render_allowed = function($blocks) use (&$render_allowed, $allowed) {
        $html = '';
        foreach ($blocks as $b) {
          $name = $b['blockName'] ?? null;
          if ( $name && in_array($name, $allowed, true) ) {
            $html .= render_block($b);
          } elseif ( !empty($b['innerBlocks']) ) {
            $html .= $render_allowed($b['innerBlocks']);
          }
        }
        return $html;
      };
      $html = $render_allowed($blocks);
    } else {
      // Editor clássico / fallback
      $html = apply_filters('the_content', $content);
    }

    // Remove headings e bloco de heading
    $html = preg_replace('#<h[1-6][^>]*>.*?</h[1-6]>#si', ' ', $html);
    $html = preg_replace('#<div[^>]*class="[^"]*wp-block-heading[^"]*"[^>]*>.*?</div>#si', ' ', $html);

    // Remove containers de botões, anchors e <button>
    $html = preg_replace('#<div[^>]*class="[^"]*(?:wp-block-buttons|wp-block-button)[^"]*"[^>]*>.*?</div>#si', ' ', $html);
    $html = preg_replace('#<a\b[^>]*>.*?</a>#si', ' ', $html);
    $html = preg_replace('#<button\b[^>]*>.*?</button>#si', ' ', $html);

    // Remove figuras/imagens/svg/form
    $html = preg_replace('#<figure[\s\S]*?</figure>#i', ' ', $html);
    $html = preg_replace('#<img[^>]*>#i', ' ', $html);
    $html = preg_replace('#<svg[\s\S]*?</svg>#i', ' ', $html);
    $html = preg_replace('#<form[\s\S]*?</form>#i', ' ', $html);

    // Remove shortcodes
    if ( function_exists('strip_shortcodes') ) {
      $html = strip_shortcodes( $html );
    }

    // Texto puro + compactação de espaços
    $text = trim( preg_replace('/\s+/', ' ', wp_strip_all_tags($html, true) ) );

    // Fallback: excerpt
    if ( $text === '' ) {
      $excerpt = get_the_excerpt( $post );
      $text = $excerpt ? trim( preg_replace('/\s+/', ' ', $excerpt) ) : '';
    }

    // Limite de palavras
    if ( $limit !== null && $limit > 0 ) {
      $text = $text ? wp_trim_words( $text, $limit, '…' ) : '';
    }

    return $text;
  }
}
?>

<!-- Nossas soluções -->
<?php
$q = new WP_Query([
  'post_type'      => 'nossas_solucoes',
  'posts_per_page' => -1,
  'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
  'order'          => 'ASC',
]);

if ( $q->have_posts() ) : ?>
<section class="solucoes-swiper-section my-5">
  <div class="container">
    <div class="clientes-header">
      <?php if ( $titulo = get_field('titulo_solucoes') ) : ?>
        <h2 class="titulo-solucoes"><?php echo esc_html($titulo); ?></h2>
      <?php endif; ?>

      <?php if ( $desc = get_field('descricao_solucoes') ) : ?>
        <p class="descricao-solucoes"><?php echo esc_html($desc); ?></p>
      <?php endif; ?>
    </div>

    <div class="swiper solucoes-swiper">
      <div class="swiper-wrapper">

        <?php while ( $q->have_posts() ) : $q->the_post();

          // Imagem destacada
          $thumb_id = get_post_thumbnail_id();
          $img      = $thumb_id ? ( wp_get_attachment_image_src($thumb_id, 'large')[0] ?? '' ) : '';

          // CTA (ACF "Link" ou fallback)
          $cta_field      = get_field('cta_link');
          $cta_text_field = get_field('cta_text');

          if ( is_array($cta_field) && !empty($cta_field['url']) ) {
            $cta_link   = $cta_field['url'];
            $cta_text   = !empty($cta_field['title']) ? $cta_field['title'] : ( $cta_text_field ?: 'Saiba mais' );
            $cta_target = !empty($cta_field['target']) ? $cta_field['target'] : '_self';
          } else {
            $cta_link   = get_permalink();
            $cta_text   = $cta_text_field ?: 'Saiba mais';
            $cta_target = '_self';
          }

          // ===== "the_content" apenas como TEXTO (sem headings/botões/links/shortcodes) =====
          $text_only = dv_get_text_only_from_content_no_headings( get_the_ID(), 30 ); // ajuste o 30 conforme layout
        ?>
          <div class="swiper-slide">
            <article class="solucao-card">
              <?php if ($img): ?>
                <img class="solucao-bg" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
              <?php else: ?>
                <div class="solucao-bg-placeholder" aria-hidden="true"></div>
              <?php endif; ?>

              <div class="solucao-overlay"></div>

              <div class="solucao-content">
                <h3 class="solucao-title"><?php the_title(); ?></h3>
                <?php if ($text_only): ?>
                  <p class="solucao-excerpt"><?php echo esc_html($text_only); ?></p>
                <?php endif; ?>
              </div>

              <div class="solucao-footer">
                <a class="solucao-cta"
                  href="<?php echo esc_url($cta_link); ?>"
                  target="<?php echo esc_attr($cta_target); ?>"
                  aria-label="<?php echo esc_attr('Saiba mais sobre ' . get_the_title()); ?>">
                  <span><?php echo esc_html($cta_text); ?></span>
                  <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M13.172 12L8.222 7.05l1.414-1.414L16 12l-6.364 6.364-1.414-1.414z" fill="currentColor"/>
                  </svg>
                </a>
              </div>
            </article>
          </div>
        <?php endwhile; ?>

      </div>

      <!-- Controles -->
      <div class="nossas-solucoes swiper-pagination"></div>

    </div>
  </div>
</section>
<?php
wp_reset_postdata();
endif;
?>










<?php get_footer(); ?>
