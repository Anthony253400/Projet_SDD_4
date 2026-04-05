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
                <svg class="nav-ico" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                Accueil
            </button>
        </a>

        <a href="pred.php">
            <button class="nav-btn <?php echo ($currentPage == 'pred.php') ? 'active' : ''; ?>" id="nb-scan">
                Prédiction déchet
            </button>
        </a>

        <a href="prediction.php">   
            <button class="nav-btn <?php echo ($currentPage == 'prediction.php') ? 'active' : ''; ?>" id="nb-communes">
                Prédiction communes
            </button>
        </a>

        <a href="graphiques.php">
            <button class="nav-btn <?php echo ($currentPage == 'graphiques.php') ? 'active' : ''; ?>" id="nb-charts">
                <svg class="nav-ico" viewBox="0 0 24 24"><path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zm5.6 8H19v6h-2.8v-6z"/></svg>
                Graphiques
            </button>
        </a>

        <a href="carte.php">
            <button class="nav-btn <?php echo ($currentPage == 'carte.php') ? 'active' : ''; ?>" id="nb-map">
                Carte des données
            </button>
        </a>

    </div>
  </div>
</nav>