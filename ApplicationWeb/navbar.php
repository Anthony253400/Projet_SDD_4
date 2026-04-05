<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav>
  <div class="nav-inner">
    <a class="logo" href="index.php">
      <div class="logo-mark">♻</div>
      <span class="logo-name">Éco<em>Scan</em></span>
    </a>

    <div class="nav-links">

        <a href="index.php">
            <button class="nav-btn <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" id="nb-home">
                <i class="fa-solid fa-house nav-ico"></i>
                Accueil
            </button>
        </a>

        <a href="pred.php">
            <button class="nav-btn <?php echo ($currentPage == 'pred.php') ? 'active' : ''; ?>" id="nb-scan">
                <i class="fa-solid fa-images nav-ico"></i>
                Prédiction déchet
            </button>
        </a>

        <a href="prediction.php">   
            <button class="nav-btn <?php echo ($currentPage == 'prediction.php') ? 'active' : ''; ?>" id="nb-communes">
                <i class="fa-solid fa-eye nav-ico"></i>
                Prédiction communes
            </button>
        </a>

        <a href="graphiques.php">
            <button class="nav-btn <?php echo ($currentPage == 'graphiques.php') ? 'active' : ''; ?>" id="nb-charts">
                <i class="fa-solid fa-chart-line"></i>
                Graphiques
            </button>
        </a>

        <a href="carte.php">
            <button class="nav-btn <?php echo ($currentPage == 'carte.php') ? 'active' : ''; ?>" id="nb-map">
                <i class="fa-solid fa-map-location-dot nav-ico"></i>
                Carte des données
            </button>
        </a>

        <a href="nous.php">
            <button class="nav-btn <?php echo ($currentPage == 'nous.php') ? 'active' : ''; ?>" id="nb-nous">
                <i class="fa-solid fa-user-group nav-ico"></i>
                Qui sommes-nous ?
            </button>
        </a>

    </div>
  </div>
</nav>