<nav>
  <div class="nav-inner">
    <a class="logo" href="#" onclick="show('home');return false">
      <div class="logo-mark">♻</div>
      <span class="logo-name">Éco<em>Scan</em></span>
    </a>

    <div class="nav-links">

        <a href="index.php">
            <button class="nav-btn active" id="nb-home" onclick="show('home')">
                <svg class="nav-ico" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                Accueil
            </button>
        </a>

        <a href="pred.php">
            <button class="nav-btn" id="nb-scan" onclick="show('scan')">
                Prédiction déchet
            </button>
        </a>

        <a href="prediction.php">   
            <button class="nav-btn" id="nb-communes" onclick="show('communes')">
                rédiction communes
            </button>
        </a>

        <a href="graphiques.php">
            <button class="nav-btn" id="nb-charts" onclick="show('charts')">
                <svg class="nav-ico" viewBox="0 0 24 24"><path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zm5.6 8H19v6h-2.8v-6z"/></svg>
                Graphiques
            </button>
        </a>

    </div>

  </div>
</nav>
