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
$active_classes = array();
$sub_time = array();
$prearr = 0;
$prehw = 0;
$presb = 0;
$ps4collectors = 0;
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

$player_overview_query = $db->query("SELECT realm, grand_company, race, gender, COUNT(*), SUM(CASE WHEN hw_33_complete = (1) THEN 1 ELSE 0 END) FROM tblplayers GROUP BY realm, grand_company, race, gender;");
while($row = $player_overview_query->fetch_array()) {
        $realm = isset($row[0]) ? $row[0] : 'Unknown';
        $grand_company = isset($row[1]) ?$row[1] : 'Unknown';
        $race = isset($row[2]) ? $row[2] : 'Unknown';
        $gender = isset($row[3]) ? $row[3] : 'Unknown';
        // Fetch total number of players in database
        $player_count += isset($row[4]) ? $row[4] : 0;
        // Fetch total number of active players in database
        $active_player_count += isset($row[5]) ? $row[5] : 0;
        // Fetch realm player counts
        if(!array_key_exists($realm, $realm_count)) {
                $realm_count[$realm] = 0;
        }
        $realm_count[$realm] += isset($row[4]) ? $row[4] : 0;
        // Fetch realm active player count
        if(!array_key_exists($realm, $active_realm_count)) {
                $active_realm_count[$realm] = 0;
        }
        $active_realm_count[$realm] += isset($row[5]) ? $row[5] : 0;
        // Fetch grand company player count
        if(!array_key_exists($grand_company, $gc_count)) {
                $gc_count[$grand_company] = 0;
        }
        $gc_count[$grand_company] += isset($row[4]) ? $row[4] : 0;
        // Fetch granc company active player count
        if(!array_key_exists($grand_company, $active_gc_count)) {
                $active_gc_count[$grand_company] = 0;
        }
        $active_gc_count[$grand_company] += isset($row[5]) ? $row[5] : 0;
        // Fetch race and gender player count
        if(!array_key_exists($race, $race_gender_count)) {
                $race_gender_count[$race] = array();
        }
        if(!array_key_exists($gender, $race_gender_count[$race])) {
                $race_gender_count[$race][$gender] = 0;
        }
        $race_gender_count[$race][$gender] += isset($row[4]) ? $row[4] : 0;
        // Fetch race and gender active player count
        if(!array_key_exists($race, $active_race_gender_count)) {
                $active_race_gender_count[$race] = array();
        }
        if(!array_key_exists($gender, $active_race_gender_count[$race])) {
                $active_race_gender_count[$race][$gender] = 0;
        }
        $active_race_gender_count[$race][$gender] += isset($row[5]) ? $row[5] : 0;
}

// Get statistics on class adoption
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

$classes["Gladiator"] = isset($results[0]) ? $results[0] : 0;
$classes["Pugilist"] = isset($results[2]) ? $results[2] : 0;
$classes["Marauder"] = isset($results[4]) ? $results[4] : 0;
$classes["Lancer"] = isset($results[6]) ? $results[6] : 0;
$classes["Archer"] = isset($results[8]) ? $results[8] : 0;
$classes["Rogue"] = isset($results[10]) ? $results[10] : 0;
$classes["Conjurer"] = isset($results[12]) ? $results[12] : 0;
$classes["Thaumaturge"] = isset($results[14]) ? $results[14] : 0;
$classes["Arcanist"] = isset($results[16]) ? $results[16] : 0;
$classes["Scholar"] = isset($results[18]) ? $results[18] : 0;
$classes["Dark Knight"] = isset($results[20]) ? $results[20] : 0;
$classes["Machinist"] = isset($results[22]) ? $results[22] : 0;
$classes["Astrologian"] = isset($results[24]) ? $results[24] : 0;
$classes["Samurai"] = isset($results[26]) ? $results[26] : 0;
$classes["Red Mage"] = isset($results[28]) ? $results[28] : 0;
$classes["Carpenter"] = isset($results[30]) ? $results[30] : 0;
$classes["Blacksmith"] = isset($results[32]) ? $results[32] : 0;
$classes["Armorer"] = isset($results[34]) ? $results[34] : 0;
$classes["Goldsmith"] = isset($results[36]) ? $results[36] : 0;
$classes["Leatherworker"] = isset($results[38]) ? $results[38] : 0;
$classes["Weaver"] = isset($results[40]) ? $results[40] : 0;
$classes["Alchemist"] = isset($results[42]) ? $results[42] : 0;
$classes["Culinarian"] = isset($results[44]) ? $results[44] : 0;
$classes["Miner"] = isset($results[46]) ? $results[46] : 0;
$classes["Botanist"] = isset($results[48]) ? $results[48] : 0;
$classes["Fisher"] = isset($results[50]) ? $results[50] : 0;

