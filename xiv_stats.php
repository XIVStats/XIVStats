<?php

$conn_info = parse_ini_file("templateconfig.ini");

$date = date("F Y");

// Create DB Connection
$db = new mysqli($conn_info["host"], $conn_info["username"], $conn_info["password"]);

// Check DB Connection
if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}

// Select DB
if (! $db->select_db($conn_info["database"])) {
        die("Couldn't find DB");
}

$active_check = "hw_33_complete = (1)";

$american_realms = "(realm = 'Behemoth' OR realm = 'Brynhildr' OR realm = 'Diabolos'
        OR realm = 'Excalibur' OR realm = 'Exodus' OR realm = 'Famfrit' OR realm = 'Hyperion' OR realm = 'Lamia' OR realm = 'Leviathan'
        OR realm = 'Malboro' OR realm = 'Ultros' OR realm = 'Adamantoise' OR realm = 'Balmung' OR realm = 'Cactuar' OR realm = 'Coeurl'
        OR realm = 'Faerie' OR realm = 'Gilgamesh' OR realm = 'Goblin' OR realm = 'Jenova' OR realm = 'Mateus' OR realm = 'Midgardsormr'
        OR realm = 'Sargatanas' OR realm = 'Siren' OR realm = 'Zalera')";

$japanese_realms = "(realm = 'Alexander' OR realm = 'Bahamut' OR realm = 'Durandal'
        OR realm = 'Fenrir' OR realm = 'Ifrit' OR realm = 'Ridill' OR realm = 'Tiamat' OR realm = 'Ultima' OR realm = 'Valefor' OR
        realm = 'Yojimbo'  OR realm = 'Zeromus' OR realm = 'Anima' OR realm = 'Asura' OR realm = 'Belias' OR realm = 'Chocobo' OR
        realm = 'Hades' OR realm = 'Ixion' OR realm = 'Mandragora' OR realm = 'Masamune' OR realm = 'Pandaemonium' OR realm = 'Shinryu'
        OR realm = 'Titan' OR realm = 'Aegis' OR realm = 'Atomos' OR realm = 'Carbuncle' OR realm = 'Garuda' OR realm = 'Gungnir'
        OR realm = 'Kujata' OR realm = 'Ramuh' OR realm = 'Tonberry' OR realm = 'Typhon' OR realm = 'Unicorn')";

$european_realms = "(realm = 'Cerberus' OR realm = 'Lich' OR realm = 'Moogle' OR
        realm = 'Odin' OR realm = 'Phoenix' OR realm = 'Ragnarok' OR realm = 'Shiva' OR realm = 'Zodiark' OR realm = 'Louisoix' OR realm = 'Omega')";

// Fetch total number of players in database
$player_count_query = $db->query("SELECT count(*) FROM tblplayers;");
$player_count = $player_count_query->fetch_array()[0];
$fmt_player_count = number_format($player_count);

$active_player_count_query = $db->query("SELECT count(*) FROM tblplayers WHERE " . $active_check . ";");
$active_player_count = $active_player_count_query->fetch_array()[0];
$fmt_active_player_count = number_format($active_player_count);

// Fetch total number of players in each region
// America
$america_player_count_query = $db->query("SELECT count(*) FROM tblplayers WHERE " . $american_realms);
$america_player_count = $america_player_count_query->fetch_array()[0];
$fmt_america_player_count = number_format($america_player_count);

$america_active_player_count_query = $db->query("SELECT count(*) FROM tblplayers WHERE " . $american_realms . " AND " . $active_check);
$america_active_player_count = $america_active_player_count_query->fetch_array()[0];
$fmt_america_active_player_count = number_format($america_active_player_count);

//Japan
$japan_player_count_query = $db->query("SELECT count(*) FROM tblplayers WHERE " . $japanese_realms);
$japan_player_count = $japan_player_count_query->fetch_array()[0];
$fmt_japan_player_count = number_format($japan_player_count);

$japan_active_player_count_query = $db->query("SELECT count(*) FROM tblplayers WHERE " . $japanese_realms . " AND " . $active_check);
$japan_active_player_count = $japan_active_player_count_query->fetch_array()[0];
$fmt_japan_active_player_count = number_format($japan_active_player_count);

//Europe
$europe_player_count_query = $db->query("SELECT count(*) FROM tblplayers WHERE " . $european_realms);
$europe_player_count = $europe_player_count_query->fetch_array()[0];
$fmt_europe_player_count = number_format($europe_player_count);

