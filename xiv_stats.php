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

$american_realm_array = array("Behemoth","Brynhildr","Diabolos","Exodus","Famfrit","Hyperion",
                              "Lamia","Leviathan","Malboro","Ultros","Adamantoise","Balmung",
                              "Cactuar","Coeurl","Faerie","Gilgamesh","Goblin","Jenova","Mateus",
                              "Midgardsormr","Sargatanas","Siren","Zalera","Excalibur");
sort($american_realm_array);

$japanese_realm_array = array("Alexander","Bahamut","Durandal","Fenrir","Ifrit","Ridill","Tiamat","Ultima",
                              "Valefor","Yojimbo","Zeromus","Anima","Asura","Belias","Chocobo","Hades",
                              "Ixion","Mandragora","Masamune","Pandaemonium","Shinryu","Titan","Aegis",
                              "Atomos","Carbuncle","Garuda","Gungnir","Kujata","Ramuh","Tonberry","Typhon","Unicorn");
sort($japanese_realm_array);

$european_realm_array = array("Cerberus","Lich","Moogle","Odin","Phoenix","Ragnarok","Shiva","Zodiark","Louisoix","Omega");
sort($european_realm_array);

$active_check = "hw_33_complete = (1)";

$player_overview_query = $db->query("SELECT realm, grand_company, race, gender, COUNT(*), SUM(CASE WHEN hw_33_complete = (1) THEN 1 ELSE 0 END) FROM tblplayers GROUP BY realm, grand_company, race, gender;");
while($row = $player_overview_query->fetch_array()) {
        // Fetch total number of players in database
        $player_count += $row[4];
        // Fetch total number of active players in database
        $active_player_count += $row[5];
        // Fetch realm player counts
        $realm_count[$row[0]] += $row[4];
        // Fetch realm active player count
        $active_realm_count[$row[0]] += $row[5];
        // Fetch grand company player count
        $gc_count[$row[1]] += $row[4];
        // Fetch granc company active player count
        $active_gc_count[$row[1]] += $row[5];

        // Fetch race and gender player count
        $race_gender_count[$row[2]][$row[3]] += $row[4];
        // Fetch race and gender active player count
        $active_race_gender_count[$row[2]][$row[3]] += $row[5];
}

// Get statistics on class adoption
$classes = array();

