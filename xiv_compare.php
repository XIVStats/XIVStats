<?php

$olddb = new SQLite3('players.db');
$newdb = new SQLite3('players-20150801.db');

$old_experienced_check = "((level_gladiator >= '50' AND level_gladiator != '') OR (level_pugilist >= '50' AND level_pugilist != '') OR
        (level_marauder >= '50' AND level_marauder != '') OR (level_lancer >= '50' AND level_lancer != '') OR
        (level_archer >= '50' AND level_archer != '') OR (level_rogue >= '50' AND level_rogue != '') OR
        (level_conjurer >= '50' AND level_conjurer != '') OR (level_thaumaturge >= '50' AND level_thaumaturge != '') OR
        (level_arcanist >= '50' AND level_arcanist != '') OR (level_carpenter >= '50' AND level_carpenter != '') OR
        (level_blacksmith >= '50' AND level_blacksmith != '') OR (level_armorer >= '50' AND level_armorer != '')  OR
        (level_goldsmith >= '50' AND level_goldsmith != '') OR (level_leatherworker >= '50' AND level_leatherworker != '') OR
        (level_weaver >= '50' AND level_weaver != '') OR (level_alchemist >= '50' AND level_alchemist != '') OR
        (level_culinarian >= '50' AND level_culinarian != '') OR (level_miner >= '50' AND level_miner != '') OR
        (level_botanist >= '50' AND level_botanist != '') OR (level_fisher >= '50' AND level_fisher != ''))";

$new_experienced_check = "((level_gladiator >= '50' AND level_gladiator != '') OR (level_pugilist >= '50' AND level_pugilist != '') OR
        (level_marauder >= '50' AND level_marauder != '') OR (level_lancer >= '50' AND level_lancer != '') OR
        (level_archer >= '50' AND level_archer != '') OR (level_rogue >= '50' AND level_rogue != '') OR
        (level_conjurer >= '50' AND level_conjurer != '') OR (level_thaumaturge >= '50' AND level_thaumaturge != '') OR
        (level_arcanist >= '50' AND level_arcanist != '') OR (level_darkknight >= '50' AND level_darkknight != '') OR
        (level_machinist >= '50' AND level_machinist != '') OR (level_astrologian >= '50' AND level_astrologian != '') OR
        (level_carpenter >= '50' AND level_carpenter != '') OR
        (level_blacksmith >= '50' AND level_blacksmith != '') OR (level_armorer >= '50' AND level_armorer != '')  OR
        (level_goldsmith >= '50' AND level_goldsmith != '') OR (level_leatherworker >= '50' AND level_leatherworker != '') OR
        (level_weaver >= '50' AND level_weaver != '') OR (level_alchemist >= '50' AND level_alchemist != '') OR
        (level_culinarian >= '50' AND level_culinarian != '') OR (level_miner >= '50' AND level_miner != '') OR
        (level_botanist >= '50' AND level_botanist != '') OR (level_fisher >= '50' AND level_fisher != ''))";

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
$old_player_count_query = $olddb->query("SELECT count() FROM players");
$old_player_count = $old_player_count_query->fetchArray()[0];

$new_player_count_query = $newdb->query("SELECT count() FROM players");
$new_player_count = $new_player_count_query->fetchArray()[0];

$old_exp_player_count_query = $olddb->query("SELECT count() FROM players WHERE " . $old_experienced_check . ";");
$old_exp_player_count = $old_exp_player_count_query->fetchArray()[0];

$new_exp_player_count_query = $newdb->query("SELECT count() FROM players WHERE " . $new_experienced_check . ";");
$new_exp_player_count = $new_exp_player_count_query->fetchArray()[0];

// Fetch total number of players in each region
// America
$old_america_player_count_query = $olddb->query("SELECT count() FROM players WHERE " . $american_realms);
$old_america_player_count = $old_america_player_count_query->fetchArray()[0];

$new_america_player_count_query = $newdb->query("SELECT count() FROM players WHERE " . $american_realms);
$new_america_player_count = $new_america_player_count_query->fetchArray()[0];