$active_classes["Gladiator"] = isset($results[1]) ? $results[1] : 0;
$active_classes["Pugilist"] = isset($results[3]) ? $results[3] : 0;
$active_classes["Marauder"] = isset($results[5]) ? $results[5] : 0;
$active_classes["Lancer"] = isset($results[7]) ? $results[7] : 0;
$active_classes["Archer"] = isset($results[9]) ? $results[9] : 0;
$active_classes["Rogue"] = isset($results[11]) ? $results[11] : 0;
$active_classes["Conjurer"] = isset($results[13]) ? $results[13] : 0;
$active_classes["Thaumaturge"] = isset($results[15]) ? $results[15] : 0;
$active_classes["Arcanist"] = isset($results[17]) ? $results[17] : 0;
$active_classes["Scholar"] = isset($results[19]) ? $results[19] : 0;
$active_classes["Dark Knight"] = isset($results[21]) ? $results[21] : 0;
$active_classes["Machinist"] = isset($results[23]) ? $results[23] : 0;
$active_classes["Astrologian"] = isset($results[25]) ? $results[25] : 0;
$active_classes["Samurai"] = isset($results[27]) ? $results[27] : 0;
$active_classes["Red Mage"] = isset($results[29]) ? $results[29] : 0;
$active_classes["Carpenter"] = isset($results[31]) ? $results[31] : 0;
$active_classes["Blacksmith"] = isset($results[33]) ? $results[33] : 0;
$active_classes["Armorer"] = isset($results[35]) ? $results[35] : 0;
$active_classes["Goldsmith"] = isset($results[37]) ? $results[37] : 0;
$active_classes["Leatherworker"] = isset($results[39]) ? $results[39] : 0;
$active_classes["Weaver"] = isset($results[41]) ? $results[41] : 0;
$active_classes["Alchemist"] = isset($results[43]) ? $results[43] : 0;
$active_classes["Culinarian"] = isset($results[45]) ? $results[45] : 0;
$active_classes["Miner"] = isset($results[47]) ? $results[47] : 0;
$active_classes["Botanist"] = isset($results[49]) ? $results[49] : 0;
$active_classes["Fisher"] = isset($results[51]) ? $results[51] : 0;

// Subscription figures
$sub_time["30 Days"] = isset($results[52]) ? $results[52] : 0;
$sub_time["60 Days"] = isset($results[53]) ? $results[53] : 0;
$sub_time["90 Days"] = isset($results[54]) ? $results[54] : 0;
$sub_time["180 Days"] = isset($results[55]) ? $results[55] : 0;
$sub_time["270 Days"] = isset($results[56]) ? $results[56] : 0;
$sub_time["360 Days"] = isset($results[57]) ? $results[57] : 0;
$sub_time["450 Days"] = isset($results[58]) ? $results[58] : 0;
$sub_time["630 Days"] = isset($results[59]) ? $results[59] : 0;
$sub_time["960 Days"] = isset($results[60]) ? $results[60] : 0;

// Pre-orders
$prearr = isset($results[61]) ? $results[61] : 0;
$fmt_prearr = number_format($prearr);
$prehw = isset($results[62]) ? $results[62] : 0;
$fmt_prehw = number_format($prehw);
$presb = isset($results[63]) ? $results[63] : 0;
$fmt_presb = number_format($presb);

// Collectors Edition
$ps4_collectors = isset($results[64]) ? $results[64] : 0;
$fmt_ps4_collectors = number_format($ps4_collectors);
$pc_collectors = isset($results[65]) ? $results[65] : 0;
$fmt_pc_collectors = number_format($pc_collectors);

