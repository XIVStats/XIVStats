<?php

$db = new SQLite3('players.db');

$active_check = "arr_25_complete == 1";

$american_realms = "(realm = 'Behemoth' OR realm = 'Brynhildr' OR realm = 'Diabolos'
        OR realm = 'Excalibur' OR realm = 'Exodus' OR realm = 'Famfrit' OR realm = 'Hyperion' OR realm = 'Lamia' OR realm = 'Leviathan'
        OR realm = 'Malboro' OR realm = 'Ultros' OR realm = 'Adamantoise' OR realm = 'Balmung' OR realm = 'Cactuar' OR realm = 'Coeurl'
        OR realm = 'Faerie' OR realm = 'Gilgamesh' OR realm = 'Goblin' OR realm = 'Jenova' OR realm = 'Mateus' OR realm = 'Midgardsormr'
        OR realm = 'Sargatanas' OR realm = 'Siren' OR realm = 'Zalera')";

$japanese_realms = "(realm = 'Alexander' OR realm = 'Bahamut' OR realm = 'Durandal'
        OR realm = 'Fenrir' OR realm = 'Ifrit' OR realm = 'Ridill' OR realm = 'Tiamat' OR realm = 'Ultima' OR realm = 'Valefor' OR
        realm = 'Yojimbo'  OR realm = 'Zeromus' OR realm = 'Anima' OR realm = 'Asura' OR realm = 'Belias' OR realm = 'Chocobo' OR
        realm = 'Hades' OR realm = 'Ixion' OR realm = 'Mandragora' OR realm = 'Masamune' OR realm = 'Pandaemonium' OR realm = 'Shinryu'
        OR realm = 'Titan' OR realm = 'Aegis' OR realm = 'Atomis' OR realm = 'Carbuncle' OR realm = 'Garuda' OR realm = 'Gungnir'
        OR realm = 'Kujata' OR realm = 'Ramuh' OR realm = 'Tonberry' OR realm = 'Typhon' OR realm = 'Unicorn')";

$european_realms = "(realm = 'Cerberus' OR realm = 'Lich' OR realm = 'Moogle' OR
        realm = 'Odin' OR realm = 'Phoenix' OR realm = 'Ragnarok' OR realm = 'Shiva' OR realm = 'Zodiark')";

// Fetch total number of players in database
$player_count_query = $db->query("SELECT count() FROM players;");
$player_count = $player_count_query->fetchArray()[0];
$fmt_player_count = number_format($player_count);

$active_player_count_query = $db->query("SELECT count() FROM players WHERE " . $active_check . ";");
$active_player_count = $active_player_count_query->fetchArray()[0];
$fmt_active_player_count = number_format($active_player_count);

// Fetch total number of players in each region
// America
$america_player_count_query = $db->query("SELECT count() FROM players WHERE " . $american_realms);
$america_player_count = $america_player_count_query->fetchArray()[0];
$fmt_america_player_count = number_format($america_player_count);

$america_active_player_count_query = $db->query("SELECT count() FROM players WHERE " . $american_realms . " AND " . $active_check);
$america_active_player_count = $america_active_player_count_query->fetchArray()[0];
$fmt_america_active_player_count = number_format($america_active_player_count);

//Japan
$japan_player_count_query = $db->query("SELECT count() FROM players WHERE " . $japanese_realms);
$japan_player_count = $japan_player_count_query->fetchArray()[0];
$fmt_japan_player_count = number_format($japan_player_count);

$japan_active_player_count_query = $db->query("SELECT count() FROM players WHERE " . $japanese_realms . " AND " . $active_check);
$japan_active_player_count = $japan_active_player_count_query->fetchArray()[0];
$fmt_japan_active_player_count = number_format($japan_active_player_count);

//Europe
$europe_player_count_query = $db->query("SELECT count() FROM players WHERE " . $european_realms);
$europe_player_count = $europe_player_count_query->fetchArray()[0];
$fmt_europe_player_count = number_format($europe_player_count);

$europe_active_player_count_query = $db->query("SELECT count() FROM players WHERE " . $european_realms . " AND " . $active_check);
$europe_active_player_count = $europe_active_player_count_query->fetchArray()[0];
$fmt_europe_active_player_count = number_format($europe_active_player_count);

// Grand Company Distribution
$gc_distribution = array();
$gc_distribution_query = $db->query("SELECT grand_company,count() FROM players GROUP BY grand_company");