$old_america_exp_player_count_query = $olddb->query("SELECT count() FROM players WHERE " . $american_realms . " AND " . $old_experienced_check);
$old_america_exp_player_count = $old_america_exp_player_count_query->fetchArray()[0];

$new_america_exp_player_count_query = $newdb->query("SELECT count() FROM players WHERE " . $american_realms . " AND " . $new_experienced_check);
$new_america_exp_player_count = $new_america_exp_player_count_query->fetchArray()[0];

//Japan
$old_japan_player_count_query = $olddb->query("SELECT count() FROM players WHERE " . $japanese_realms);
$old_japan_player_count = $old_japan_player_count_query->fetchArray()[0];

$new_japan_player_count_query = $newdb->query("SELECT count() FROM players WHERE " . $japanese_realms);
$new_japan_player_count = $new_japan_player_count_query->fetchArray()[0];

$old_japan_exp_player_count_query = $olddb->query("SELECT count() FROM players WHERE " . $japanese_realms . " AND " . $old_experienced_check);
$old_japan_exp_player_count = $old_japan_exp_player_count_query->fetchArray()[0];

$new_japan_exp_player_count_query = $newdb->query("SELECT count() FROM players WHERE " . $japanese_realms . " AND " . $new_experienced_check);
$new_japan_exp_player_count = $new_japan_exp_player_count_query->fetchArray()[0];

//Europe
$old_europe_player_count_query = $olddb->query("SELECT count() FROM players WHERE " . $european_realms);
$old_europe_player_count = $old_europe_player_count_query->fetchArray()[0];

$new_europe_player_count_query = $newdb->query("SELECT count() FROM players WHERE " . $european_realms);
$new_europe_player_count = $new_europe_player_count_query->fetchArray()[0];

$old_europe_exp_player_count_query = $olddb->query("SELECT count() FROM players WHERE " . $european_realms . " AND " . $old_experienced_check);
$old_europe_exp_player_count = $old_europe_exp_player_count_query->fetchArray()[0];

$new_europe_exp_player_count_query = $newdb->query("SELECT count() FROM players WHERE " . $european_realms . " AND " . $new_experienced_check);
$new_europe_exp_player_count = $new_europe_exp_player_count_query->fetchArray()[0];

// Grand Company Distribution
$gc_new_distribution = array();
$gc_new_distribution_query = $newdb->query("SELECT grand_company,count() FROM players GROUP BY grand_company");

while ($row = $gc_new_distribution_query->fetchArray()) {
        //$row[0] = grand company name
        //$row[1] = population

        //If user is not in gc, mark gc as none rather than blank
        if ($row[0] === '') { $row[0] = "None"; }
        $gc_new_distribution[$row[0]] = $row[1];
}

$gc_new_exp_distribution = array();
$gc_new_exp_distribution_query = $newdb->query("select grand_company,count() from players WHERE " . $new_experienced_check . " group by grand_company");

while ($row = $gc_new_exp_distribution_query->fetchArray()) {
        //$row[0] = grand company name
        //$row[1] = population

        //If user is not in gc, mark gc as none rather than blank
        if ($row[0] === '') { $row[0] = "None"; }
        $gc_new_exp_distribution[$row[0]] = $row[1];
}


// Race / Gender Distribution

$old_race_gender_male = array();
$old_race_gender_female = array();
$old_race_gender_query = $olddb->query("SELECT race,gender,count() FROM players GROUP BY race,gender");
$new_race_gender_male = array();
$new_race_gender_female = array();
$new_race_gender_query = $newdb->query("SELECT race,gender,count() FROM players GROUP BY race,gender");

while ($row = $old_race_gender_query->fetchArray()) {
        //$row[0] = race
        //$row[1] = gender
        //$row[2] = population

        //Split males and females into seperate arrays
        if ($row[1] === "male") {
                $old_race_gender_male[$row[0]] = $row[2];
        } else if ($row[1] === "female") {
                $old_race_gender_female[$row[0]] = $row[2];
        }
}

