<?php get_header(); ?>

<div class="container">
  <?php if (have_posts()) : ?>
    <div class="row">
      <?php while (have_posts()) : the_post(); ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?php the_title(); ?></h5>
              <p class="card-text"><?php the_excerpt(); ?></p>
              <a href="<?php the_permalink(); ?>" class="btn btn-primary">Leia mais</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else : ?>
    <p>Nenhum conteúdo encontrado.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>