$europe_active_player_count_query = $db->query("SELECT count(*) FROM tblplayers WHERE " . $european_realms . " AND " . $active_check);
$europe_active_player_count = $europe_active_player_count_query->fetch_array()[0];
$fmt_europe_active_player_count = number_format($europe_active_player_count);

// Grand Company Distribution
$gc_distribution = array();
$gc_distribution_query = $db->query("SELECT grand_company,count(*) FROM tblplayers GROUP BY grand_company");

while ($row = $gc_distribution_query->fetch_array()) {
        //$row[0] = grand company name
        //$row[1] = population

        //If user is not in gc, mark gc as none rather than blank
        if ($row[0] === '') { $row[0] = "None"; }
        $gc_distribution[$row[0]] = $row[1];
}

$gc_active_distribution = array();
$gc_active_distribution_query = $db->query("select grand_company,count(*) from tblplayers WHERE " . $active_check . " group by grand_company");

while ($row = $gc_active_distribution_query->fetch_array()) {
        //$row[0] = grand company name
        //$row[1] = population

        //If user is not in gc, mark gc as none rather than blank
        if ($row[0] === '') { $row[0] = "None"; }
        $gc_active_distribution[$row[0]] = $row[1];
}


// Race / Gender Distribution

$race_gender_male = array();
$race_gender_female = array();
$race_gender_query = $db->query("SELECT race,gender,count(*) FROM tblplayers GROUP BY race,gender");