while ($row = $new_race_gender_query->fetchArray()) {
        //$row[0] = race
        //$row[1] = gender
        //$row[2] = population

        //Split males and females into seperate arrays
        if ($row[1] === "male") {
                $new_race_gender_male[$row[0]] = $row[2];
        } else if ($row[1] === "female") {
                $new_race_gender_female[$row[0]] = $row[2];
        }
}

$old_exp_race_gender_male = array();
$old_exp_race_gender_female = array();
$old_exp_race_gender_query = $olddb->query("SELECT race,gender,count() FROM players WHERE " . $old_experienced_check . " GROUP BY race,gender");
$new_exp_race_gender_male = array();
$new_exp_race_gender_female = array();
$new_exp_race_gender_query = $newdb->query("SELECT race,gender,count() FROM players WHERE " . $new_experienced_check . " GROUP BY race,gender");

while ($row = $old_exp_race_gender_query->fetchArray()) {
        //$row[0] = race
        //$row[1] = gender
        //$row[2] = population

        //Split males and females into seperate arrays
        if ($row[1] === "male") {
                $old_exp_race_gender_male[$row[0]] = $row[2];
        } else if ($row[1] === "female") {
                $old_exp_race_gender_female[$row[0]] = $row[2];
        }
}

while ($row = $new_exp_race_gender_query->fetchArray()) {
        //$row[0] = race
        //$row[1] = gender
        //$row[2] = population

        //Split males and females into seperate arrays
        if ($row[1] === "male") {
                $new_exp_race_gender_male[$row[0]] = $row[2];
        } else if ($row[1] === "female") {
                $new_exp_race_gender_female[$row[0]] = $row[2];
        }
}

// Get statistics on class adoption
$old_classes = array();