// Physical Items
$arrartbook = isset($results[66]) ? $results[66] : 0;
$fmt_arrartbook = number_format($arrartbook);
$beforemeteor = isset($results[67]) ? $results[67] : 0;
$fmt_beforemeteor = number_format($beforemeteor);
$beforethefall = isset($results[68]) ? $results[68] : 0;
$fmt_beforethefall = number_format($beforethefall);
$soundtrack = isset($results[69]) ? $results[69] : 0;
$fmt_soundtrack = number_format($soundtrack);
$moogleplush = isset($results[70]) ? $results[70] : 0;
$fmt_moogleplush = number_format($moogleplush);

// Eternal Bond
$saw_eternal_bond = isset($results[71]) ? $results[71] : 0;
$fmt_saw_eternal_bond = number_format($saw_eternal_bond);
$did_eternal_bond = isset($results[72]) ? $results[72] : 0;
$fmt_did_eternal_bond = number_format($did_eternal_bond);

// Player Commendations
$comm50 = isset($results[73]) ? $results[73] : 0;
$fmt_comm50 = number_format($comm50);

// Hildibrand
$hildibrand = isset($results[74]) ? $results[74] : 0;
$fmt_hildibrand = number_format($hildibrand);

// ARR Sightseeing Log
$sightseeing = isset($results[75]) ? $results[75] : 0;
$fmt_sightseeing = number_format($sightseeing);

// Beast Tribes

$beast_tribes["Kobold"] = isset($results[76]) ? $results[76] : 0;
$beast_tribes["Sahagin"] = isset($results[77]) ? $results[77] : 0;
$beast_tribes["Amaljaa"] = isset($results[78]) ? $results[78] : 0;
$beast_tribes["Sylph"] = isset($results[79]) ? $results[79] : 0;

// Close DB Connection
$db->close();

