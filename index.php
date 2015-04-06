<?php

$db = new SQLite3('players.db');


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

?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
  <div style="min-width: 400px; margin: 0 auto"><h1>Players in database:
    <?php
	echo $player_count[0];
    ?>
    </h1></div>
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
                                echo "'$key',\n";
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



    <p>
      The statistics collector is open source! View it at: <a href="https://mygitlab.org/pricetx/ffxiv_player_stats/tree/master">https://mygitlab.org/pricetx/ffxiv_player_stats/tree/master </a>
    </p>

 </body>

</html>

<?php
//select grand_company,count() from players group by grand_company; (get the number of people in each grand company
//SELECT race,gender,count() FROM players GROUP BY race,gender; (get the number of males and females in each race)
//SELECT * FROM players WHERE name LIKE '%%'; (get stats by name)
//AND realm = ''; // Do one of the other searches, but for a specific realm
//SELECT count(NULLIF(level_gladiator, '')) FROM players;
?>
