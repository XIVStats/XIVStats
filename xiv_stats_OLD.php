<?php

$db = new SQLite3('players-20150801.db');

// Gets total number of players in database
$player_count = array();
$player_count_results = $db->query('SELECT count() FROM players');
$player_count = $player_count_results->fetchArray();

$player_50_count = array();
$player_50_count_results = $db->query("SELECT count() FROM players WHERE ((level_gladiator >= '50' AND level_gladiator != '') OR (level_pugilist >= '50' AND level_pugilist != '') OR
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
        (level_botanist >= '50' AND level_botanist != '') OR (level_fisher >= '50' AND level_fisher != ''));");
$player_50_count = $player_50_count_results->fetchArray();

// Get total number of players in each region

// Japan
$japanese_total = array();
$japanese_total_results = $db->query("SELECT count() FROM players WHERE realm = 'Alexander' OR realm = 'Bahamut' OR realm = 'Durandal'
	OR realm = 'Fenrir' OR realm = 'Ifrit' OR realm = 'Ridill' OR realm = 'Tiamat' OR realm = 'Ultima' OR realm = 'Valefor' OR
	realm = 'Yojimbo'  OR realm = 'Zeromus' OR realm = 'Anima' OR realm = 'Asura' OR realm = 'Belias' OR realm = 'Chocobo' OR
	realm = 'Hades' OR realm = 'Ixion' OR realm = 'Mandragora' OR realm = 'Masamune' OR realm = 'Pandaemonium' OR realm = 'Shinryu'
	OR realm = 'Titan' OR realm = 'Aegis' OR realm = 'Atomos' OR realm = 'Carbuncle' OR realm = 'Garuda' OR realm = 'Gungnir'
	OR realm = 'Kujata' OR realm = 'Ramuh' OR realm = 'Tonberry' OR realm = 'Typhon' OR realm = 'Unicorn';");
$japanese_total = $japanese_total_results->fetchArray();

$japanese_50_total = array();
$japanese_50_total_results = $db->query("SELECT count() FROM players WHERE (realm = 'Alexander' OR realm = 'Bahamut' OR realm = 'Durandal'
        OR realm = 'Fenrir' OR realm = 'Ifrit' OR realm = 'Ridill' OR realm = 'Tiamat' OR realm = 'Ultima' OR realm = 'Valefor' OR
        realm = 'Yojimbo'  OR realm = 'Zeromus' OR realm = 'Anima' OR realm = 'Asura' OR realm = 'Belias' OR realm = 'Chocobo' OR
        realm = 'Hades' OR realm = 'Ixion' OR realm = 'Mandragora' OR realm = 'Masamune' OR realm = 'Pandaemonium' OR realm = 'Shinryu'
        OR realm = 'Titan' OR realm = 'Aegis' OR realm = 'Atomos' OR realm = 'Carbuncle' OR realm = 'Garuda' OR realm = 'Gungnir'
        OR realm = 'Kujata' OR realm = 'Ramuh' OR realm = 'Tonberry' OR realm = 'Typhon' OR realm = 'Unicorn') AND
	((level_gladiator >= '50' AND level_gladiator != '') OR (level_pugilist >= '50' AND level_pugilist != '') OR
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
        (level_botanist >= '50' AND level_botanist != '') OR (level_fisher >= '50' AND level_fisher != ''));");
$japanese_50_total = $japanese_50_total_results->fetchArray();

// America
$american_total = array();
$american_total_results = $db->query("SELECT count() FROM players WHERE realm = 'Behemoth' OR realm = 'Brynhildr' OR realm = 'Diabolos'
	OR realm = 'Excalibur' OR realm = 'Exodus' OR realm = 'Famfrit' OR realm = 'Hyperion' OR realm = 'Lamia' OR realm = 'Leviathan'
	OR realm = 'Malboro' OR realm = 'Ultros' OR realm = 'Adamantoise' OR realm = 'Balmung' OR realm = 'Cactuar' OR realm = 'Coeurl'
	OR realm = 'Faerie' OR realm = 'Gilgamesh' OR realm = 'Goblin' OR realm = 'Jenova' OR realm = 'Mateus' OR realm = 'Midgardsormr'
	OR realm = 'Sargatanas' OR realm = 'Siren' OR realm = 'Zalera';");
$american_total = $american_total_results->fetchArray();

$american_50_total = array();
$american_50_total_results = $db->query("SELECT count() FROM players WHERE (realm = 'Behemoth' OR realm = 'Brynhildr' OR realm = 'Diabolos'
        OR realm = 'Excalibur' OR realm = 'Exodus' OR realm = 'Famfrit' OR realm = 'Hyperion' OR realm = 'Lamia' OR realm = 'Leviathan'
        OR realm = 'Malboro' OR realm = 'Ultros' OR realm = 'Adamantoise' OR realm = 'Balmung' OR realm = 'Cactuar' OR realm = 'Coeurl'
        OR realm = 'Faerie' OR realm = 'Gilgamesh' OR realm = 'Goblin' OR realm = 'Jenova' OR realm = 'Mateus' OR realm = 'Midgardsormr'
        OR realm = 'Sargatanas' OR realm = 'Siren' OR realm = 'Zalera') AND
        ((level_gladiator >= '50' AND level_gladiator != '') OR (level_pugilist >= '50' AND level_pugilist != '') OR
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
        (level_botanist >= '50' AND level_botanist != '') OR (level_fisher >= '50' AND level_fisher != ''));");
$american_50_total = $american_50_total_results->fetchArray();


// Europe
$european_total = array();
$european_total_results = $db->query("SELECT count() FROM players WHERE realm = 'Cerberus' OR realm = 'Lich' OR realm = 'Moogle' OR
	realm = 'Odin' OR realm = 'Phoenix' OR realm = 'Ragnarok' OR realm = 'Shiva' OR realm = 'Zodiark';");
$european_total = $european_total_results->fetchArray();

$european_50_total = array();
$european_50_total_results = $db->query("SELECT count() FROM players WHERE (realm = 'Cerberus' OR realm = 'Lich' OR realm = 'Moogle' OR
        realm = 'Odin' OR realm = 'Phoenix' OR realm = 'Ragnarok' OR realm = 'Shiva' OR realm = 'Zodiark') AND
        ((level_gladiator >= '50' AND level_gladiator != '') OR (level_pugilist >= '50' AND level_pugilist != '') OR
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
        (level_botanist >= '50' AND level_botanist != '') OR (level_fisher >= '50' AND level_fisher != ''));");
$european_50_total = $european_50_total_results->fetchArray();

// Get statistics on realm population
$realm_pop = array();
$realm_pop_results = $db->query('SELECT realm,count() FROM players GROUP BY realm ORDER BY realm ASC');

while ($row = $realm_pop_results->fetchArray()) {
	//$row[0] = realm name
	//$row[1] = population
	$realm_pop[$row[0]] = $row[1];
}

// Get statistics on level 50 realm population
$realm_50_pop = array();
$realm_50_pop_results = $db->query("SELECT realm,count() FROM players WHERE level_gladiator = '50' OR level_pugilist = '50' OR level_marauder = '50'
	OR level_lancer = '50' OR level_archer = '50' OR level_rogue = '50' OR level_conjurer = '50' OR level_thaumaturge = '50'
	OR level_arcanist = '50' OR level_darkknight = '50' OR level_machinist = '50' OR level_astrologian = '50' OR level_carpenter = '50'
        OR level_blacksmith = '50' OR level_armorer = '50' OR level_goldsmith = '50'
	OR level_leatherworker = '50' OR level_weaver = '50' OR level_alchemist = '50' OR level_culinarian = '50' OR level_miner = '50'
	OR level_botanist = '50' OR level_fisher = '50' GROUP BY realm ORDER BY realm ASC");

while ($row = $realm_50_pop_results->fetchArray()) {
        //$row[0] = realm name
        //$row[1] = population
        $realm_50_pop[$row[0]] = $row[1];
}


// Get statistics on Grand Company population
$gc_pop = array();
$gc_pop_results = $db->query('select grand_company,count() from players group by grand_company');

while ($row = $gc_pop_results->fetchArray()) {
	//$row[0] = grand company name
	//$row[1] = population

	//If user is not in gc, mark gc as none rather than blank
	if ($row[0] === '') { $row[0] = "None"; }
	$gc_pop[$row[0]] = $row[1];
}

// Grand company population with at least one class at level 50
$gc_50_pop = array();
$gc_50_pop_results = $db->query("select grand_company,count() from players WHERE ((level_gladiator >= '50' AND level_gladiator != '') OR (level_pugilist >= '50' AND level_pugilist != '') OR
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
        (level_botanist >= '50' AND level_botanist != '') OR (level_fisher >= '50' AND level_fisher != ''))
	group by grand_company");

while ($row = $gc_50_pop_results->fetchArray()) {
        //$row[0] = grand company name
        //$row[1] = population

        //If user is not in gc, mark gc as none rather than blank
        if ($row[0] === '') { $row[0] = "None"; }
        $gc_50_pop[$row[0]] = $row[1];
}


// Get statistics on race / gender distribution
$race_gender_male = array();
$race_gender_female = array();
$race_gender_results = $db->query('SELECT race,gender,count() FROM players GROUP BY race,gender');

while ($row = $race_gender_results->fetchArray()) {
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

// Get statistics on class adoption
$classes = array();

$class_results = $db->query("select count() from players where level_gladiator != ''");
$classes["Gladiator"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_pugilist != ''");
$classes["Pugilist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_marauder != ''");
$classes["Marauder"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_lancer != ''");
$classes["Lancer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_archer != ''");
$classes["Archer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_rogue != ''");
$classes["Rogue"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_conjurer != ''");
$classes["Conjurer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_thaumaturge != ''");
$classes["Thaumaturge"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_arcanist != ''");
$classes["Arcanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_darkknight != ''");
$classes["Dark Knight"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_machinist != ''");
$classes["Machinist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_astrologian != ''");
$classes["Astrologian"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_carpenter != ''");
$classes["Carpenter"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_blacksmith != ''");
$classes["Blacksmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_armorer != ''");
$classes["Armorer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_goldsmith != ''");
$classes["Goldsmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_leatherworker != ''");
$classes["Leatherworker"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_weaver != ''");
$classes["Weaver"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_alchemist != ''");
$classes["Alchemist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_culinarian != ''");
$classes["Culinarian"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_miner != ''");
$classes["Miner"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_botanist != ''");
$classes["Botanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_fisher != ''");
$classes["Fisher"] = $class_results->fetchArray()[0];

// Get statistics on level 50 class adoption
$classes_50 = array();

$class_results = $db->query("select count() from players where level_gladiator >= '50' AND level_gladiator != ''");
$classes_50["Gladiator"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_pugilist >= '50' AND level_pugilist != ''");
$classes_50["Pugilist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_marauder >= '50' AND level_marauder != ''");
$classes_50["Marauder"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_lancer >= '50' AND level_lancer != ''");
$classes_50["Lancer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_archer >= '50' AND level_archer != ''");
$classes_50["Archer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_rogue >= '50' AND level_rogue != ''");
$classes_50["Rogue"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_conjurer >= '50' AND level_conjurer != ''");
$classes_50["Conjurer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_thaumaturge >= '50' AND level_thaumaturge != ''");
$classes_50["Thaumaturge"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_arcanist >= '50' AND level_arcanist != ''");
$classes_50["Arcanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_darkknight >= '50' AND level_darkknight != ''");
$classes_50["Dark Knight"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_machinist >= '50' AND level_machinist != ''");
$classes_50["Machinist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_astrologian >= '50' AND level_astrologian != ''");
$classes_50["Astrologian"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_carpenter >= '50' AND level_carpenter != ''");
$classes_50["Carpenter"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_blacksmith >= '50' AND level_blacksmith != ''");
$classes_50["Blacksmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_armorer >= '50' AND level_blacksmith != ''");
$classes_50["Armorer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_goldsmith >= '50' AND level_goldsmith != ''");
$classes_50["Goldsmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_leatherworker >= '50' AND level_leatherworker != ''");
$classes_50["Leatherworker"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_weaver >= '50' AND level_leatherworker != ''");
$classes_50["Weaver"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_alchemist >= '50' AND level_alchemist != ''");
$classes_50["Alchemist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_culinarian >= '50' AND level_culinarian != ''");
$classes_50["Culinarian"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_miner >= '50' AND level_miner != ''");
$classes_50["Miner"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_botanist >= '50' AND level_botanist != ''");
$classes_50["Botanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_fisher >= '50' AND level_fisher != ''");
$classes_50["Fisher"] = $class_results->fetchArray()[0];

// Get 20 most common names, and their frequency
$common_name_results = $db->query("SELECT name,COUNT(name) FROM players GROUP BY name ORDER BY COUNT(name) DESC LIMIT 20;");
$common_name = array();

while ($row = $common_name_results->fetchArray()) {
        //$row[0] = name
        //$row[1] = frequency

       $common_name[$row[0]] = $row[1];
}

?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
  <table border="1">
    <tr>
      <td>Region:</td>
      <td>Total characters</td>
      <td>At least one level 50 class</td>
    </tr>
    <tr>
      <td>Combined</td>
      <td><?php echo $player_count[0]; ?></td>
      <td><?php echo $player_50_count[0]; ?></td>
    </tr>
    <tr>
      <td>Japan</td>
      <td><?php echo $japanese_total[0]; ?></td>
      <td><?php echo $japanese_50_total[0]; ?></td>
    </tr>
    <tr>
      <td>America</td>
      <td><?php echo $american_total[0]; ?></td>
      <td><?php echo $american_50_total[0]; ?></td>
    </tr>
    <tr>
      <td>Europe</td>
      <td><?php echo $european_total[0]; ?></td>
      <td><?php echo $european_50_total[0]; ?></td>
    </tr>
  </table>

  <p>20 Most common player names</p>

  <table border="1">
    <tr>
      <td>Name</td>
      <td># of realms</td>
    </tr>
    <?php
      foreach ($common_name as $key => $value) {
        echo "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
      }
    ?>
  </table>

  <div id="realm_distribution" style="min-width: 400px; height: 600px; margin: 0 auto"></div>

<script>
$(function () {
    $('#realm_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Realm Population Distribution'
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($realm_pop as $key => $value) {
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
            name: 'All Characters',
            data: [
                <?php
                        foreach ($realm_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ]
        }, {
            name: 'At least one level 50 class',
            data: [
                <?php
                        foreach ($realm_50_pop as $value) {
                                echo "$value,";
                        }
                ?>
            ]
        }]
    });
});

</script>

  <div id="gc_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

<script>
$(function () {
    $('#gc_distribution').highcharts({
        chart: {
        },
        title: {
            text: 'Grand Company Population Distribution'
        },
        plotOptions: {
            pie: {
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
                        foreach ($gc_pop as $key => $value) {
                                echo "['$key', $value,],\n";
                        }
                ?>
            ]
        }]
    });
});
</script>

  <div id="gc_50_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

<script>
$(function () {
    $('#gc_50_distribution').highcharts({
        chart: {
        },
        title: {
            text: 'Grand Company Population Distribution (with one class at or above level 50)'
        },
        plotOptions: {
            pie: {
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
                        foreach ($gc_50_pop as $key => $value) {
                                echo "['$key', $value,],\n";
                        }
                ?>
            ]
        }]
    });
});
</script>

  <div id="race_gender_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

<script>
$(function () {
    $('#race_gender_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Race / Gender Distribution'
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
            ]
        }, {
            name: 'Male',
            data: [
                <?php
                        foreach ($race_gender_male as $value) {
                                echo "$value,";
                        }
                ?>
            ]
        }]
    });
});
</script>

<div id="class_distribution" style="min-width: 400px; height: 600px; margin: 0 auto"></div>

<script>
$(function () {
    $('#class_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Class Distribution'
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
            name: 'All Characters',
            data: [
                <?php
                        foreach ($classes as $key => $value) {
                                echo "$value,";
                        }
                ?>
            ]
	}, {
	    name: 'At least one level 50 class',
	    data: [
               <?php
                        foreach ($classes_50 as $key => $value) {
                                echo "$value,";
                        }
                ?>
	    ]
        }]
    });
});
</script>

    <p>
      The statistics collector is open source! View it at: <a href="https://github.com/Pricetx/XIVStats">https://github.com/Pricetx/XIVStats</a>
    </p>

 </body>

</html>