?>
<html>

  <head>
    <title>XIVCensus - Player statistics for FFXIV</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
      <!-- Font Awesome-->
    <script src="https://use.fontawesome.com/42d19261ec.js"></script>
      <!-- Compiled and minified CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
      <!-- Compiled and minified JavaScript -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

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

          .dropdown-button, .waves-light, .btn, .btn:visited{
            background-color: #7CB5EC;
          }

          .dropdown-button:hover, .waves-light:hover{
            background-color: #8fc0ef;
          }

          #realm-dropdown a, #pop-dropdown a, #misc-stats a{
              color: black;
          }
          
          .navbar {
              left: 0;
              right: 0;
              margin-left: auto;
              margin-right: auto;
          }
          
          .main-nav-scrolled {
              z-index: 1;
              position: fixed;
              width: 100%;
              top: 0;
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
                      <a id="population"><span class="card-title black-text" style="font-size:28pt;">XIVCensus - Player statistics for FFXIV</span></a>
                      <p>Statistics for <?php echo $date; ?></p>
                      <p><b>* (Any reference to "Active" players, refers to players that have claimed the 3.3 story minion)</b></p>
                    </div>
              </div>
          </div>
      </div>
            <div class="col s12 m6 navbar center">
                      <!-- Navbar - 'Population', 'Realm Stats' & 'Other Stats' are dropdowns-->
                      <a class='dropdown-button btn' href='#' data-activates='pop-dropdown'>Population</a>
                      <a class="waves-effect waves-light btn" href='#racegender'>Race &amp; Gender Stats</a>
                      <a class="waves-effect waves-light btn" href='#class'>Class Stats</a>
                      <a class='dropdown-button btn' href='#' data-activates='realm-dropdown'>Realm Stats</a>
                      <a class="waves-effect waves-light btn" href='#grandcompany'>Grand Company Stats</a>
                      <a class='dropdown-button btn' href='#' data-activates='misc-stats'>Other Stats</a>
                      <a class="waves-effect waves-light btn" href='#top'><i class="fa fa-arrow-up" aria-hidden="true"></i></a>

                      <!-- Population Stats Dropdown -->
                      <ul id='pop-dropdown' class='dropdown-content'>
                          <li><a href="#population">World</a></li>
                          <li class="divider"></li>
                          <li><a href="#popna">North America</a></li>
                          <li><a href="#popjp">Japan</a></li>
                          <li><a href="#popeu">Europe</a></li>
                      </ul>
                      
                      <!-- Realm Stats Dropdown -->
                      <ul id='realm-dropdown' class='dropdown-content'>
                          <li><a href="#realmall">Realm Stats (All-Time)</a></li>
                          <li class="divider"></li>
                          <li><a href="#rat-na">North America</a></li>
                          <li><a href="#rat-jp">Japan</a></li>
                          <li><a href="#rat-eu">Europe</a></li>
                          <li class="divider"></li>
                          <li class="divider"></li>
                          <li><a href="#realmactive">Realm Stats (Active)</a></li>
                          <li class="divider"></li>
                          <li><a href="#ra-na">North America</a></li>
                          <li><a href="#ra-jp">Japan</a></li>
                          <li><a href="#ra-eu">Europe</a></li>
                      </ul>
                      
                      <!-- Other Stats Dropdown -->
                      <ul id='misc-stats' class='dropdown-content'>
                          <li><a href="#subscribed">Subscribed Time</a></li>
                          <li><a href="#beast">Beast Tribes</a></li>
                          <li><a href="#preorders">Pre-Orders</a></li>
                          <li><a href="#collectors">Collectors Edition</a></li>
                          <li><a href="#physical">Physical Items</a></li>
                          <li><a href="#misc-stats">Misc Stats</a></li>
                        </ul>            
      </div>
      <div class="row">
          <div class="col s12 m6" style="width:100%;">
              <div class="card white">
                  <div class="card-content black-text">
                      <a id="population"><span class="card-title black-text light">HOW MANY PLAYERS ARE THERE?</span></a>
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
                      <a id="popna"><div class="black-text light region-title">AMERICA</div></a>
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
                      <a id="popjp"><div class="black-text light region-title">JAPAN</div></a>
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
                      <a id="popeu"><div class="black-text light region-title">EUROPE</div></a>
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
                      <a id="racegender"><span class="card-title black-text light">RACE AND GENDER DISTRIBUTION</span></a>
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
                      <a id="class"><span class="card-title black-text light">CLASS DISTRIBUTION</span></a>
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
                      <a id="realmall"><span class="card-title black-text light">REALM DISTRIBUTION (ALL)</span></a>
                      <br/>
                      <hr/>
                      <br/>
                      <a id="rat-na"><div class="black-text light region-subtitle">AMERICAN REALMS</div></a>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="america_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <a id="rat-jp"><div class="black-text light region-subtitle">JAPANESE REALMS</div></a>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="japan_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <a id="rat-eu"><div class="black-text light region-subtitle">EUROPEAN REALMS</div></a>
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
                      <a id="realmactive"><span class="card-title black-text light">REALM DISTRIBUTION (ACTIVE)</span></a>
                      <br/>
                      <hr/>
                      <br/>
                          <a id="ra-na"><div class="black-text light region-subtitle">AMERICAN REALMS</div></a>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="america_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                          <a id="ra-jp"><div class="black-text light region-subtitle">JAPANESE REALMS</div></a>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="japan_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                          <a id="ra-eu"><div class="black-text light region-subtitle">EUROPEAN REALMS</div></a>
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
                      <a id="grandcompany"><span class="card-title black-text light">GRAND COMPANY DISTRIBUTION</span></a>
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
                      <a id="subscribed"><span class="card-title black-text light">SUBSCRIBED TIME</span></a>
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
                      <a id="beast"><span class="card-title black-text light">BEAST TRIBES (RANK 4 OR HIGHER)</span></a>

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
                      <a id="preorders"><span class="card-title black-text light">PRE-ORDERS</span></a>

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
                      <a id="collectors"><span class="card-title black-text light">COLLECTORS EDITION</span></a>

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
                      <a id="physical"><span class="card-title black-text light">PHYSICAL ITEMS</span></a>

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
                      <a id="misc-stats"><span class="card-title black-text light">OTHER</span></a>

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
          var mn = $(".navbar");
          mns = "main-nav-scrolled";
          hdr = $('header').height();
          
          $(window).scroll(function() {
              if( $(this).scrollTop() > 105 ) {
                  mn.addClass(mns);
              } else {
                  mn.removeClass(mns);
              }
          });
  </script>

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