while ($row = $gc_distribution_query->fetchArray()) {
        //$row[0] = grand company name
        //$row[1] = population

        //If user is not in gc, mark gc as none rather than blank
        if ($row[0] === '') { $row[0] = "None"; }
        $gc_distribution[$row[0]] = $row[1];
}

$gc_active_distribution = array();
$gc_active_distribution_query = $db->query("select grand_company,count() from players WHERE " . $active_check . " group by grand_company");

while ($row = $gc_active_distribution_query->fetchArray()) {
        //$row[0] = grand company name
        //$row[1] = population

        //If user is not in gc, mark gc as none rather than blank
        if ($row[0] === '') { $row[0] = "None"; }
        $gc_active_distribution[$row[0]] = $row[1];
}


// Race / Gender Distribution

$race_gender_male = array();
$race_gender_female = array();
$race_gender_query = $db->query("SELECT race,gender,count() FROM players GROUP BY race,gender");

while ($row = $race_gender_query->fetchArray()) {
        //$row[0] = race
        //$row[1] = gender
        //$row[2] = population

        //Split males and females into seperate arrays
        if ($row[1] === "male") {
                $race_gender_male[$row[0]] = $row[2];
        } else if ($row[1] === "female") {
                $race_gender_female[$row[0]] = $row[2];
        }
}

$active_race_gender_male = array();
$active_race_gender_female = array();
$active_race_gender_query = $db->query("SELECT race,gender,count() FROM players WHERE " . $active_check . " GROUP BY race,gender");

while ($row = $active_race_gender_query->fetchArray()) {
        //$row[0] = race
        //$row[1] = gender
        //$row[2] = population

        //Split males and females into seperate arrays
        if ($row[1] === "male") {
                $active_race_gender_male[$row[0]] = $row[2];
        } else if ($row[1] === "female") {
                $active_race_gender_female[$row[0]] = $row[2];
        }
}

// Get statistics on class adoption
$classes = array();

