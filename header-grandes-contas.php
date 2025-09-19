<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>

  <!-- Swiper e Google Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

 
</head>
<body <?php body_class(); ?>>

<?php $logo_topo = get_option('valenet_logo_topo'); ?>

<!-- ================== TOP BAR ================== -->
<?php if (has_nav_menu('menu-topbar')): ?>
<div class="topbar">
  <div class="container d-flex justify-content-start align-items-center py-1">

    <!-- Menu Desktop -->
    <nav class="d-none d-lg-block">
      <?php
        wp_nav_menu([
          'theme_location' => 'menu-topbar',
          'container'      => false,
          'menu_class'     => 'nav small',
          'fallback_cb'    => false,
          'depth'          => 1
        ]);
      ?>
    </nav>

    <!-- Menu Mobile -->
    <div class="dropdown d-lg-none">
      <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="topbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        Menu
      </button>
      <ul class="dropdown-menu" aria-labelledby="topbarDropdown">
        <?php
          wp_nav_menu([
            'theme_location' => 'menu-topbar',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'depth'          => 1
          ]);
        ?>
      </ul>
    </div>

  </div>
</div>
<?php endif; ?>
<!-- ================ FIM TOP BAR ================ -->


<!-- ========== HEADER GRANDES CONTAS ========== -->
<header class="menu-topo shadow-md sticky top-0">
  <nav class="navbar navbar-expand-lg navbar-light bg-white container">

    <!-- Logo -->
    <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
      <?php 
        if ($logo_topo) {
          echo '<img src="'. esc_url($logo_topo) .'" alt="'. esc_attr(get_bloginfo('name')) .'" class="site-logo" style="max-height:50px;">';
        } elseif (has_custom_logo()) {
          the_custom_logo();
        } else {
          echo '<span class="fw-bold">'. get_bloginfo('name') .'</span>';
        }
      ?>
    </a>

    <!-- Botão Mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Grandes Contas -->
    <?php
      if (has_nav_menu('grandes-contas-menu')) {
        wp_nav_menu([
          'theme_location' => 'grandes-contas-menu',
          'depth'          => 2,
          'container'      => 'div',
          'container_class'=> 'collapse navbar-collapse',
          'container_id'   => 'mainMenu',
          'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0 menu-topo-nav',
          'fallback_cb'    => false,
          'walker'         => class_exists('Bootstrap_Navwalker') ? new Bootstrap_Navwalker() : '',
        ]);
      } else {
        echo '<div class="collapse navbar-collapse" id="mainMenu">';
        echo '<ul class="navbar-nav ms-auto mb-2 mb-lg-0">';
        echo '<li class="nav-item"><a class="nav-link text-danger" href="#">⚠ Menu "Grandes Contas" não atribuído</a></li>';
        echo '</ul></div>';
      }
    ?>
  </nav>
</header>