while ($row = $race_gender_query->fetch_array()) {
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
$active_race_gender_query = $db->query("SELECT race,gender,count(*) FROM tblplayers WHERE " . $active_check . " GROUP BY race,gender");

while ($row = $active_race_gender_query->fetch_array()) {
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

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_gladiator != ''");
$classes["Gladiator"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_pugilist != ''");
$classes["Pugilist"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_marauder != ''");
$classes["Marauder"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_lancer != ''");
$classes["Lancer"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_archer != ''");
$classes["Archer"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_rogue != ''");
$classes["Rogue"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_conjurer != ''");
$classes["Conjurer"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_thaumaturge != ''");
$classes["Thaumaturge"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_arcanist != ''");
$classes["Arcanist"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_darkknight != ''");
$classes["Dark Knight"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_machinist != ''");
$classes["Machinist"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_astrologian != ''");
$classes["Astrologian"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_samurai != ''");
$classes["Samurai"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_redmage != ''");
$classes["Red Mage"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_carpenter != ''");
$classes["Carpenter"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_blacksmith != ''");
$classes["Blacksmith"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_armorer != ''");
$classes["Armorer"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_goldsmith != ''");
$classes["Goldsmith"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_leatherworker != ''");
$classes["Leatherworker"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_weaver != ''");
$classes["Weaver"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_alchemist != ''");
$classes["Alchemist"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_culinarian != ''");
$classes["Culinarian"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_miner != ''");
$classes["Miner"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_botanist != ''");
$classes["Botanist"] = $class_results->fetch_array()[0];

$class_results = $db->query("SELECT count(*) FROM tblplayers WHERE level_fisher != ''");
$classes["Fisher"] = $class_results->fetch_array()[0];

// Get statistics on active class adoption
$active_classes = array();

$class_results = $db->query("select count(*) from tblplayers where level_gladiator >= '60' AND level_gladiator != ''");
$active_classes["Gladiator"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_pugilist >= '60' AND level_pugilist != ''");
$active_classes["Pugilist"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_marauder >= '60' AND level_marauder != ''");
$active_classes["Marauder"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_lancer >= '60' AND level_lancer != ''");
$active_classes["Lancer"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_archer >= '60' AND level_archer != ''");
$active_classes["Archer"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_rogue >= '60' AND level_rogue != ''");
$active_classes["Rogue"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_conjurer >= '60' AND level_conjurer != ''");
$active_classes["Conjurer"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_thaumaturge >= '60' AND level_thaumaturge != ''");
$active_classes["Thaumaturge"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_arcanist >= '60' AND level_arcanist != ''");
$active_classes["Arcanist"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_darkknight >= '60' AND level_darkknight != ''");
$active_classes["Dark Knight"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_machinist >= '60' AND level_machinist != ''");
$active_classes["Machinist"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_astrologian >= '60' AND level_astrologian != ''");
$active_classes["Astrologian"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_samurai >= '60' AND level_samurai != ''");
$active_classes["Samurai"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_redmage >= '60' AND level_redmage != ''");
$active_classes["Red Mage"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_carpenter >= '60' AND level_carpenter != ''");
$active_classes["Carpenter"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_blacksmith >= '60' AND level_blacksmith != ''");
$active_classes["Blacksmith"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_armorer >= '60' AND level_blacksmith != ''");
$active_classes["Armorer"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_goldsmith >= '60' AND level_goldsmith != ''");
$active_classes["Goldsmith"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_leatherworker >= '60' AND level_leatherworker != ''");
$active_classes["Leatherworker"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_weaver >= '60' AND level_weaver != ''");
$active_classes["Weaver"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_alchemist >= '60' AND level_alchemist != ''");
$active_classes["Alchemist"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_culinarian >= '60' AND level_culinarian != ''");
$active_classes["Culinarian"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_miner >= '60' AND level_miner != ''");
$active_classes["Miner"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_botanist >= '60' AND level_botanist != ''");
$active_classes["Botanist"] = $class_results->fetch_array()[0];

$class_results = $db->query("select count(*) from tblplayers where level_fisher >= '60' AND level_fisher != ''");
$active_classes["Fisher"] = $class_results->fetch_array()[0];

// Get statistics on realm population
$america_realm_pop = array();
$america_realm_pop_query = $db->query("SELECT realm,count(*) FROM tblplayers WHERE " . $american_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $america_realm_pop_query->fetch_array()) {
        //$row[0] = realm name
        //$row[1] = population
        $america_realm_pop[$row[0]] = $row[1];
}

$active_america_realm_pop = array();
$active_america_realm_pop_query = $db->query("SELECT realm,count(*) FROM tblplayers WHERE " . $active_check . " AND " . $american_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $active_america_realm_pop_query->fetch_array()) {
        //$row[0] = realm name
        //$row[1] = population
        $active_america_realm_pop[$row[0]] = $row[1];
}

$japan_realm_pop = array();
$japan_realm_pop_query = $db->query("SELECT realm,count(*) FROM tblplayers WHERE " . $japanese_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $japan_realm_pop_query->fetch_array()) {
        //$row[0] = realm name
        //$row[1] = population
        $japan_realm_pop[$row[0]] = $row[1];
}

$active_japan_realm_pop = array();
$active_japan_realm_pop_query = $db->query("SELECT realm,count(*) FROM tblplayers WHERE " . $active_check . " AND " . $japanese_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $active_japan_realm_pop_query->fetch_array()) {
        //$row[0] = realm name
        //$row[1] = population
        $active_japan_realm_pop[$row[0]] = $row[1];
}

$europe_realm_pop = array();
$europe_realm_pop_query = $db->query("SELECT realm,count(*) FROM tblplayers WHERE " . $european_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $europe_realm_pop_query->fetch_array()) {
        //$row[0] = realm name
        //$row[1] = population
        $europe_realm_pop[$row[0]] = $row[1];
}

$active_europe_realm_pop = array();
$active_europe_realm_pop_query = $db->query("SELECT realm,count(*) FROM tblplayers WHERE " . $active_check . " AND " . $european_realms . " GROUP BY realm ORDER BY realm ASC");

while ($row = $active_europe_realm_pop_query->fetch_array()) {
        //$row[0] = realm name
        //$row[1] = population
        $active_europe_realm_pop[$row[0]] = $row[1];
}



// Subscription figures

$sub_time = array();

$sub_30_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p30days = (1);");
$sub_time["30 Days"] = $sub_30_days_query->fetch_array()[0];

$sub_60_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p60days = (1);");
$sub_time["60 Days"] = $sub_60_days_query->fetch_array()[0];

$sub_90_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p90days = (1);");
$sub_time["90 Days"] = $sub_90_days_query->fetch_array()[0];

$sub_180_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p180days = (1);");
$sub_time["180 Days"] =  $sub_180_days_query->fetch_array()[0];

$sub_270_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p270days = (1);");
$sub_time["270 Days"] = $sub_270_days_query->fetch_array()[0];

$sub_360_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p360days = (1);");
$sub_time["360 Days"] = $sub_360_days_query->fetch_array()[0];

$sub_450_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p450days = (1);");
$sub_time["450 Days"] = $sub_450_days_query->fetch_array()[0];

$sub_630_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p630days = (1);");
$sub_time["630 Days"] = $sub_630_days_query->fetch_array()[0];

$sub_960_days_query = $db->query("SELECT count(*) FROM tblplayers WHERE p960days = (1);");
$sub_time["960 Days"] = $sub_960_days_query->fetch_array()[0];

// Pre-orders

$prearr_query = $db->query("SELECT count(*) FROM tblplayers WHERE prearr = (1);");
$prearr = $prearr_query->fetch_array()[0];
$fmt_prearr = number_format($prearr);

$prehw_query = $db->query("SELECT count(*) FROM tblplayers WHERE prehw = (1);");
$prehw = $prehw_query->fetch_array()[0];
$fmt_prehw = number_format($prehw);

// Collectors Edition

$ps4_collectors_query = $db->query("SELECT count(*) FROM tblplayers WHERE ps4collectors = (1);");
$ps4_collectors = $ps4_collectors_query->fetch_array()[0];
$fmt_ps4_collectors = number_format($ps4_collectors);

$pc_collectors_query = $db->query("SELECT count(*) FROM tblplayers WHERE arrcollector = (1);");
$pc_collectors = $pc_collectors_query->fetch_array()[0];
$fmt_pc_collectors = number_format($pc_collectors);

// Physical Items

$arrartbook_query = $db->query("SELECT count(*) FROM tblplayers WHERE arrartbook = (1);");
$arrartbook = $arrartbook_query->fetch_array()[0];
$fmt_arrartbook = number_format($arrartbook);

$beforemeteor_query = $db->query("SELECT count(*) FROM tblplayers WHERE beforemeteor = (1);");
$beforemeteor = $beforemeteor_query->fetch_array()[0];
$fmt_beforemeteor = number_format($beforemeteor);

$beforethefall_query = $db->query("SELECT count(*) FROM tblplayers WHERE beforethefall = (1);");
$beforethefall = $beforethefall_query->fetch_array()[0];
$fmt_beforethefall = number_format($beforethefall);

$soundtrack_query = $db->query("SELECT count(*) FROM tblplayers WHERE soundtrack = (1);");
$soundtrack = $soundtrack_query->fetch_array()[0];
$fmt_soundtrack = number_format($soundtrack);

$moogleplush_query = $db->query("SELECT count(*) FROM tblplayers WHERE moogleplush = (1);");
$moogleplush = $moogleplush_query->fetch_array()[0];
$fmt_moogleplush = number_format($moogleplush);

// Eternal Bond

$saw_eternal_bond_query = $db->query("SELECT count(*) FROM tblplayers WHERE saweternalbond = (1);");
$saw_eternal_bond = $saw_eternal_bond_query->fetch_array()[0];
$fmt_saw_eternal_bond = number_format($saw_eternal_bond);

$did_eternal_bond_query = $db->query("SELECT count(*) FROM tblplayers WHERE dideternalbond = (1);");
$did_eternal_bond = $did_eternal_bond_query->fetch_array()[0];
$fmt_did_eternal_bond = number_format($did_eternal_bond);

// Player Commendations

$comm50_query = $db->query("SELECT count(*) FROM tblplayers WHERE comm50 = (1);");
$comm50 = $comm50_query->fetch_array()[0];
$fmt_comm50 = number_format($comm50);

// Hildibrand

$hildibrand_query = $db->query("SELECT count(*) FROM tblplayers WHERE hildibrand = (1);");
$hildibrand = $hildibrand_query->fetch_array()[0];
$fmt_hildibrand = number_format($hildibrand);

// ARR Sightseeing Log

$sightseeing_query = $db->query("SELECT count(*) FROM tblplayers WHERE sightseeing = (1);");
$sightseeing = $sightseeing_query->fetch_array()[0];
$fmt_sightseeing = number_format($sightseeing);

// Beast Tribes

$beast_tribes = array();

$kobold_query = $db->query("SELECT count(*) FROM tblplayers WHERE kobold = (1);");
$beast_tribes["Kobold"] = $kobold_query->fetch_array()[0];

$sahagin_query = $db->query("SELECT count(*) FROM tblplayers WHERE sahagin = (1);");
$beast_tribes["Sahagin"] = $sahagin_query->fetch_array()[0];

$amaljaa_query = $db->query("SELECT count(*) FROM tblplayers WHERE amaljaa = (1);");
$beast_tribes["Amaljaa"] = $amaljaa_query->fetch_array()[0];

$sylph_query = $db->query("SELECT count(*) FROM tblplayers WHERE sylph = (1);");
$beast_tribes["Sylph"] = $sylph_query->fetch_array()[0];

// Close DB Connection
$db->close();

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

      <!-- Google Analytics -->
      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-93918116-1', 'auto');
        ga('send', 'pageview');
      </script>
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
                      <br/>Statistics for <?php echo $date; ?>
                      <p><b>* (Any reference to "Active" players, refers to players who have had their profile image update in the last 4 weeks)</b></p>
                      <p><b>NOTE: The metric for "Active" players changed between February and March 2017</b></p>
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
                      <span class="card-title black-text light">REALM DISTRIBUTION (ALL)</span>
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
                      <span class="card-title black-text light">REALM DISTRIBUTION (ACTIVE)</span>
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">AMERICAN REALMS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="america_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">JAPANESE REALMS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="japan_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">EUROPEAN REALMS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="europe_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
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
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="subscribed_time" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->

                  </div>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <span class="card-title black-text light">BEAST TRIBES (RANK 4 OR HIGHER)</span>

                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="beast_tribes" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->

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

                      <div class="black-text light region-subtitle">PS4 ARR COLLECTORS EDITION</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_ps4_collectors; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">PC ARR COLLECTORS EDITION</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_pc_collectors; ?></div>
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

                      <div class="black-text light region-subtitle">BEFORE THE FALL SOUNDTRACK</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_beforethefall; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">ARTBOOK</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_arrartbook; ?></div>
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
                      <span class="card-title black-text light">OTHER</span>

                      <div class="black-text light region-subtitle">GUEST AT AN ETERNAL BOND</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_saw_eternal_bond; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">MARRIED AT AN ETERNAL BOND</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_did_eternal_bond; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">EARNED 50 COMMENDATIONS</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_comm50; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">COMPLETED ARR HILDIBRAND QUESTLINE</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_hildibrand; ?></div>
                        </div>
                      </div>

                      <div class="black-text light region-subtitle">COMPLETED ARR SIGHTSEEING LOG</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_sightseeing; ?></div>
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
                      <div class="black-text light region-title"><a href="<?php echo "/" . date("Y-m") . "/ffxivcensus-" . date("Y-m") . ".zip";?>">Download database (MySQL)</a></div>
                  </div>

                  <div class="card-content black-text">
                      <span class="card-title black-text light"></span>
                      <div class="black-text light region-title"><a href="/list">View Previous Censuses</a></div>
                  </div>
              </div>
          </div>
      </div>

      <!-- End Container -->
  </div>
  <footer class="page-footer light-blue lighten-2">
      <div class="footer-copyright">
          <div class="container">
              Statistics generated on <?php echo date("Y-m-d");  ?>
              <div class="right"><a class="grey-text text-lighten-4" href="https://github.com/XIVStats">Source Code avaiailable on GitHub</a> - Feel free to submit any ideas you may have!</div>
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
                      colors: ['#212121', '#b71c1c', '#9e9e9e',  '#ffc107']
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
                      colors: ['#212121', '#b71c1c',  '#ffc107']
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
                              foreach ($race_gender_female as $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
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
                              foreach ($active_race_gender_female as $value) {
                                      echo "$value,";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
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
        }]
    });
});
</script>

<script>
$(function () {
    $('#america_active_realm_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($active_america_realm_pop as $key => $value) {
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
        }]
    });
});
</script>

<script>
$(function () {
    $('#japan_active_realm_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($active_japan_realm_pop as $key => $value) {
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
        }]
    });
});
</script>

<script>
$(function () {
    $('#europe_active_realm_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($active_europe_realm_pop as $key => $value) {
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

  <script>
      $(function () {
          $('#subscribed_time').highcharts({
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: [
                      <?php
                              foreach ($sub_time as $key => $value) {
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
                  name: 'Subscription Time',
                  data: [
                      <?php
                              foreach ($sub_time as $key => $value) {
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
          $('#beast_tribes').highcharts({
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: [
                      <?php
                              foreach ($beast_tribes as $key => $value) {
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
                  name: 'Tribe',
                  data: [
                      <?php
                              foreach ($beast_tribes as $key => $value) {
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