$class_results = $db->query("SELECT count() FROM players WHERE level_gladiator != ''");
$classes["Gladiator"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_pugilist != ''");
$classes["Pugilist"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_marauder != ''");
$classes["Marauder"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_lancer != ''");
$classes["Lancer"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_archer != ''");
$classes["Archer"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_rogue != ''");
$classes["Rogue"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_conjurer != ''");
$classes["Conjurer"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_thaumaturge != ''");
$classes["Thaumaturge"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_arcanist != ''");
$classes["Arcanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_darkknight != ''");
$classes["Dark Knight"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_machinist != ''");
$classes["Machinist"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_astrologian != ''");
$classes["Astrologian"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_carpenter != ''");
$classes["Carpenter"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_blacksmith != ''");
$classes["Blacksmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_armorer != ''");
$classes["Armorer"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_goldsmith != ''");
$classes["Goldsmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_leatherworker != ''");
$classes["Leatherworker"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_weaver != ''");
$classes["Weaver"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_alchemist != ''");
$classes["Alchemist"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_culinarian != ''");
$classes["Culinarian"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_miner != ''");
$classes["Miner"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_botanist != ''");
$classes["Botanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("SELECT count() FROM players WHERE level_fisher != ''");
$classes["Fisher"] = $class_results->fetchArray()[0];

// Get statistics on active class adoption
$active_classes = array();

$class_results = $db->query("select count() from players where level_gladiator >= '60' AND level_gladiator != ''");
$active_classes["Gladiator"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_pugilist >= '60' AND level_pugilist != ''");
$active_classes["Pugilist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_marauder >= '60' AND level_marauder != ''");
$active_classes["Marauder"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_lancer >= '60' AND level_lancer != ''");
$active_classes["Lancer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_archer >= '60' AND level_archer != ''");
$active_classes["Archer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_rogue >= '60' AND level_rogue != ''");
$active_classes["Rogue"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_conjurer >= '60' AND level_conjurer != ''");
$active_classes["Conjurer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_thaumaturge >= '60' AND level_thaumaturge != ''");
$active_classes["Thaumaturge"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_arcanist >= '60' AND level_arcanist != ''");
$active_classes["Arcanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_darkknight >= '60' AND level_darkknight != ''");
$active_classes["Dark Knight"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_machinist >= '60' AND level_machinist != ''");
$active_classes["Machinist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_astrologian >= '60' AND level_astrologian != ''");
$active_classes["Astrologian"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_carpenter >= '60' AND level_carpenter != ''");
$active_classes["Carpenter"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_blacksmith >= '60' AND level_blacksmith != ''");
$active_classes["Blacksmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_armorer >= '60' AND level_blacksmith != ''");
$active_classes["Armorer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_goldsmith >= '60' AND level_goldsmith != ''");
$active_classes["Goldsmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_leatherworker >= '60' AND level_leatherworker != ''");
$active_classes["Leatherworker"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_weaver >= '60' AND level_weaver != ''");
$active_classes["Weaver"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_alchemist >= '60' AND level_alchemist != ''");
$active_classes["Alchemist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_culinarian >= '60' AND level_culinarian != ''");
$active_classes["Culinarian"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_miner >= '60' AND level_miner != ''");
$active_classes["Miner"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_botanist >= '60' AND level_botanist != ''");
$active_classes["Botanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_fisher >= '60' AND level_fisher != ''");
$active_classes["Fisher"] = $class_results->fetchArray()[0];

// Get statistics on realm population
$america_realm_pop = array();
$america_realm_pop_query = $db->query("SELECT realm,count() FROM players WHERE " . $american_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $america_realm_pop_query->fetchArray()) {
        //$row[0] = realm name
        //$row[1] = population
        $america_realm_pop[$row[0]] = $row[1];
}

$active_america_realm_pop = array();
$active_america_realm_pop_query = $db->query("SELECT realm,count() FROM players WHERE " . $active_check . " AND " . $american_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $active_america_realm_pop_query->fetchArray()) {
        //$row[0] = realm name
        //$row[1] = population
        $active_america_realm_pop[$row[0]] = $row[1];
}

$japan_realm_pop = array();
$japan_realm_pop_query = $db->query("SELECT realm,count() FROM players WHERE " . $japanese_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $japan_realm_pop_query->fetchArray()) {
        //$row[0] = realm name
        //$row[1] = population
        $japan_realm_pop[$row[0]] = $row[1];
}

$active_japan_realm_pop = array();
$active_japan_realm_pop_query = $db->query("SELECT realm,count() FROM players WHERE " . $active_check . " AND " . $japanese_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $active_japan_realm_pop_query->fetchArray()) {
        //$row[0] = realm name
        //$row[1] = population
        $active_japan_realm_pop[$row[0]] = $row[1];
}

$europe_realm_pop = array();
$europe_realm_pop_query = $db->query("SELECT realm,count() FROM players WHERE " . $european_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $europe_realm_pop_query->fetchArray()) {
        //$row[0] = realm name
        //$row[1] = population
        $europe_realm_pop[$row[0]] = $row[1];
}

$active_europe_realm_pop = array();
$active_europe_realm_pop_query = $db->query("SELECT realm,count() FROM players WHERE " . $active_check . " AND " . $european_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $active_europe_realm_pop_query->fetchArray()) {
        //$row[0] = realm name
        //$row[1] = population
        $active_europe_realm_pop[$row[0]] = $row[1];
}



// Subscription figures

$sub_30_days_query = $db->query("SELECT count() FROM players WHERE p30days == '1';");
$sub_30_days = $sub_30_days_query->fetchArray()[0];
$fmt_sub_30_days = number_format($sub_30_days);

$sub_60_days_query = $db->query("SELECT count() FROM players WHERE p60days == '1';");
$sub_60_days = $sub_60_days_query->fetchArray()[0];
$fmt_sub_60_days = number_format($sub_60_days);

$sub_90_days_query = $db->query("SELECT count() FROM players WHERE p90days == '1';");
$sub_90_days = $sub_90_days_query->fetchArray()[0];
$fmt_sub_90_days = number_format($sub_90_days);

$sub_180_days_query = $db->query("SELECT count() FROM players WHERE p180days == '1';");
$sub_180_days = $sub_180_days_query->fetchArray()[0];
$fmt_sub_180_days = number_format($sub_180_days);

$sub_270_days_query = $db->query("SELECT count() FROM players WHERE p270days == '1';");
$sub_270_days = $sub_270_days_query->fetchArray()[0];
$fmt_sub_270_days = number_format($sub_270_days);

$sub_360_days_query = $db->query("SELECT count() FROM players WHERE p360days == '1';");
$sub_360_days = $sub_360_days_query->fetchArray()[0];
$fmt_sub_360_days = number_format($sub_360_days);

$sub_450_days_query = $db->query("SELECT count() FROM players WHERE p450days == '1';");
$sub_450_days = $sub_450_days_query->fetchArray()[0];
$fmt_sub_450_days = number_format($sub_450_days);

$sub_630_days_query = $db->query("SELECT count() FROM players WHERE p630days == '1';");
$sub_630_days = $sub_630_days_query->fetchArray()[0];
$fmt_sub_630_days = number_format($sub_630_days);

// Pre-orders

$prearr_query = $db->query("SELECT count() FROM players WHERE prearr == '1';");
$prearr = $prearr_query->fetchArray()[0];
$fmt_prearr = number_format($prearr);

$prehw_query = $db->query("SELECT count() FROM players WHERE prehw == '1';");
$prehw = $prehw_query->fetchArray()[0];
$fmt_prehw = number_format($prehw);

// Collectors Edition

$ps4_collectors_query = $db->query("SELECT count() FROM players WHERE ps4collectors == '1';");
$ps4_collectors = $ps4_collectors_query->fetchArray()[0];
$fmt_ps4_collectors = number_format($ps4_collectors);

// Physical Items

$artbook_query = $db->query("SELECT count() FROM players WHERE artbook == '1';");
$artbook = $artbook_query->fetchArray()[0];
$fmt_artbook = number_format($artbook);

$beforemeteor_query = $db->query("SELECT count() FROM players WHERE beforemeteor == '1';");
$beforemeteor = $beforemeteor_query->fetchArray()[0];
$fmt_beforemeteor = number_format($beforemeteor);

$beforethefall_query = $db->query("SELECT count() FROM players WHERE beforethefall == '1';");
$beforethefall = $beforethefall_query->fetchArray()[0];
$fmt_beforethefall = number_format($beforethefall);

$soundtrack_query = $db->query("SELECT count() FROM players WHERE soundtrack == '1';");
$soundtrack = $soundtrack_query->fetchArray()[0];
$fmt_soundtrack = number_format($soundtrack);

$moogleplush_query = $db->query("SELECT count() FROM players WHERE moogleplush == '1';");
$moogleplush = $moogleplush_query->fetchArray()[0];
$fmt_moogleplush = number_format($moogleplush);

// Eternal Bond

$saw_eternal_bond_query = $db->query("SELECT count() FROM players WHERE saweternalbond == '1';");
$saw_eternal_bond = $saw_eternal_bond_query->fetchArray()[0];
$fmt_saw_eternal_bond = number_format($saw_eternal_bond);

// Player Commendations

$comm50_query = $db->query("SELECT count() FROM players WHERE comm50 == '1';");
$comm50 = $comm50_query->fetchArray()[0];
$fmt_comm50 = number_format($comm50);

// Hildibrand

$hildibrand_query = $db->query("SELECT count() FROM players WHERE hildibrand == '1';");
$hildibrand = $hildibrand_query->fetchArray()[0];
$fmt_hildibrand = number_format($hildibrand);

// ARR Sightseeing Log

$sightseeing_query = $db->query("SELECT count() FROM players WHERE sightseeing == '1';");
$sightseeing = $sightseeing_query->fetchArray()[0];
$fmt_sightseeing = number_format($sightseeing);


?>

<html>

  <head>
    <title>XIVCensus - Player statistics for FFXIV</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
      <!-- Compiled and minified CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.0/css/materialize.min.css">
      <!-- Compiled and minified JavaScript -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.0/js/materialize.min.js"></script>
      <style>
          .box-element {
              width: 100%;
          }

          #pageTitleBox {
              margin-top: 30px;
              margin-bottom: 0;
          }

          .region-title {
              text-align: center;
              color: black;
              font-size: 28pt;
          }

          .region-subtitle {
              text-align: center;
              color: #9e9e9e;
              font-size: large;
          }

          .region-stat {
              text-align: center;
              font-size: 48pt;
          }

          .region-stat-diff {
              font-size: medium;
          }

          .region-stat-label {
              text-align: center;
              font-size: large;
          }

          footer.page-footer {
              margin-top: 0px;
              padding-top: 0px;
          }
      </style>
  </head>

  <body>
  <div class="container box-element">
      <div class="row" id="pageTitleBox">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text" style="font-size:28pt;">XIVCensus - Player statistics for FFXIV</span>
                      <p><b>* (Any reference to "Active" players, refers to players who have the midgardsormr pet from the 2.5 story)</b></p>
                      <p>Please click <a href="/old">HERE</a> to see previous censuses</p>
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">HOW MANY PLAYERS ARE THERE?</span>
                      <br/>
                      <hr/>
                      <br/>
                      <!--World-->
                      <div class="black-text light region-title">WORLD</div>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_player_count; ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_active_player_count; ?></div>
                          </div>
                      </div>
                      <!-- America -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-title">AMERICA</div>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_america_player_count; ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_america_active_player_count; ?></div>
                          </div>
                      </div>
                      <!--Japan-->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-title">JAPAN</div>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_japan_player_count; ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_japan_active_player_count; ?></div>
                          </div>
                      </div>
                      <!--Europe-->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-title">EUROPE</div>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_europe_player_count; ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_europe_active_player_count; ?></div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">RACE AND GENDER DISTRIBUTION</span>
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="race_gender_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="active_race_gender_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">CLASS DISTRIBUTION</span>
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="class_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="active_class_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">REALM DISTRIBUTION</span>
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">AMERICAN REALMS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="america_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">JAPANESE REALMS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="japan_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">EUROPEAN REALMS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="europe_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->

                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">GRAND COMPANY DISTRIBUTION</span>
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="gc_distribution" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="gc_active_distribution" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                      <!-- End Chart -->
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">SUBSCRIBED TIME</span>
                      <div class="black-text light region-subtitle">30 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_30_days; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">60 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_60_days; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">90 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_90_days; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">180 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_180_days; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">270 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_270_days; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">360 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_360_days; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">450 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_450_days; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">630 DAYS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sub_630_days; ?></div>
                        </div>
                      </div>

                  </div>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">PRE-ORDERS</span>

                      <div class="black-text light region-subtitle">PRE-ORDERED ARR</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_prearr; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">PRE-ORDERED HW</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_prehw; ?></div>
                        </div>
                      </div>

                 </div>
              </div>
          </div>
      </div>


      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">COLLECTORS EDITION</span>

                      <div class="black-text light region-subtitle">PS4 COLLECTORS EDITION</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_ps4_collectors; ?></div>
                        </div>
                      </div>

                 </div>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">PHYSICAL ITEMS</span>

                      <div class="black-text light region-subtitle">ARR SOUNDTRACK</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_soundtrack; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">BEFORE METEOR SOUNDTRACK</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_beforemeteor; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">BEFORE THE FALL SOUNDTRACK (FIGURE COMING SOON!)</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_beforethefall; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">ARTBOOK</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_artbook; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">MOOGLE PLUSH</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_moogleplush; ?></div>
                        </div>
                      </div>

                 </div>
              </div>
          </div>
      </div>

    <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light"></span>
                      <!--World-->
                      <div class="black-text light region-title">View Previous Censuses</div>
                      <div class="black-text light region-subtitle"><a href="/old">I'M A BUTTON</a></div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo $fmt_player_count; ?></div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- End Container -->
  </div>
  <footer class="page-footer light-blue lighten-2">
      <div class="footer-copyright">
          <div class="container">
              Latest statistics generated between 2015-11-07 and 2015-11-16
              <div class="right"><a class="grey-text text-lighten-4" href="https://github.com/Pricetx/XIVStats">Source Code avaiailable on GitHub</a> - Feel free to submit any ideas you may have!</div>
          </div>
      </div>
  </footer>

  <script>
      $(function () {
          $('#gc_distribution').highcharts({
              chart: {
              },
              title: {
                  text: ''
              },
              plotOptions: {
                  pie: {
                      colors: ['#9e9e9e', '#212121', '#b71c1c', '#ffc107']
                  }
              },
              tooltip: {
                  pointFormat: '{point.y}'
              },
              credits: {
                  enabled: false
              },
              series: [{
                  type: 'pie',
                  name: '# of Characters',
                  data: [
                      <?php
                              foreach ($gc_distribution as $key => $value) {
                                      echo "['$key', $value,],\n";
                              }
                      ?>
                  ]
              }]
          });
      });
  </script>

  <script>
      $(function () {
          $('#gc_active_distribution').highcharts({
              chart: {
              },
              title: {
                  text: ''
              },
              plotOptions: {
                  pie: {
                      colors: ['#9e9e9e', '#212121', '#b71c1c', '#ffc107']
                  }
              },
              tooltip: {
                  pointFormat: '{point.y}'
              },
              credits: {
                  enabled: false
              },
              series: [{
                  type: 'pie',
                  name: '# of Characters',
                  data: [
                      <?php
                              foreach ($gc_active_distribution as $key => $value) {
                                      echo "['$key', $value,],\n";
                              }
                      ?>
                  ]
              }]
          });
      });
  </script>

  <script>
      $(function () {
          $('#race_gender_distribution').highcharts({
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: [
                      <?php
                              foreach ($race_gender_male as $key => $value) {
                                      echo "\"$key\",\n";
                              }
                      ?>
                  ],

              },
              yAxis: {
                  title: {
                      text: '# of Characters'
                  }
              },
              tooltip: {
                  pointFormat: '{point.y}'
              },
              credits: {
                  enabled: false
              },
              series: [{
                  name: 'Female',
                  data: [
                      <?php
                  echo "0,";
                              foreach ($race_gender_female as $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
                  echo "0,";
                              foreach ($race_gender_male as $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }]
          });
      });
  </script>

  <script>
      $(function () {
          $('#active_race_gender_distribution').highcharts({
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: [
                      <?php
                              foreach ($active_race_gender_male as $key => $value) {
                                      echo "\"$key\",\n";
                              }
                      ?>
                  ],

              },
              yAxis: {
                  title: {
                      text: '# of Characters'
                  }
              },
              tooltip: {
                  pointFormat: '{point.y}'
              },
              credits: {
                  enabled: false
              },
              series: [{
                  name: 'Female',
                  data: [
                      <?php
                  echo "0,";
                              foreach ($active_race_gender_female as $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
                  echo "0,";
                              foreach ($active_race_gender_male as $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }]
          });
      });
  </script>

  <script>
      $(function () {
          $('#class_distribution').highcharts({
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: [
                      <?php
                              foreach ($classes as $key => $value) {
                                      echo "'$key',";
                              }
                      ?>
                  ],

              },
              yAxis: {
                  title: {
                      text: '# of Characters'
                  }
              },
              tooltip: {
                  pointFormat: '{point.y}'
              },
              credits: {
                  enabled: false
              },
              series: [{
                  name: 'Classes',
                  data: [
                      <?php
                              foreach ($classes as $key => $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }]
          });
      });
  </script>

  <script>
      $(function () {
          $('#active_class_distribution').highcharts({
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: [
                      <?php
                              foreach ($active_classes as $key => $value) {
                                      echo "'$key',";
                              }
                      ?>
                  ],

              },
              yAxis: {
                  title: {
                      text: '# of Characters'
                  }
              },
              tooltip: {
                  pointFormat: '{point.y}'
              },
              credits: {
                  enabled: false
              },
              series: [{
                  name: 'Active Classes',
                  data: [
                      <?php
                              foreach ($active_classes as $key => $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }]
          });
      });
  </script>

<script>
$(function () {
    $('#america_realm_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($america_realm_pop as $key => $value) {
                                echo "'$key',";
                        }
                ?>
             ],

        },

        yAxis: {
            title: {
                text: '# of Characters'
            }
        },
        tooltip: {
            pointFormat: '{point.y}'
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'All',
            data: [
                <?php
                        foreach ($america_realm_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ],
        }, {
            name: 'Active',
            data: [
                <?php
                        foreach ($active_america_realm_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ],
        }]
    });
});
</script>


<script>
$(function () {
    $('#japan_realm_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($japan_realm_pop as $key => $value) {
                                echo "'$key',";
                        }
                ?>
             ],

        },

        yAxis: {
            title: {
                text: '# of Characters'
            }
        },
        tooltip: {
            pointFormat: '{point.y}'
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'All',
            data: [
                <?php
                        foreach ($japan_realm_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ],
        }, {
            name: 'Active',
            data: [
                <?php
                        foreach ($active_japan_realm_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ],
        }]
    });
});
</script>

<script>
$(function () {
    $('#europe_realm_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($europe_realm_pop as $key => $value) {
                                echo "'$key',";
                        }
                ?>
             ],

        },

        yAxis: {
            title: {
                text: '# of Characters'
            }
        },
        tooltip: {
            pointFormat: '{point.y}'
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'All',
            data: [
                <?php
                        foreach ($europe_realm_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ],
        }, {
            name: 'Active',
            data: [
                <?php
                        foreach ($active_europe_realm_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ],
        }]
    });
});
</script>


  </body>

</html>
