<?php
/* Single Nossas Soluções */
get_header(); ?>

<?php get_template_part('template-parts/breadcrumb'); ?>



<main class="conteudo-single-solucoes">

    <!-- titulo -->
       <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<!-- Não utilizado aqui 
    <h1 class="titulo-single-cliente"><?php the_title(); ?></h1>
--------->
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
