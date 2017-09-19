<?php

// Helper function to fetch the sum of all values in the array, where the array key matches one of the specified realm names
function sumInRegion($data, $regional_realms) {
        return array_sum(array_intersect_key($data, array_flip($regional_realms)));
}

// Helper function to return the value of the requested key, or zero if one isn't available
function getValueOrZero($data, $key) {
        return isset($data[$key]) ? $data[$key] : 0;
}

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

// Variables
$player_count = 0;
$active_player_count = 0;
$realm_count = array();
$active_realm_count = array();
$gc_count = array();
$active_gc_count = array();
$race_gender_count = array();
$active_race_gender_count = array();

$classes = array();
$classes["Gladiator"] = 0;
$classes["Pugilist"] = 0;
$classes["Marauder"] = 0;
$classes["Lancer"] = 0;
$classes["Archer"] = 0;
$classes["Rogue"] = 0;
$classes["Conjurer"] = 0;
$classes["Thaumaturge"] = 0;
$classes["Arcanist"] = 0;
$classes["Scholar"] = 0;
$classes["Dark Knight"] = 0;
$classes["Machinist"] = 0;
$classes["Astrologian"] = 0;
$classes["Samurai"] = 0;
$classes["Red Mage"] = 0;
$classes["Carpenter"] = 0;
$classes["Blacksmith"] = 0;
$classes["Armorer"] = 0;
$classes["Goldsmith"] = 0;
$classes["Leatherworker"] = 0;
$classes["Weaver"] = 0;
$classes["Alchemist"] = 0;
$classes["Culinarian"] = 0;
$classes["Miner"] = 0;
$classes["Botanist"] = 0;
$classes["Fisher"] = 0;

$active_classes = array();
$active_classes["Gladiator"] = 0;
$active_classes["Pugilist"] = 0;
$active_classes["Marauder"] = 0;
$active_classes["Lancer"] = 0;
$active_classes["Archer"] = 0;
$active_classes["Rogue"] = 0;
$active_classes["Conjurer"] = 0;
$active_classes["Thaumaturge"] = 0;
$active_classes["Arcanist"] = 0;
$active_classes["Scholar"] = 0;
$active_classes["Dark Knight"] = 0;
$active_classes["Machinist"] = 0;
$active_classes["Astrologian"] = 0;
$active_classes["Samurai"] = 0;
$active_classes["Red Mage"] = 0;
$active_classes["Carpenter"] = 0;
$active_classes["Blacksmith"] = 0;
$active_classes["Armorer"] = 0;
$active_classes["Goldsmith"] = 0;
$active_classes["Leatherworker"] = 0;
$active_classes["Weaver"] = 0;
$active_classes["Alchemist"] = 0;
$active_classes["Culinarian"] = 0;
$active_classes["Miner"] = 0;
$active_classes["Botanist"] = 0;
$active_classes["Fisher"] = 0;

$sub_time = array();
$sub_time["30 Days"] = 0;
$sub_time["60 Days"] = 0;
$sub_time["90 Days"] = 0;
$sub_time["180 Days"] = 0;
$sub_time["270 Days"] = 0;
$sub_time["360 Days"] = 0;
$sub_time["450 Days"] = 0;
$sub_time["630 Days"] = 0;
$sub_time["960 Days"] = 0;

$prearr = 0;
$prehw = 0;
$presb = 0;
$ps4_collectors = 0;
$pc_collectors = 0;
$arrartbook = 0;
$beforemeteor = 0;
$beforethefall = 0;
$soundtrack = 0;
$moogleplush = 0;
$saw_eternal_bond = 0;
$did_eternal_bond = 0;
$comm50 = 0;
$hildibrand = 0;
$sightseeing = 0;

$beast_tribes = array();
$beast_tribes["Kobold"] = 0;
$beast_tribes["Sahagin"] = 0;
$beast_tribes["Amaljaa"] = 0;
$beast_tribes["Sylph"] = 0;

