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

?>

<html>

  <head>
    <title>XIVStats - Heavensward Comparison</title>
  </head>

  <body>
    <h1>XIVStats - Comparison of A Realm Reborn to Heavensward</h1>

    <p>(Any reference to "EXP" players, refers to players with at least one skill at level 50)</p>

    <p>The left side shows statistics from April 2015, the right side shows statistics from the end of July 2015</p>

    <p>How many players are there?</p>

    <p>World:</p>
    <p><?php echo $old_player_count; ?>  -> <?php echo $new_player_count; ?> (+<?php echo $new_player_count - $old_player_count ?>)</p>
    <p>(EXP) <?php echo $old_exp_player_count; ?> -> <?php echo $new_exp_player_count; ?> (+<?php echo $new_exp_player_count - $old_exp_player_count ?>)</p>

    <p>America:</p>
    <p><?php echo $old_america_player_count; ?> -> <?php echo $new_america_player_count; ?> (+<?php echo $new_america_player_count - $old_america_player_count ?>)</p>
    <p>(EXP) <?php echo $old_america_exp_player_count; ?> -> <?php echo $new_america_exp_player_count; ?> (+<?php echo $new_america_exp_player_count - $old_america_exp_player_count ?>)</p>

    <p>Japan:</p>
    <p><?php echo $old_japan_player_count; ?> -> <?php echo $new_japan_player_count; ?> (+<?php echo $new_japan_player_count - $old_japan_player_count ?>)</p>
    <p>(EXP) <?php echo $old_japan_exp_player_count; ?> -> <?php echo $new_japan_exp_player_count; ?> (+<?php echo $new_japan_exp_player_count - $old_japan_exp_player_count ?>)</p>

    <p>Europe:</p>
    <p><?php echo $old_europe_player_count; ?> -> <?php echo $new_europe_player_count; ?> (+<?php echo $new_europe_player_count - $old_europe_player_count ?>)</p>
    <p>(EXP) <?php echo $old_europe_exp_player_count; ?> -> <?php echo $new_europe_exp_player_count; ?> (+<?php echo $new_europe_exp_player_count - $old_europe_exp_player_count ?>)</p>

    <p>Grand Company Distribution:</p>

    <p>Grand Company Distribution (experienced):</p>

    <p>Race / Gender Distribution:</p>

    <p>Race / Gender Distribution (experienced):</p>

    <p>Class Distribution:</p>

    <p>Class Distribution (experienced):</p>

    <p>Have any ideas for further information? Let me know at GitHub</p>

  </body>

</html>