$class_results = $olddb->query("SELECT count() FROM players WHERE level_gladiator != ''");
$old_classes["Gladiator"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_pugilist != ''");
$old_classes["Pugilist"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_marauder != ''");
$old_classes["Marauder"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_lancer != ''");
$old_classes["Lancer"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_archer != ''");
$old_classes["Archer"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_rogue != ''");
$old_classes["Rogue"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_conjurer != ''");
$old_classes["Conjurer"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_thaumaturge != ''");
$old_classes["Thaumaturge"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_arcanist != ''");
$old_classes["Arcanist"] = $class_results->fetchArray()[0];

// Set the new classes to 0 for the old stats, just so the columns line up correctly
$old_classes["Dark Knight"] = "0";
$old_classes["Machinist"] = "0";
$old_classes["Astrologian"] = "0";

$class_results = $olddb->query("SELECT count() FROM players WHERE level_carpenter != ''");
$old_classes["Carpenter"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_blacksmith != ''");
$old_classes["Blacksmith"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_armorer != ''");
$old_classes["Armorer"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_goldsmith != ''");
$old_classes["Goldsmith"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_leatherworker != ''");
$old_classes["Leatherworker"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_weaver != ''");
$old_classes["Weaver"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_alchemist != ''");
$old_classes["Alchemist"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_culinarian != ''");
$old_classes["Culinarian"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_miner != ''");
$old_classes["Miner"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_botanist != ''");
$old_classes["Botanist"] = $class_results->fetchArray()[0];

$class_results = $olddb->query("SELECT count() FROM players WHERE level_fisher != ''");
$old_classes["Fisher"] = $class_results->fetchArray()[0];


// Get statistics on class adoption (HW)
$new_classes = array();

$class_results = $newdb->query("SELECT count() FROM players WHERE level_gladiator != ''");
$new_classes["Gladiator"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_pugilist != ''");
$new_classes["Pugilist"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_marauder != ''");
$new_classes["Marauder"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_lancer != ''");
$new_classes["Lancer"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_archer != ''");
$new_classes["Archer"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_rogue != ''");
$new_classes["Rogue"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_conjurer != ''");
$new_classes["Conjurer"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_thaumaturge != ''");
$new_classes["Thaumaturge"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_arcanist != ''");
$new_classes["Arcanist"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_darkknight != ''");
$new_classes["Dark Knight"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_machinist != ''");
$new_classes["Machinist"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_astrologian != ''");
$new_classes["Astrologian"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_carpenter != ''");
$new_classes["Carpenter"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_blacksmith != ''");
$new_classes["Blacksmith"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_armorer != ''");
$new_classes["Armorer"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_goldsmith != ''");
$new_classes["Goldsmith"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_leatherworker != ''");
$new_classes["Leatherworker"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_weaver != ''");
$new_classes["Weaver"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_alchemist != ''");
$new_classes["Alchemist"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_culinarian != ''");
$new_classes["Culinarian"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_miner != ''");
$new_classes["Miner"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_botanist != ''");
$new_classes["Botanist"] = $class_results->fetchArray()[0];

$class_results = $newdb->query("SELECT count() FROM players WHERE level_fisher != ''");
$new_classes["Fisher"] = $class_results->fetchArray()[0];

?>

<html>

  <head>
    <title>XIVStats - Heavensward Comparison</title>
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

          .arr-color {
              color: #673ab7;
          }

          .heavensward-color {
              color: #03a9f4;
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
                      <span class="card-title black-text" style="font-size:28pt;">XIVStats - Comparison of A Realm Reborn to Heavensward</span>
                      <p><b>* (Any reference to "Experienced" players, refers to players with at least one skill at level 50)</b></p>
                      <p>The left side shows statistics from April 2015, the right side shows statistics from the end of July 2015</p>
                      <p>NOTE: Unfortunately, due to a bug in the old statistics, the Grand Company population figures were incorrect, so have not been included</p>
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
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>6,468,099</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>7,185,438 </div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+717,339)</div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">EXPERIENCED PLAYERS*</div>
                      <div class="row">
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>1,055,157</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>1,169,673 </div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+114,516)</div>
                          </div>
                      </div>
                      <!-- America -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-title">AMERICA</div>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <div class="row">
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>3,211,604</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>3,595,831</div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+384,227)</div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">EXPERIENCED PLAYERS*</div>
                      <div class="row">
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>438,778</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>493,263</div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+54,485)</div>
                          </div>
                      </div>
                      <!--Japan-->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-title">JAPAN</div>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <div class="row">
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>1,761,520</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>1,900,826</div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+139,306)</div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">EXPERIENCED PLAYERS*</div>
                      <div class="row">
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>430,638</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>465,648</div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+35,010)</div>
                          </div>
                      </div>
                      <!--Europe-->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-title">EUROPE</div>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <div class="row">
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>1,444,665</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>1,632,419</div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+187,754)</div>
                          </div>
                      </div>
                      <div class="black-text light region-subtitle">EXPERIENCED PLAYERS*</div>
                      <div class="row">
                          <div class="col s12 m6 l6 arr-color region-stat">
                              <div>173,390</div>
                              <div class="region-stat-label light">A REALM REBORN</div>
                          </div>
                          <div class="col s12 m6 l6 heavensward-color region-stat">
                              <div>197,506</div>
                              <div class="region-stat-label light">HEAVENSWARD</div>
                              <div class="region-stat-diff">(+24,116)</div>
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
                      <span class="card-title black-text light">GRAND COMPANY DISTRIBUTION</span>
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">ALL PLAYERS</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="gc_new_distribution" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                      <!-- End Chart -->
                      <br/>
                      <hr/>
                      <br/>
                      <div class="black-text light region-subtitle">EXPERIENCED PLAYERS*</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="gc_new_exp_distribution" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                      <!-- End Chart -->
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
                      <div class="black-text light region-subtitle">EXPERIENCED PLAYERS*</div>
                      <br/>
                      <!-- Begin Chart -->
                      <div id="exp_race_gender_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
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
                      <div class="black-text light region-subtitle">EXPERIENCED PLAYERS*</div>
                      <br/>
                      <!-- Begin Chart -->

                      <!-- End Chart -->
                  </div>
              </div>
          </div>
      </div>


      <!-- End Container -->
  </div>
  <footer class="page-footer light-blue lighten-2">
      <div class="footer-copyright">
          <div class="container">
              Latest statistics on generated 2015-07-31
              <div class="right"><a class="grey-text text-lighten-4" href="https://github.com/Pricetx/XIVStats">Source Code avaiailable on GitHub</a> - Feel free to submit any ideas you may have!</div>
          </div>
      </div>
  </footer>

    <script>
        $(function() {
            $('#gc_new_distribution').highcharts({
                chart: {},
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
                        ['None', 5238186, ],
                        ['Immortal Flames', 682866, ],
                        ['Maelstrom', 558835, ],
                        ['Order of the Twin Adder', 705551, ],
                    ]
                }]
            });
        });
    </script>

    <script>
        $(function() {
            $('#gc_new_exp_distribution').highcharts({
                chart: {},
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
                        ['None', 57116, ],
                        ['Immortal Flames', 378824, ],
                        ['Maelstrom', 333818, ],
                        ['Order of the Twin Adder', 399915, ],
                    ]
                }]
            });
        });
    </script>

    <script>
        $(function() {
            $('#race_gender_distribution').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: [
                        "Au",
                        "Elezen",
                        "Hyur",
                        "Lalafell",
                        "Miqo'te",
                        "Roegadyn",
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
                    name: 'Female (ARR)',
                    data: [
                        0, 279385, 756619, 396208, 1233076, 84304,
                    ],
                    color: '#ce93d8'
                }, {
                    name: 'Male (ARR)',
                    data: [
                        0, 462732, 1420200, 576120, 897226, 362229,
                    ],
                    color: '#673ab7'
                }, {
                    name: 'Female (HW)',
                    data: [
                        147164, 300014, 799311, 417782, 1304723, 88341,
                    ],
                    color: '#81d4fa'
                }, {
                    name: 'Male (HW)',
                    data: [
                        90158, 495770, 1570880, 620355, 961176, 389764,
                    ],
                    color: '#03a9f4'
                }]
            });
        });
    </script>

    <script>
        $(function() {
            $('#exp_race_gender_distribution').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: [
                        "Au",
                        "Elezen",
                        "Hyur",
                        "Lalafell",
                        "Miqo'te",
                        "Roegadyn",
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
                    name: 'Female (ARR)',
                    data: [
                        0, 35570, 130884, 87036, 238166, 14562,
                    ],
                    color: '#ce93d8'
                }, {
                    name: 'Male (ARR)',
                    data: [
                        0, 62534, 193460, 114548, 131640, 46757,
                    ],
                    color: '#673ab7'
                }, {
                    name: 'Female (HW)',
                    data: [
                        80772, 34885, 123774, 85646, 222213, 14161,
                    ],
                    color: '#81d4fa'
                }, {
                    name: 'Male (HW)',
                    data: [
                        48064, 62409, 200606, 122302, 127576, 47265,
                    ],
                    color: '#03a9f4'
                }]
            });
        });
    </script>

    <script>
        $(function() {
            $('#class_distribution').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: [
                        'Gladiator', 'Pugilist', 'Marauder', 'Lancer', 'Archer', 'Rogue', 'Conjurer', 'Thaumaturge', 'Arcanist', 'Dark Knight', 'Machinist', 'Astrologian', 'Carpenter', 'Blacksmith', 'Armorer', 'Goldsmith', 'Leatherworker', 'Weaver', 'Alchemist', 'Culinarian', 'Miner', 'Botanist', 'Fisher',
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
                    name: 'A Realm Reborn',
                    data: [
                        2137862, 1686004, 1616594, 1683251, 1957426, 535016, 1963615, 1878907, 1779842, 0, 0, 0, 1012620, 927633, 928624, 1030476, 962100, 1030995, 914994, 937728, 1177654, 1044183, 974050,
                    ],
                    color: '#673ab7'
                }, {
                    name: 'Heavensward',
                    data: [
                        2365352, 1852388, 1772240, 1857175, 2148966, 673775, 2137259, 2056058, 1949381, 238275, 209575, 276504, 1091148, 1002445, 1003215, 1107921, 1037457, 1107843, 984909, 1013800, 1265851, 1123321, 1046762,
                    ],
                    color: '#03a9f4'
                }]
            });
        });
    </script>

  </body>

</html>
