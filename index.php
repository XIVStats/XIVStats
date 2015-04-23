<?php

$db = new SQLite3('players.db');

// Get highest ID in database
$highest_id = array();
$highest_id_results = $db->query('SELECT MAX(id) FROM players');
$highest_id = $highest_id_results->fetchArray();

// Gets total number of players in database
$player_count = array();
$player_count_results = $db->query('SELECT count() FROM players');
$player_count = $player_count_results->fetchArray();

// Get statistics on realm population
$realm_pop = array();
$realm_pop_results = $db->query('SELECT realm,count() FROM players GROUP BY realm ORDER BY realm ASC');

while ($row = $realm_pop_results->fetchArray()) {
	//$row[0] = realm name
	//$row[1] = population
	$realm_pop[$row[0]] = $row[1];
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

$class_results = $db->query("select count() from players where level_gladiator = '50'");
$classes_50["Gladiator"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_pugilist = '50'");
$classes_50["Pugilist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_marauder = '50'");
$classes_50["Marauder"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_lancer = '50'");
$classes_50["Lancer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_archer = '50'");
$classes_50["Archer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_rogue = '50'");
$classes_50["Rogue"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_conjurer = '50'");
$classes_50["Conjurer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_thaumaturge = '50'");
$classes_50["Thaumaturge"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_arcanist = '50'");
$classes_50["Arcanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_carpenter = '50'");
$classes_50["Carpenter"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_blacksmith = '50'");
$classes_50["Blacksmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_armorer = '50'");
$classes_50["Armorer"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_goldsmith = '50'");
$classes_50["Goldsmith"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_leatherworker = '50'");
$classes_50["Leatherworker"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_weaver = '50'");
$classes_50["Weaver"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_alchemist = '50'");
$classes_50["Alchemist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_culinarian = '50'");
$classes_50["Culinarian"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_miner = '50'");
$classes_50["Miner"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_botanist = '50'");
$classes_50["Botanist"] = $class_results->fetchArray()[0];

$class_results = $db->query("select count() from players where level_fisher = '50'");
$classes_50["Fisher"] = $class_results->fetchArray()[0];

?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
  <table>
    <tr/>
      <td>IDs scanned:</td>
      <td><?php
        echo $highest_id[0];
      ?></td>
    </tr>
    <tr>
      <td>Players in database:</td>
      <td><?php
        echo $player_count[0];
      ?></td>
    </tr>
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
            name: '# of Characters',
            data: [
                <?php
                        foreach ($realm_pop as $key => $value) {
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
            name: '# of Characters',
            data: [
                <?php
                        foreach ($classes as $key => $value) {
                                echo "$value,";
                        }
                ?>
            ]
        }]
    });
});
</script>

<div id="class_50_distribution" style="min-width: 400px; height: 600px; margin: 0 auto"></div>

<script>
$(function () {
    $('#class_50_distribution').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Level 50 Class Distribution'
        },
        xAxis: {
            categories: [
                <?php
                        foreach ($classes_50 as $key => $value) {
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
            name: '# of Characters',
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
      The statistics collector is open source! View it at: <a href="https://mygitlab.org/pricetx/ffxiv_player_stats/tree/master">https://mygitlab.org/pricetx/ffxiv_player_stats/tree/master </a>
    </p>

 </body>

</html>