$player_overview_query = $db->query("SELECT * FROM tblplayers;", MYSQLI_USE_RESULT);
while($row = $player_overview_query->fetch_assoc()) {
        $realm = isset($row["realm"]) ? $row["realm"] : 'Unknown';
        $grand_company = isset($row["grand_company"]) ?$row["grand_company"] : 'Unknown';
        $race = isset($row["race"]) ? $row["race"] : 'Unknown';
        $gender = isset($row["gender"]) ? $row["gender"] : 'Unknown';
        
        // Fetch total number of players in database
        $player_count++;
        // Fetch realm player counts
        if(!array_key_exists($realm, $realm_count)) {
                $realm_count[$realm] = 0;
        }
        $realm_count[$realm]++;
        // Fetch grand company player count
        if(!array_key_exists($grand_company, $gc_count)) {
                $gc_count[$grand_company] = 0;
        }
        $gc_count[$grand_company]++;
        // Fetch race and gender player count
        if(!array_key_exists($race, $race_gender_count)) {
                $race_gender_count[$race] = array();
        }
        if(!array_key_exists($gender, $race_gender_count[$race])) {
                $race_gender_count[$race][$gender] = 0;
        }
        $race_gender_count[$race][$gender]++;

        if(isset($row["level_gladiator"]) && $row["level_gladiator"] > 0) $classes["Gladiator"]++;
        if(isset($row["level_pugilist"]) && $row["level_pugilist"] > 0) $classes["Pugilist"]++;
        if(isset($row["level_marauder"]) && $row["level_marauder"] > 0) $classes["Marauder"]++;
        if(isset($row["level_lancer"]) && $row["level_lancer"] > 0) $classes["Lancer"]++;
        if(isset($row["level_archer"]) && $row["level_archer"] > 0) $classes["Archer"]++;
        if(isset($row["level_rogue"]) && $row["level_rogue"] > 0) $classes["Rogue"]++;
        if(isset($row["level_conjurer"]) && $row["level_conjurer"] > 0) $classes["Conjurer"]++;
        if(isset($row["level_thaumaturge"]) && $row["level_thaumaturge"] > 0) $classes["Thaumaturge"]++;
        if(isset($row["level_arcanist"]) && $row["level_arcanist"] > 0) $classes["Arcanist"]++;
        if(isset($row["level_scholar"]) && $row["level_scholar"] > 0) $classes["Scholar"]++;
        if(isset($row["level_darkknight"]) && $row["level_darkknight"] > 0) $classes["Dark Knight"]++;
        if(isset($row["level_machinist"]) && $row["level_machinist"] > 0) $classes["Machinist"]++;
        if(isset($row["level_astrologian"]) && $row["level_astrologian"] > 0) $classes["Astrologian"]++;
        if(isset($row["level_samurai"]) && $row["level_samurai"] > 0) $classes["Samurai"]++;
        if(isset($row["level_redmage"]) && $row["level_redmage"] > 0) $classes["Red Mage"]++;
        if(isset($row["level_carpenter"]) && $row["level_carpenter"] > 0) $classes["Carpenter"]++;
        if(isset($row["level_blacksmith"]) && $row["level_blacksmith"] > 0) $classes["Blacksmith"]++;
        if(isset($row["level_armorer"]) && $row["level_armorer"] > 0) $classes["Armorer"]++;
        if(isset($row["level_goldsmith"]) && $row["level_goldsmith"] > 0) $classes["Goldsmith"]++;
        if(isset($row["level_leatherworker"]) && $row["level_leatherworker"] > 0) $classes["Leatherworker"]++;
        if(isset($row["level_weaver"]) && $row["level_weaver"] > 0) $classes["Weaver"]++;
        if(isset($row["level_alchemist"]) && $row["level_alchemist"] > 0) $classes["Alchemist"]++;
        if(isset($row["level_culinarian"]) && $row["level_culinarian"] > 0) $classes["Culinarian"]++;
        if(isset($row["level_miner"]) && $row["level_miner"] > 0) $classes["Miner"]++;
        if(isset($row["level_botanist"]) && $row["level_botanist"] > 0) $classes["Botanist"]++;
        if(isset($row["level_fisher"]) && $row["level_fisher"] > 0) $classes["Fisher"]++;

        // Subscription figures
        if(isset($row["p30days"]) && $row["p30days"] == 1) $sub_time["30 Days"]++;
        if(isset($row["p60days"]) && $row["p60days"] == 1) $sub_time["60 Days"]++;
        if(isset($row["p90days"]) && $row["p90days"] == 1) $sub_time["90 Days"]++;
        if(isset($row["p180days"]) && $row["p180days"] == 1) $sub_time["180 Days"]++;
        if(isset($row["p270days"]) && $row["p270days"] == 1) $sub_time["270 Days"]++;
        if(isset($row["p360days"]) && $row["p360days"] == 1) $sub_time["360 Days"]++;
        if(isset($row["p450days"]) && $row["p450days"] == 1) $sub_time["450 Days"]++;
        if(isset($row["p630days"]) && $row["p630days"] == 1) $sub_time["630 Days"]++;
        if(isset($row["p960days"]) && $row["p960days"] == 1)  $sub_time["960 Days"]++;

        // Pre-orders
        $prearr += isset($row["prearr"]) && $row["prearr"] == 1 ? 1 : 0;
        $fmt_prearr = number_format($prearr);
        $prehw += isset($row["prehw"]) && $row["prehw"] == 1 ? 1 : 0;
        $fmt_prehw = number_format($prehw);
        $presb += isset($row["presb"]) && $row["presb"] == 1 ? 1 : 0;
        $fmt_presb = number_format($presb);

        // Collectors Edition
        $ps4_collectors += isset($row["ps4collectors"]) && $row["ps4collectors"] == 1 ? 1 : 0;
        $fmt_ps4_collectors = number_format($ps4_collectors);
        $pc_collectors += isset($row["arrcollector"]) && $row["arrcollector"] == 1 ? 1 : 0;
        $fmt_pc_collectors = number_format($pc_collectors);

        // Physical Items
        $arrartbook += isset($row["arrartbook"]) && $row["arrartbook"] == 1 ? 1 : 0;
        $fmt_arrartbook = number_format($arrartbook);
        $beforemeteor += isset($row["beforemeteor"]) && $row["beforemeteor"] == 1 ? 1 : 0;
        $fmt_beforemeteor = number_format($beforemeteor);
        $beforethefall += isset($row["beforethefall"]) && $row["beforethefall"] == 1 ? 1 : 0;
        $fmt_beforethefall = number_format($beforethefall);
        $soundtrack += isset($row["soundtrack"]) && $row["soundtrack"] == 1 ? 1 : 0;
        $fmt_soundtrack = number_format($soundtrack);
        $moogleplush += isset($row["moogleplush"]) && $row["moogleplush"] == 1 ? 1 : 0;
        $fmt_moogleplush = number_format($moogleplush);

        // Eternal Bond
        $saw_eternal_bond += isset($row["saweternalbond"]) && $row["saweternalbond"] == 1 ? 1 : 0;
        $fmt_saw_eternal_bond = number_format($saw_eternal_bond);
        $did_eternal_bond += isset($row["dideternalbond"]) && $row["dideternalbond"] == 1 ? 1 : 0;
        $fmt_did_eternal_bond = number_format($did_eternal_bond);

        // Player Commendations
        $comm50 += isset($row["comm50"]) && $row["comm50"] == 1 ? 1 : 0;
        $fmt_comm50 = number_format($comm50);

        // Hildibrand
        $hildibrand += isset($row["hildibrand"]) && $row["hildibrand"] == 1 ? 1 : 0;
        $fmt_hildibrand = number_format($hildibrand);

        // ARR Sightseeing Log
        $sightseeing += isset($row["sightseeing"]) && $row["sightseeing"] == 1 ? 1 : 0;
        $fmt_sightseeing = number_format($sightseeing);

        // Beast Tribes
        $beast_tribes["Kobold"] += isset($row["kobold"]) && $row["kobold"] == 1 ? 1 : 0;
        $beast_tribes["Sahagin"] += isset($row["sahagin"]) && $row["sahagin"] == 1 ? 1 : 0;
        $beast_tribes["Amaljaa"] += isset($row["amaljaa"]) && $row["amaljaa"] == 1 ? 1 : 0;
        $beast_tribes["Sylph"] += isset($row["sylph"]) && $row["sylph"] == 1 ? 1 : 0;

        // Fetch total number of active players in database
        if(isset($row["hw_33_complete"]) && $row["hw_33_complete"] == 1) {
            $active_player_count++;
            // Fetch realm active player count
            if(!array_key_exists($realm, $active_realm_count)) {
                    $active_realm_count[$realm] = 0;
            }
            $active_realm_count[$realm]++;
            // Fetch granc company active player count
            if(!array_key_exists($grand_company, $active_gc_count)) {
                    $active_gc_count[$grand_company] = 0;
            }
            $active_gc_count[$grand_company]++;
        // Fetch race and gender active player count
            if(!array_key_exists($race, $active_race_gender_count)) {
                    $active_race_gender_count[$race] = array();
            }
            if(!array_key_exists($gender, $active_race_gender_count[$race])) {
                    $active_race_gender_count[$race][$gender] = 0;
            }
            $active_race_gender_count[$race][$gender]++;

            if(isset($row["level_gladiator"]) && $row["level_gladiator"] > 0) $active_classes["Gladiator"]++;
            if(isset($row["level_pugilist"]) && $row["level_pugilist"] > 0) $active_classes["Pugilist"]++;
            if(isset($row["level_marauder"]) && $row["level_marauder"] > 0) $active_classes["Marauder"]++;
            if(isset($row["level_lancer"]) && $row["level_lancer"] > 0) $active_classes["Lancer"]++;
            if(isset($row["level_archer"]) && $row["level_archer"] > 0) $active_classes["Archer"]++;
            if(isset($row["level_rogue"]) && $row["level_rogue"] > 0) $active_classes["Rogue"]++;
            if(isset($row["level_conjurer"]) && $row["level_conjurer"] > 0) $active_classes["Conjurer"]++;
            if(isset($row["level_thaumaturge"]) && $row["level_thaumaturge"] > 0) $active_classes["Thaumaturge"]++;
            if(isset($row["level_arcanist"]) && $row["level_arcanist"] > 0) $active_classes["Arcanist"]++;
            if(isset($row["level_scholar"]) && $row["level_scholar"] > 0) $active_classes["Scholar"]++;
            if(isset($row["level_darkknight"]) && $row["level_darkknight"] > 0) $active_classes["Dark Knight"]++;
            if(isset($row["level_machinist"]) && $row["level_machinist"] > 0) $active_classes["Machinist"]++;
            if(isset($row["level_astrologian"]) && $row["level_astrologian"] > 0) $active_classes["Astrologian"]++;
            if(isset($row["level_samurai"]) && $row["level_samurai"] > 0) $active_classes["Samurai"]++;
            if(isset($row["level_redmage"]) && $row["level_redmage"] > 0) $active_classes["Red Mage"]++;
            if(isset($row["level_carpenter"]) && $row["level_carpenter"] > 0) $active_classes["Carpenter"]++;
            if(isset($row["level_blacksmith"]) && $row["level_blacksmith"] > 0) $active_classes["Blacksmith"]++;
            if(isset($row["level_armorer"]) && $row["level_armorer"] > 0) $active_classes["Armorer"]++;
            if(isset($row["level_goldsmith"]) && $row["level_goldsmith"] > 0) $active_classes["Goldsmith"]++;
            if(isset($row["level_leatherworker"]) && $row["level_leatherworker"] > 0) $active_classes["Leatherworker"]++;
            if(isset($row["level_weaver"]) && $row["level_weaver"] > 0) $active_classes["Weaver"]++;
            if(isset($row["level_alchemist"]) && $row["level_alchemist"] > 0) $active_classes["Alchemist"]++;
            if(isset($row["level_culinarian"]) && $row["level_culinarian"] > 0) $active_classes["Culinarian"]++;
            if(isset($row["level_miner"]) && $row["level_miner"] > 0) $active_classes["Miner"]++;
            if(isset($row["level_botanist"]) && $row["level_botanist"] > 0) $active_classes["Botanist"]++;
            if(isset($row["level_fisher"]) && $row["level_fisher"] > 0) $active_classes["Fisher"]++;
        }
}

ksort($gc_count);
ksort($active_gc_count);
ksort($race_gender_count);
ksort($active_race_gender_count);

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
                              <div><?php echo number_format(sumInRegion($realm_count, $american_realm_array)) ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($active_realm_count, $american_realm_array)) ?></div>
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
                              <div><?php echo number_format(sumInRegion($realm_count, $japanese_realm_array)) ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($active_realm_count, $japanese_realm_array)) ?></div>
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
                              <div><?php echo number_format(sumInRegion($realm_count, $european_realm_array)) ?></div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">ACTIVE PLAYERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($active_realm_count, $european_realm_array)) ?></div>
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
                      colors: ['#212121', '#b71c1c', '#ffc107', '#9e9e9e']
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
                                echo getValueOrZero($realm_count, $value) . ",";
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
                                echo getValueOrZero($active_realm_count, $value) . ",";
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
                                echo getValueOrZero($realm_count, $value) . ",";
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
                                echo getValueOrZero($active_realm_count, $value) . ",";
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
                                echo getValueOrZero($realm_count, $value) . ",";
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
                                echo getValueOrZero($active_realm_count, $value) . ",";
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