$class_achievements_results = $db->query(
        "SELECT
                SUM(CASE WHEN level_gladiator > 0 THEN 1 ELSE 0 END) AS 'gladiator',
                SUM(CASE WHEN level_gladiator >= 60 THEN 1 ELSE 0 END) AS 'gladiator_active',
                SUM(CASE WHEN level_pugilist > 0 THEN 1 ELSE 0 END) AS 'pugilist',
                SUM(CASE WHEN level_pugilist >= 60 THEN 1 ELSE 0 END) AS 'pugilist_active',
                SUM(CASE WHEN level_marauder > 0 THEN 1 ELSE 0 END) AS 'marauder',
                SUM(CASE WHEN level_marauder >= 60 THEN 1 ELSE 0 END) AS 'marauder_active',
                SUM(CASE WHEN level_lancer > 0 THEN 1 ELSE 0 END) AS 'lancer',
                SUM(CASE WHEN level_lancer >= 60 THEN 1 ELSE 0 END) AS 'lancer_active',
                SUM(CASE WHEN level_archer > 0 THEN 1 ELSE 0 END) AS 'archer',
                SUM(CASE WHEN level_archer >= 60 THEN 1 ELSE 0 END) AS 'archer_active',
                SUM(CASE WHEN level_rogue > 0 THEN 1 ELSE 0 END) AS 'rogue',
                SUM(CASE WHEN level_rogue >= 60 THEN 1 ELSE 0 END) AS 'rogue_active',
                SUM(CASE WHEN level_conjurer > 0 THEN 1 ELSE 0 END) AS 'conjurer',
                SUM(CASE WHEN level_conjurer >= 60 THEN 1 ELSE 0 END) AS 'conjurer_active',
                SUM(CASE WHEN level_thaumaturge > 0 THEN 1 ELSE 0 END) AS 'thaumaturge',
                SUM(CASE WHEN level_thaumaturge >= 60 THEN 1 ELSE 0 END) AS 'thaumaturge_active',
                SUM(CASE WHEN level_arcanist > 0 THEN 1 ELSE 0 END) AS 'arcanist',
                SUM(CASE WHEN level_arcanist >= 60 THEN 1 ELSE 0 END) AS 'arcanist_active',
                SUM(CASE WHEN level_scholar > 0 THEN 1 ELSE 0 END) AS 'scholar',
                SUM(CASE WHEN level_scholar >= 60 THEN 1 ELSE 0 END) AS 'scholar_active',
                SUM(CASE WHEN level_darkknight > 0 THEN 1 ELSE 0 END) AS 'darkknight',
                SUM(CASE WHEN level_darkknight >= 60 THEN 1 ELSE 0 END) AS 'darkknight_active',
                SUM(CASE WHEN level_machinist > 0 THEN 1 ELSE 0 END) AS 'machinist',
                SUM(CASE WHEN level_machinist >= 60 THEN 1 ELSE 0 END) AS 'machinist_active',
                SUM(CASE WHEN level_astrologian > 0 THEN 1 ELSE 0 END) AS 'astrologian',
                SUM(CASE WHEN level_astrologian >= 60 THEN 1 ELSE 0 END) AS 'astrologian_active',
                SUM(CASE WHEN level_samurai > 0 THEN 1 ELSE 0 END) AS 'samurai',
                SUM(CASE WHEN level_samurai >= 60 THEN 1 ELSE 0 END) AS 'samurai_active',
                SUM(CASE WHEN level_redmage > 0 THEN 1 ELSE 0 END) AS 'redmage',
                SUM(CASE WHEN level_redmage >= 60 THEN 1 ELSE 0 END) AS 'redmage_active',
                SUM(CASE WHEN level_carpenter > 0 THEN 1 ELSE 0 END) AS 'carpenter',
                SUM(CASE WHEN level_carpenter >= 60 THEN 1 ELSE 0 END) AS 'carpenter_active',
                SUM(CASE WHEN level_blacksmith > 0 THEN 1 ELSE 0 END) AS 'blacksmith',
                SUM(CASE WHEN level_blacksmith >= 60 THEN 1 ELSE 0 END) AS 'blacksmith_active',
                SUM(CASE WHEN level_armorer > 0 THEN 1 ELSE 0 END) AS 'armorer',
                SUM(CASE WHEN level_armorer >= 60 THEN 1 ELSE 0 END) AS 'armorer_active',
                SUM(CASE WHEN level_goldsmith > 0 THEN 1 ELSE 0 END) AS 'goldsmith',
                SUM(CASE WHEN level_goldsmith >= 60 THEN 1 ELSE 0 END) AS 'goldsmith_active',
                SUM(CASE WHEN level_leatherworker > 0 THEN 1 ELSE 0 END) AS 'leatherworker',
                SUM(CASE WHEN level_leatherworker >= 60 THEN 1 ELSE 0 END) AS 'leatherworker_active',
                SUM(CASE WHEN level_weaver > 0 THEN 1 ELSE 0 END) AS 'weaver',
                SUM(CASE WHEN level_weaver >= 60 THEN 1 ELSE 0 END) AS 'weaver_active',
                SUM(CASE WHEN level_alchemist > 0 THEN 1 ELSE 0 END) AS 'alchemist',
                SUM(CASE WHEN level_alchemist >= 60 THEN 1 ELSE 0 END) AS 'alchemist_active',
                SUM(CASE WHEN level_culinarian > 0 THEN 1 ELSE 0 END) AS 'culinarian',
                SUM(CASE WHEN level_culinarian >= 60 THEN 1 ELSE 0 END) AS 'culinarian_active',
                SUM(CASE WHEN level_miner > 0 THEN 1 ELSE 0 END) AS 'miner',
                SUM(CASE WHEN level_miner >= 60 THEN 1 ELSE 0 END) AS 'miner_active',
                SUM(CASE WHEN level_botanist > 0 THEN 1 ELSE 0 END) AS 'botanist',
                SUM(CASE WHEN level_botanist >= 60 THEN 1 ELSE 0 END) AS 'botanist_active',
                SUM(CASE WHEN level_fisher > 0 THEN 1 ELSE 0 END) AS 'fisher',
                SUM(CASE WHEN level_fisher >= 60 THEN 1 ELSE 0 END) AS 'fisher_active',
                SUM(p30days),
                SUM(p60days),
                SUM(p90days),
                SUM(p180days),
                SUM(p270days),
                SUM(p360days),
                SUM(p450days),
                SUM(p630days),
                SUM(p960days),
                SUM(prearr),
                SUM(prehw),
                SUM(presb),
                SUM(ps4collectors),
                SUM(arrcollector),
                SUM(arrartbook),
                SUM(beforemeteor),
                SUM(beforethefall),
                SUM(soundtrack),
                SUM(moogleplush),
                SUM(saweternalbond),
                SUM(dideternalbond),
                SUM(comm50),
                SUM(hildibrand),
                SUM(sightseeing),
                SUM(kobold),
                SUM(sahagin),
                SUM(amaljaa),
                SUM(sylph)
         FROM
                tblplayers;");

$results = $class_achievements_results->fetch_array();

$classes["Gladiator"] = $results[0];
$classes["Pugilist"] = $results[2];
$classes["Marauder"] = $results[4];
$classes["Lancer"] = $results[6];
$classes["Archer"] = $results[8];
$classes["Rogue"] = $results[10];
$classes["Conjurer"] = $results[12];
$classes["Thaumaturge"] = $results[14];
$classes["Arcanist"] = $results[16];
$classes["Scholar"] = $results[18];
$classes["Dark Knight"] = $results[20];
$classes["Machinist"] = $results[22];
$classes["Astrologian"] = $results[24];
$classes["Samurai"] = $results[26];
$classes["Red Mage"] = $results[28];
$classes["Carpenter"] = $results[30];
$classes["Blacksmith"] = $results[32];
$classes["Armorer"] = $results[34];
$classes["Goldsmith"] = $results[36];
$classes["Leatherworker"] = $results[38];
$classes["Weaver"] = $results[40];
$classes["Alchemist"] = $results[42];
$classes["Culinarian"] = $results[44];
$classes["Miner"] = $results[46];
$classes["Botanist"] = $results[48];
$classes["Fisher"] = $results[50];

$active_classes["Gladiator"] = $results[1];
$active_classes["Pugilist"] = $results[3];
$active_classes["Marauder"] = $results[5];
$active_classes["Lancer"] = $results[7];
$active_classes["Archer"] = $results[9];
$active_classes["Rogue"] = $results[11];
$active_classes["Conjurer"] = $results[13];
$active_classes["Thaumaturge"] = $results[15];
$active_classes["Arcanist"] = $results[17];
$active_classes["Scholar"] = $results[19];
$active_classes["Dark Knight"] = $results[21];
$active_classes["Machinist"] = $results[23];
$active_classes["Astrologian"] = $results[25];
$active_classes["Samurai"] = $results[27];
$active_classes["Red Mage"] = $results[29];
$active_classes["Carpenter"] = $results[31];
$active_classes["Blacksmith"] = $results[33];
$active_classes["Armorer"] = $results[35];
$active_classes["Goldsmith"] = $results[37];
$active_classes["Leatherworker"] = $results[39];
$active_classes["Weaver"] = $results[41];
$active_classes["Alchemist"] = $results[43];
$active_classes["Culinarian"] = $results[45];
$active_classes["Miner"] = $results[47];
$active_classes["Botanist"] = $results[49];
$active_classes["Fisher"] = $results[51];

// Subscription figures
$sub_time["30 Days"] = $results[52];
$sub_time["60 Days"] = $results[53];
$sub_time["90 Days"] = $results[54];
$sub_time["180 Days"] =  $results[55];
$sub_time["270 Days"] = $results[56];
$sub_time["360 Days"] = $results[57];
$sub_time["450 Days"] = $results[58];
$sub_time["630 Days"] = $results[59];
$sub_time["960 Days"] = $results[60];

// Pre-orders
$prearr = $results[61];
$fmt_prearr = number_format($prearr);
$prehw = $results[62];
$fmt_prehw = number_format($prehw);
$presb = $results[63];
$fmt_presb = number_format($presb);

// Collectors Edition
$ps4_collectors = $results[64];
$fmt_ps4_collectors = number_format($ps4_collectors);
$pc_collectors = $results[65];
$fmt_pc_collectors = number_format($pc_collectors);

// Physical Items
$arrartbook = $results[66];
$fmt_arrartbook = number_format($arrartbook);
$beforemeteor = $results[67];
$fmt_beforemeteor = number_format($beforemeteor);
$beforethefall = $results[68];
$fmt_beforethefall = number_format($beforethefall);
$soundtrack = $results[69];
$fmt_soundtrack = number_format($soundtrack);
$moogleplush = $results[70];
$fmt_moogleplush = number_format($moogleplush);

// Eternal Bond
$saw_eternal_bond = $results[71];
$fmt_saw_eternal_bond = number_format($saw_eternal_bond);
$did_eternal_bond = $results[72];
$fmt_did_eternal_bond = number_format($did_eternal_bond);

// Player Commendations
$comm50 = $results[73];
$fmt_comm50 = number_format($comm50);

// Hildibrand
$hildibrand = $results[74];
$fmt_hildibrand = number_format($hildibrand);

// ARR Sightseeing Log
$sightseeing = $results[75];
$fmt_sightseeing = number_format($sightseeing);

// Beast Tribes

$beast_tribes["Kobold"] = $results[76];
$beast_tribes["Sahagin"] = $results[77];
$beast_tribes["Amaljaa"] = $results[78];
$beast_tribes["Sylph"] = $results[79];

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
                              <div><?php echo number_format($player_count) ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format($active_player_count) ?></div>
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

                              <div><?php echo number_format(array_sum(array_intersect_key($realm_count, array_flip($american_realm_array)))) ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(array_sum(array_intersect_key($active_realm_count, array_flip($american_realm_array)))) ?></div>
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
                              <div><?php echo number_format(array_sum(array_intersect_key($realm_count, array_flip($japanese_realm_array)))) ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(array_sum(array_intersect_key($active_realm_count, array_flip($japanese_realm_array)))) ?></div>
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
                              <div><?php echo number_format(array_sum(array_intersect_key($realm_count, array_flip($european_realm_array)))) ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(array_sum(array_intersect_key($active_realm_count, array_flip($european_realm_array)))) ?></div>
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

                      <div class="black-text light region-subtitle">PRE-ORDERED STORMBLOOD</div>
                      <div class="row">
                        <div class=" s12 m6 l6   region-stat">
                          <div><?php echo $fmt_presb; ?></div>
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
              <div class="right"><a class="grey-text text-lighten-4" href="https://github.com/XIVStats">Source Code available on GitHub</a> - Feel free to submit any ideas you may have!</div>
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
                              foreach ($gc_count as $key => $value) {
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
                              foreach ($active_gc_count as $key => $value) {
                                      if($key != "none") echo "['$key', $value,],\n";
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
                              foreach ($race_gender_count as $key => $value) {
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
                              foreach ($race_gender_count as $value) {
                                      echo $value["female"] . ",";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
                              foreach ($race_gender_count as $value) {
                                      echo $value["male"] . ",";
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
                              foreach ($active_race_gender_count as $key => $value) {
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
                              foreach ($active_race_gender_count as $value) {
                                      echo $value["female"] . ",";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
                              foreach ($active_race_gender_count as $value) {
                                      echo $value["male"] . ",";
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
                        foreach ($american_realm_array as $key => $value) {
                                echo "'$value',";
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
                        foreach ($american_realm_array as $value) {
                                echo (is_null($realm_count[$value]) ? 0 : $realm_count[$value]) . ",";
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
                        foreach ($american_realm_array as $key => $value) {
                                echo "'$value',";
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
                        foreach ($american_realm_array as $value) {
                                echo (is_null($active_realm_count[$value]) ? 0 : $active_realm_count[$value]) . ",";
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
                        foreach ($japanese_realm_array as $key => $value) {
                                echo "'$value',";
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
                        foreach ($japanese_realm_array as $value) {
                                echo (is_null($realm_count[$value]) ? 0 : $realm_count[$value]) . ",";
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
                        foreach ($japanese_realm_array as $key => $value) {
                                echo "'$value',";
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
                        foreach ($japanese_realm_array as $value) {
                                echo (is_null($active_realm_count[$value]) ? 0 : $active_realm_count[$value]) . ",";
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
                        foreach ($european_realm_array as $key => $value) {
                                echo "'$value',";
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
                        foreach ($european_realm_array as $value) {
                                echo (is_null($realm_count[$value]) ? 0 : $realm_count[$value]) . ",";
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
                        foreach ($european_realm_array as $key => $value) {
                                echo "'$value',";
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
                        foreach ($european_realm_array as $value) {
                                echo (is_null($active_realm_count[$value]) ? 0 : $active_realm_count[$value]) . ",";
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
