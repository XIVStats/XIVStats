<?php

const COLOR_TANK = "#3d51b1";
const COLOR_HEAL = "#3d6830";
const COLOR_DPS = "#783536";
const COLOR_HAND = "#674ea0";
const COLOR_LAND = "#a88d3b";

const KEY = "KEY";
const TITLE = "TITLE";
const COLOR = "COLOR";
const COUNT = "COUNT";
const TYPE = "TYPE";
const CLASS_GLA = array(KEY => "level_gladiator", TITLE => "Gladiator", COLOR => COLOR_TANK);
const CLASS_PUG = array(KEY => "level_pugilist", TITLE => "Pugilist", COLOR => COLOR_DPS);
const CLASS_MRD = array(KEY => "level_marauder", TITLE => "Marauder", COLOR => COLOR_TANK);
const CLASS_LNC = array(KEY => "level_lancer", TITLE => "Lancer", COLOR => COLOR_DPS);
const CLASS_ARC = array(KEY => "level_archer", TITLE => "Archer", COLOR => COLOR_DPS);
const CLASS_ROG = array(KEY => "level_rogue", TITLE => "Rogue", COLOR => COLOR_DPS);
const CLASS_CNJ = array(KEY => "level_conjurer", TITLE => "Conjurer", COLOR => COLOR_HEAL);
const CLASS_THM = array(KEY => "level_thaumaturge", TITLE => "Thaumaturge", COLOR => COLOR_DPS);
const CLASS_ACN = array(KEY => "level_arcanist", TITLE => "Arcanist", COLOR => COLOR_DPS);
const CLASS_SCH = array(KEY => "level_scholar", TITLE => "Scholar", COLOR => COLOR_HEAL);
const CLASS_DRK = array(KEY => "level_darkknight", TITLE => "Dark Knight", COLOR => COLOR_TANK);
const CLASS_MCH = array(KEY => "level_machinist", TITLE => "Machinist", COLOR => COLOR_DPS);
const CLASS_AST = array(KEY => "level_astrologian", TITLE => "Astrologian", COLOR => COLOR_HEAL);
const CLASS_SAM = array(KEY => "level_samurai", TITLE => "Samurai", COLOR => COLOR_DPS);
const CLASS_RDM = array(KEY => "level_redmage", TITLE => "Red Mage", COLOR => COLOR_DPS);
const CLASS_BLU = array(KEY => "level_bluemage", TITLE => "Blue Mage", COLOR => COLOR_DPS);
const CLASS_GNB = array(KEY => "level_gunbreaker", TITLE => "Gunbreaker", COLOR => COLOR_TANK);
const CLASS_DNC = array(KEY => "level_dancer", TITLE => "Dancer", COLOR => COLOR_DPS);
const CLASS_CRP = array(KEY => "level_carpenter", TITLE => "Carpenter", COLOR => COLOR_HAND);
const CLASS_BSM = array(KEY => "level_blacksmith", TITLE => "Blacksmith", COLOR => COLOR_HAND);
const CLASS_ARM = array(KEY => "level_armorer", TITLE => "Armorer", COLOR => COLOR_HAND);
const CLASS_GSM = array(KEY => "level_goldsmith", TITLE => "Goldsmith", COLOR => COLOR_HAND);
const CLASS_LWR = array(KEY => "level_leatherworker", TITLE => "Leatherworker", COLOR => COLOR_HAND);
const CLASS_WVR = array(KEY => "level_weaver", TITLE => "Weaver", COLOR => COLOR_HAND);
const CLASS_ALC = array(KEY => "level_alchemist", TITLE => "Alchemist", COLOR => COLOR_HAND);
const CLASS_CUL = array(KEY => "level_culinarian", TITLE => "Culinarian", COLOR => COLOR_HAND);
const CLASS_MIN = array(KEY => "level_miner", TITLE => "Miner", COLOR => COLOR_LAND);
const CLASS_BTN = array(KEY => "level_botanist", TITLE => "Botanist", COLOR => COLOR_LAND);
const CLASS_FSH = array(KEY => "level_fisher", TITLE => "Fisher", COLOR => COLOR_LAND);

const GEAR_MAIN_HAND = array(KEY => 'main_hand', TITLE => "Main Hand");
const GEAR_OFF_HAND = array(KEY => 'off_hand', TITLE => "Off Hand");
const GEAR_HEAD = array(KEY => 'head', TITLE => 'Head');
const GEAR_BODY = array(KEY => 'body', TITLE => 'Body');
const GEAR_HANDS = array(KEY => 'hands', TITLE => 'Hands');
const GEAR_WAIST = array(KEY => 'waist', TITLE => 'Waist');
const GEAR_LEGS = array(KEY => 'legs', TITLE => 'Legs');
const GEAR_FEET = array(KEY => 'feet', TITLE => 'Feet');
const GEAR_EARS = array(KEY => 'ears', TITLE => 'Ears');
const GEAR_NECK = array(KEY => 'neck', TITLE => "Neck");
const GEAR_WRISTS = array(KEY => 'wrists', TITLE => "Wrists");
const GEAR_LEFT_HAND = array(KEY => 'left_hand', TITLE => "Rings");
const GEAR_RIGHT_HAND = array(KEY => 'right_hand', TITLE => "Rings");
const GEAR_JOB_CRYSTAL = array(KEY => 'job_crystal', TITLE => "Job Crystal");

// Helper function to fetch the sum of all values in the array, where the array key matches one of the specified realm names
function sumInRegion($data, $regional_realms) {
        return array_sum(array_intersect_key($data, array_flip($regional_realms)));
}

// Helper function that returns zero if the value supplied isn't set
function getValue($value) {
    return isset($value) ? $value : 0;
}

// Helper function to return the value of the requested key, or zero if one isn't available
function getValueFromArray($data, $key) {
        return getValue($data[$key]);
}

// Helper function to increment class count into the supplied total array
function handleClass($row, $classDef, &$totalArray) {
    if(!isset($totalArray[$classDef[KEY]])) {
        $totalArray[$classDef[KEY]] = array();
        $totalArray[$classDef[KEY]][COUNT] = 0;
        $totalArray[$classDef[KEY]][TYPE] = $classDef;
    }
    $level = isset($row[$classDef[KEY]]) ? $row[$classDef[KEY]] : 0;
    if($level > 0) {
        $totalArray[$classDef[KEY]][COUNT]++;
    }
    return 0;
}

function handleEurekaLevel($eurekaLevel, $mounts, &$totalArray) {
    $eurekaCategory = "None";
    if(in_array("Demi-Ozma", $mounts)) {
        $eurekaCategory = "Baldesion Arsenal";
    } else if($eurekaLevel > 50) {
        $eurekaCategory = "Hydatos (50-60)";
    } else if($eurekaLevel > 35) {
        $eurekaCategory = "Pyros (35-50)";
    } else if($eurekaLevel > 20) {
        $eurekaCategory = "Pagos (20-35)";
    } else if($eurekaLevel > 0) {
        $eurekaCategory = "Anemos (1-20)";
    }

    $totalArray[$eurekaCategory]++;
}

function handleGear($row, $gearSlot, &$totalArray) {
    // If counters not initialised, then do so
    if(!isset($totalArray[$gearSlot[TITLE]])) {
        $totalArray[$gearSlot[TITLE]] = array();
    }

    // Check if there's even an item in the slot
    if(isset($row[$gearSlot[KEY]])) {
        $itemId = $row[$gearSlot[KEY]];

        if(!isset($totalArray[$gearSlot[TITLE]][$itemId])) {
            $totalArray[$gearSlot[TITLE]][$itemId] = 0;
        }
        
        $totalArray[$gearSlot[TITLE]][$itemId]++;
    }
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

$european_realm_array = array("Cerberus","Lich","Moogle","Odin","Phoenix","Ragnarok","Shiva","Zodiark","Louisoix","Omega",
                              "Spriggan","Twintania");
sort($european_realm_array);

$gear_cache = array();

$gear_query = $db->query("SELECT * FROM gear_items;", MYSQLI_USE_RESULT);
while($row = $gear_query->fetch_assoc()) {
    $gear_cache[$row['item_id']] = array("name" => $row['name'], "category" => $row['category'], "ilevel" => $row['ilevel']);
}

// Variables
$player_count = 0;
$active_player_count = 0;
$deleted_player_count = 0;
$realm_count = array();
$active_realm_count = array();
$gc_count = array();
$active_gc_count = array();
$race_gender_count = array();
$active_race_gender_count = array();

$classes = array();
$active_classes = array();

$eureka = array();
$eureka["Baldesion Arsenal"] = 0;
$eureka["Hydatos (50-60)"] = 0;
$eureka["Pyros (35-50)"] = 0;
$eureka["Pagos (20-35)"] = 0;
$eureka["Anemos (1-20)"] = 0;
$eureka["None"] = 0;

$active_eureka = array();
$active_eureka["Baldesion Arsenal"] = 0;
$active_eureka["Hydatos (50-60)"] = 0;
$active_eureka["Pyros (35-50)"] = 0;
$active_eureka["Pagos (20-35)"] = 0;
$active_eureka["Anemos (1-20)"] = 0;
$active_eureka["None"] = 0;

$gear = array();
$active_gear = array();

$gear_ilevel = 0;
$active_gear_ilevel = 0;

$prearr = 0;
$prehw = 0;
$presb = 0;
$preshb = 0;
$ps4_collectors = 0;
$pc_collectors = 0;
$arrartbook = 0;
$sbartbook = 0;
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
$beast_tribes["Ixal"] = 0;
// Heavensward
$beast_tribes["Vanu Vanu"] = 0;
$beast_tribes["Vath"] = 0;
$beast_tribes["Moogle"] = 0;
// Stormblood
$beast_tribes["Kojin"] = 0;
$beast_tribes["Ananta"] = 0;
$beast_tribes["Namazu"] = 0;

$player_overview_query = $db->query("SELECT * FROM tblplayers p LEFT JOIN character_gear_sets s ON p.id = s.player_id;", MYSQLI_USE_RESULT);
while($row = $player_overview_query->fetch_assoc()) {
    // Skip deleted characters
    if(isset($row["character_status"]) && $row["character_status"] == "DELETED") {
        $deleted_player_count++;
        continue;
    }

    // Expand the mounts & minions to an array
    $mounts = isset($row["mounts"]) ? str_getcsv($row["mounts"]) : array();
    $minions = isset($row["minions"]) ? str_getcsv($row["minions"]) : array();

    // Basic data
    $realm = isset($row["realm"]) ? $row["realm"] : 'Unknown';
    $grand_company = isset($row["grand_company"]) ? $row["grand_company"] : 'Unknown';
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

    handleClass($row, CLASS_GLA, $classes);
    handleClass($row, CLASS_PUG, $classes);
    handleClass($row, CLASS_MRD, $classes);
    handleClass($row, CLASS_LNC, $classes);
    handleClass($row, CLASS_ARC, $classes);
    handleClass($row, CLASS_ROG, $classes);
    handleClass($row, CLASS_CNJ, $classes);
    handleClass($row, CLASS_THM, $classes);
    handleClass($row, CLASS_ACN, $classes);
    handleClass($row, CLASS_SCH, $classes);
    handleClass($row, CLASS_DRK, $classes);
    handleClass($row, CLASS_MCH, $classes);
    handleClass($row, CLASS_AST, $classes);
    handleClass($row, CLASS_SAM, $classes);
    handleClass($row, CLASS_RDM, $classes);
    handleClass($row, CLASS_BLU, $classes);
    handleClass($row, CLASS_GNB, $classes);
    handleClass($row, CLASS_DNC, $classes);
    handleClass($row, CLASS_CRP, $classes);
    handleClass($row, CLASS_BSM, $classes);
    handleClass($row, CLASS_ARM, $classes);
    handleClass($row, CLASS_GSM, $classes);
    handleClass($row, CLASS_LWR, $classes);
    handleClass($row, CLASS_WVR, $classes);
    handleClass($row, CLASS_ALC, $classes);
    handleClass($row, CLASS_CUL, $classes);
    handleClass($row, CLASS_MIN, $classes);
    handleClass($row, CLASS_BTN, $classes);
    handleClass($row, CLASS_FSH, $classes);

    handleGear($row, GEAR_MAIN_HAND, $gear);
    handleGear($row, GEAR_OFF_HAND, $gear);
    handleGear($row, GEAR_HEAD, $gear);
    handleGear($row, GEAR_BODY, $gear);
    handleGear($row, GEAR_HANDS, $gear);
    handleGear($row, GEAR_LEGS, $gear);
    handleGear($row, GEAR_FEET, $gear);
    handleGear($row, GEAR_EARS, $gear);
    handleGear($row, GEAR_NECK, $gear);
    handleGear($row, GEAR_WRISTS, $gear);
    handleGear($row, GEAR_LEFT_HAND, $gear);
    handleGear($row, GEAR_RIGHT_HAND, $gear);
    handleGear($row, GEAR_JOB_CRYSTAL, $gear);

    handleEurekaLevel($row["level_eureka"], $mounts, $eureka);


    // Pre-orders
    $prearr += isset($row["prearr"]) && $row["prearr"] == 1 ? 1 : 0;
    $fmt_prearr = number_format($prearr);
    $prehw += isset($row["prehw"]) && $row["prehw"] == 1 ? 1 : 0;
    $fmt_prehw = number_format($prehw);
    $presb += isset($row["presb"]) && $row["presb"] == 1 ? 1 : 0;
    $fmt_presb = number_format($presb);
    $preshb += isset($row["preshb"]) && $row["preshb"] == 1 ? 1 : 0;
    $fmt_preshb = number_format($preshb);

    // Collectors Edition
    $ps4_collectors += isset($row["ps4collectors"]) && $row["ps4collectors"] == 1 ? 1 : 0;
    $fmt_ps4_collectors = number_format($ps4_collectors);
    $pc_collectors += isset($row["arrcollector"]) && $row["arrcollector"] == 1 ? 1 : 0;
    $fmt_pc_collectors = number_format($pc_collectors);
    $shb_collectors += in_array("Grani", $mounts) ? 1 : 0;
    $fmt_shb_collectors = number_format($shb_collectors);

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
    $sbartbook += isset($row["sbartbook"]) && $row["sbartbook"] == 1 ? 1 : 0;
    $fmt_sbartbook = number_format($sbartbook);
    $sbartbooktwo += isset($row["sbartbooktwo"]) && $row["sbartbooktwo"] == 1 ? 1 : 0;
    $fmt_sbartbooktwo = number_format($sbartbooktwo);

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

    // Beast Tribes with dedicated columns in DB
    // A Realm Reborn
    $beast_tribes["Kobold"] += isset($row["kobold"]) && $row["kobold"] == 1 ? 1 : 0;
    $beast_tribes["Sahagin"] += isset($row["sahagin"]) && $row["sahagin"] == 1 ? 1 : 0;
    $beast_tribes["Amaljaa"] += isset($row["amaljaa"]) && $row["amaljaa"] == 1 ? 1 : 0;
    $beast_tribes["Sylph"] += isset($row["sylph"]) && $row["sylph"] == 1 ? 1 : 0;
    // Heavensward
    $beast_tribes["Vanu Vanu"] += isset($row["vanuvanu"]) && $row["vanuvanu"] == 1 ? 1 : 0;
    $beast_tribes["Vath"] += isset($row["vath"]) && $row["vath"] == 1 ? 1 : 0;
    $beast_tribes["Moogle"] += isset($row["moogle"]) && $row["moogle"] == 1 ? 1 : 0;
    // Stormblood

    // Bast tribes from minions
    $beast_tribes["Ixal"] += in_array("Wind-up Ixal", $minions) ? 1 : 0;
	$beast_tribes["Kojin"] += in_array("Wind-up Kojin", $minions) ? 1 : 0;
	$beast_tribes["Ananta"] += in_array("Wind-up Ananta", $minions) ? 1 : 0;        
	$beast_tribes["Namazu"] += in_array("Attendee #777", $minions) ? 1 : 0;         
  
    // Fetch total number of active players in database by checking for the Wind-up G'raha Tia minion received during 4.1 MSQ
    if(in_array("Wind-up G'raha Tia", $minions)) {  $active_player_count++;
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

        handleClass($row, CLASS_GLA, $active_classes);
        handleClass($row, CLASS_PUG, $active_classes);
        handleClass($row, CLASS_MRD, $active_classes);
        handleClass($row, CLASS_LNC, $active_classes);
        handleClass($row, CLASS_ARC, $active_classes);
        handleClass($row, CLASS_ROG, $active_classes);
        handleClass($row, CLASS_CNJ, $active_classes);
        handleClass($row, CLASS_THM, $active_classes);
        handleClass($row, CLASS_ACN, $active_classes);
        handleClass($row, CLASS_SCH, $active_classes);
        handleClass($row, CLASS_DRK, $active_classes);
        handleClass($row, CLASS_MCH, $active_classes);
        handleClass($row, CLASS_AST, $active_classes);
        handleClass($row, CLASS_SAM, $active_classes);
        handleClass($row, CLASS_RDM, $active_classes);
        handleClass($row, CLASS_BLU, $active_classes);
        handleClass($row, CLASS_GNB, $active_classes);
        handleClass($row, CLASS_DNC, $active_classes);
        handleClass($row, CLASS_CRP, $active_classes);
        handleClass($row, CLASS_BSM, $active_classes);
        handleClass($row, CLASS_ARM, $active_classes);
        handleClass($row, CLASS_GSM, $active_classes);
        handleClass($row, CLASS_LWR, $active_classes);
        handleClass($row, CLASS_WVR, $active_classes);
        handleClass($row, CLASS_ALC, $active_classes);
        handleClass($row, CLASS_CUL, $active_classes);
        handleClass($row, CLASS_MIN, $active_classes);
        handleClass($row, CLASS_BTN, $active_classes);
        handleClass($row, CLASS_FSH, $active_classes);

        handleGear($row, GEAR_MAIN_HAND, $active_gear);
        handleGear($row, GEAR_OFF_HAND, $active_gear);

        handleGear($row, GEAR_HEAD, $active_gear);
        handleGear($row, GEAR_EARS, $active_gear);

        handleGear($row, GEAR_BODY, $active_gear);
        handleGear($row, GEAR_NECK, $active_gear);

        handleGear($row, GEAR_HANDS, $active_gear);
        handleGear($row, GEAR_WRISTS, $active_gear);

        handleGear($row, GEAR_LEGS, $active_gear);
        handleGear($row, GEAR_LEFT_HAND, $active_gear);
        handleGear($row, GEAR_RIGHT_HAND, $active_gear);

        handleGear($row, GEAR_FEET, $active_gear);
        handleGear($row, GEAR_JOB_CRYSTAL, $active_gear);

        handleEurekaLevel($row["level_eureka"], $mounts, $active_eureka);
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
    <title>XIVCensus - Character statistics for FFXIV</title>
    <!-- FFXIV Official Tooltips-->
    <script src="https://img.finalfantasyxiv.com/lds/pc/global/js/eorzeadb/loader.js?v2"></script>
    <!-- JQuery Script--> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <!-- Highcharts--> 
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/sunburst.js"></script>
    <!-- Final Fantasy XIV Tooltip-->
    <script src="https://img.finalfantasyxiv.com/lds/pc/global/js/eorzeadb/loader.js?v2"></script>
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
          /*
            Core colour palette (https://coolors.co/303440-c3ac5c-702670-9e0000-039be5):
                - #212121 - Grey Darken-4 - Background
                - #c3ac5c - Gunmetel - Card background
                - #c3ac5c - Vega Gold - Titles and highlights
                - #702670 - Midnight - Main theme colour (Purple, Shadowbringers)
                - #9E0000 - USC Cardinal - Secondary theme color (Crimson, Stormblood)
                - #039BE5 - Vivid Ceruleum - Secondary theme color (Azure, Heavensward)

            Role colour palette:
                - #3d51b1 - Tank
                - #3d6830 - Healer
                - #783536 - DPS
                - #674ea0 - DoH
                - #a88d3b - DoL
          */

          .logo {
              float: left !important;
              width: 300px;
          }

          .card {
            background-color: #303440;
          }

          .card-title {
            color: #c3ac5c;
          }

          .card-content {
            color: white;
          }

          hr, .divider {
            color: #c3ac5c; 
            background-color: #c3ac5c;
            height: 3px;
            border: 0;
            margin-bottom: 15px;
          }
          
          .box-element {
              width: 100%;
          }

          #pageTitleBox {
              margin-top: 30px;
              margin-bottom: 0;
          }

          .section-title {
              text-align: left;
              color: #c3ac5c;
              font-size: 28pt;
              margin-top: 25px;
              margin-bottom: 25px;
          }

          .region-title {
              text-align: center;
              color: #c3ac5c;
              font-size: 28pt;
          }

          .region-subtitle {
              text-align: center;
              color: white;
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
            background-color: #702670;
          }

          .dropdown-button:hover, .waves-light:hover, .btn:hover {
            background-color: plum;
          }

          .dropdown-content li>a{
              color: black;
          }

          .dropdown-content li>a:hover {
              background-color: #c3ac5c;
              color: white;
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
              background-color: #c3ac5c;
          }
      </style>
  </head>

  <body class="grey darken-4">
  <div class="container box-element">
      <div class="row" id="pageTitleBox">
          <div class="col s12 m6" style="width:100%;">
              <div class="card">
                  <div class="card-content">
                      <a id="population"><span class="card-title" style="font-size:28pt;"><img src="img/logo.png" class="logo" title="XIVCensus - Character statistics for FFXIV"/></span></a>                 
                      <p>Statistics for <?php echo $date; ?></p>
                      <p><b>Any reference to "Active" characters, refers to characters that have claimed the following item: <br />
                      The minion for completing the Shadowbringers Main Scenario Quest from the Patch 5.0 story</b></p>
                    </div>
              </div>
          </div>
      </div>
        <div class="col s12 m12 navbar center">
          <!-- Navbar - 'Population', 'Realm Stats' & 'Other Stats' are dropdowns-->
          <a class='dropdown-button btn' href='#' data-activates='pop-dropdown'>Population of Eorzea</a>
          <a class='dropdown-button btn' href='#' data-activates='makeup-dropdown'>Makeup of Eorzea</a>
          <a class='dropdown-button btn' href='#' data-activates='content-dropdown'>Exploration of Eorzea</a>
          <a class='dropdown-button btn' href='#' data-activates='player-behaviours-dropdown'>Player Behaviours</a>
          <a class="waves-effect waves-light btn" href='#top'><i class="fa fa-arrow-up" aria-hidden="true"></i></a>

          <!-- Population Stats Dropdown -->
          <ul id='pop-dropdown' class='dropdown-content'>
              <li><a href="#population">World</a></li>
              <li><a href="#population-region">Regional</a></li>
              <li class="divider">Realm Breakdown</li>
              <li><a href="#population-realm-america">America</a></li>
              <li><a href="#population-realm-japan">Japan</a></li>
              <li><a href="#population-realm-europe">Europe</a></li>
          </ul>

          <!-- Character Makeup Dropdown -->
          <ul id="makeup-dropdown" class="dropdown-content">
              <li><a href="#racegender">Race & Gender</a></li>
              <li><a href="#class">Class & Job</a></li>
              <li><a href="#grandcompany">Grand Company Allegience</a></li>
          </ul>

          <!-- Content Dropdown -->
          <ul id="content-dropdown" class="dropdown-content">
              <li><a href="#gear">Equipped Gear</a></li>
              <li><a href="#beast">Beast Tribes</a></li>
              <li><a href="#eureka">Forbidden Land of Eureka</a></li>
              <li><a href="#misc-stats">Other Stats</a></li>
          </ul>

          <!-- Content Dropdown -->
          <ul id="player-behaviours-dropdown" class="dropdown-content">
              <li><a href="#preorders">Game Editions</a></li>
              <li><a href="#physical">Physical Items</a></li>
          </ul> 
        </div>

      <!-- Game Population Statistics -->
      <div class="section-title light">POPULATION OF EORZEA</div>

      <div class="card">
          <div class="card-content white-text">
              <a id="population"><span class="card-title light">HOW MANY CHARACTERS ARE THERE?</span></a>
              <hr/>
              <!--World-->
              <div class="section">
                  <div class="light region-title">WORLD</div>
                  <div class="light region-subtitle">ALL CHARACTERS</div>
                  <div class="row">
                      <div class="s12 m6 l6   region-stat">
                          <div><?php echo number_format($player_count) ?></div>
                      </div>
                  </div>
                  <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
                  <div class="row">
                      <div class="s12 m6 l6   region-stat">
                          <div><?php echo number_format($active_player_count) ?></div>
                      </div>
                  </div>
                  <div class="light region-subtitle">REMOVED CHARACTERS</div>
                  <div class="row">
                      <div class="s12 m6 l6   region-stat">
                          <div><?php echo number_format($deleted_player_count) ?></div>
                      </div>
                  </div>
              </div>
              <div class="divider"></div>
              <!-- By Region -->
              <div class="section">
                  <a id="population-region"></a>
                  <div class="row">
                    <div class="col m4 light region-title">AMERICA</div>
                    <div class="col m4 light region-title">JAPAN</div>
                    <div class="col m4 light region-title">EUROPE</div>
                  </div>
                  <div class="row">
                    <div class="col m4 light region-stat">
                      <div class="light region-subtitle">ALL CHARACTERS</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($realm_count, $american_realm_array)) ?></div>
                          </div>
                      </div>     
                    </div>
                    <div class="col m4 light region-stat">
                      <div class="light region-subtitle">ALL CHARACTERS</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($realm_count, $japanese_realm_array)) ?></div>
                          </div>
                      </div>
                    </div>
                    <div class="col m4 light region-stat">
                      <div class="light region-subtitle">ALL CHARACTERS</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($realm_count, $european_realm_array)) ?></div>
                          </div>
                      </div>
                  </div>
                  </div>
                  <div class="row">
                    <div class="col m4 light region-stat">
                      <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($active_realm_count, $american_realm_array)) ?></div>
                          </div>
                      </div>
                    </div>
                    <div class="col m4 light region-stat">
                      <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($active_realm_count, $japanese_realm_array)) ?></div>
                          </div>
                      </div>
                    </div>
                    <div class="col m4 light region-stat">
                      <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
                      <div class="row">
                          <div class="s12 m6 l6   region-stat">
                              <div><?php echo number_format(sumInRegion($active_realm_count, $european_realm_array)) ?></div>
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="card">
          <div class="card-content">
              <a id="population-realm"><span class="card-title light">WHICH REALMS DO CHARACTERS POPULATE?</span></a>
              <hr/>
              <a id="population-realm-america"><div class="light region-title">AMERICA</div></a>
              <div class="light region-subtitle">ALL CHARACTERS</div>
              <!-- Begin Chart -->
              <div id="america_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
              <!-- Begin Chart -->
              <div id="america_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <hr/>
              <a id="population-realm-japan"><div class="light region-title">JAPAN</div></a>
              <div class="light region-subtitle">ALL CHARACTERS</div>
              <!-- Begin Chart -->
              <div id="japan_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
              <!-- Begin Chart -->
              <div id="japan_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <hr/>
              <a id="population-realm-europe"><div class="light region-title">EUROPE</div></a>
              <div class="light region-subtitle">ALL CHARACTERS</div>
              <!-- Begin Chart -->
              <div id="europe_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
              <!-- Begin Chart -->
              <div id="europe_active_realm_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->

          </div>
      </div>

      <!-- Character Makeup Statistics -->
      <div class="section-title light">MAKEUP OF EORZEA</div>

      <div class="card">
          <div class="card-content">
              <a id="racegender"><span class="card-title light">RACE & GENDER</span></a>
              <hr/>
              <div class="light region-subtitle">ALL CHARACTERS</div>
              <!-- Begin Chart -->
              <div id="race_gender_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <hr/>
              <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
              <!-- Begin Chart -->
              <div id="active_race_gender_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
          </div>
      </div>
      <div class="card">
          <div class="card-content">
              <a id="class"><span class="card-title light">CLASS & JOB</span></a>
              <hr/>
              <div class="light region-subtitle">ALL CHARACTERS</div>
              <!-- Begin Chart -->
              <div id="class_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <hr/>
              <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
              <!-- Begin Chart -->
              <div id="active_class_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
          </div>
      </div>
      <div class="card">
          <div class="card-content">
              <a id="grandcompany"><span class="card-title light">GRAND COMPANY ALLEGIENCE</span></a>
              <hr/>
              <div class="row">
                <div class="col s12 m6">
                  <div class="light region-subtitle">ALL CHARACTERS</div>
                  <!-- Begin Chart -->
                  <div id="gc_distribution" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                  <!-- End Chart -->
                </div>
                <div class="col s12 m6">
                  <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
                  <!-- Begin Chart -->
                  <div id="gc_active_distribution" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                  <!-- End Chart -->
                </div>
              </div>
          </div>
      </div>

      <!-- Content Progression Statistics -->
      <div class="section-title light">EXPLORATION OF EORZEA</div>

      <div class="card">
        <div class="card-content">
            <a id="gear"></a>
            <span class="card-title light">EQUIPPED GEAR</span>
            <hr/>
            <div class="light region-subtitle">ACTIVE CHARACTERS* TOP 10 EQUIPPED GEAR ITEMS FOR EACH GEAR SLOT</div>
            <div class="row">
                <?php
                    foreach($active_gear as $slot => $gearTotals) {
                        arsort($gearTotals);
                        $i = 0;
                        $ilevel = 0;
                        $itemCount = 0;
                        foreach($gearTotals as $itemId => $count) {
                            if($i < 10) {
                                $ilevel = $ilevel + ($gear_cache[$itemId]['ilevel'] * $count);
                                $itemCount = $itemCount + $count;
                                $i++;
                            }
                        }
                    ?>
                <div class="col s12 m6 l6 xl6">
                    <div class="light region-title"><?php echo $slot ?></div>
                    <div class="light region-subtitle">
                         AVERAGE iLVL <?php echo round($ilevel / $itemCount)?>
                    </div>
                    <div id="equipped-<?php echo str_replace(' ','-',strtolower($slot)) ?>" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                </div>
                <?php } ?>
            </div>
        </div>
      </div>

      <div class="card">
          <div class="card-content">
              <a id="beast"></a>
              <span class="card-title light">BEAST TRIBES</span>
              <hr/>
              <div class="row light region-subtitle">ALL CHARACTERS</div>
              <!-- Begin Chart -->
              <div id="beast_tribes" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
              <!-- End Chart -->
              <div class="light region-subtitle">Redeemed Beast Tribe minion counted</div>
         </div>
      </div>

      <div class="card">
          <div class="card-content">
              <a id="eureka"><span class="card-title light">FORBIDDEN LAND OF EURKEA</span></a>
              <hr/>
              <div class="row">
                <div class="col s12 m6">
                  <div class="light region-subtitle">ALL CHARACTERS</div>
                  <!-- Begin Chart -->
                  <div id="eureka_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                  <!-- End Chart -->
                </div>
                <div class="col s12 m6">
                  <div class="light region-subtitle">ACTIVE CHARACTERS*</div>
                  <!-- Begin Chart -->
                  <div id="active_eureka_distribution" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                  <!-- End Chart -->
                </div>
              </div>
              <div class="light region-subtitle">Based on Elemental Level. <a href="https://eu.finalfantasyxiv.com/lodestone/playguide/db/item/b87459fa922/" class="eorzeadb_link">Demi-Ozma</a> mount counted for Baldesion Arsenal completion.</div>
          </div>
      </div>

      <div class="card">
          <div class="card-content">
              <a id="misc-stats"><span class="card-title light">OTHER</span></a>
              <hr/>
              <div class="light region-subtitle">GUEST AT AN ETERNAL BOND</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_saw_eternal_bond; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">MARRIED AT AN ETERNAL BOND</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_did_eternal_bond; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">EARNED 50 COMMENDATIONS</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_comm50; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">COMPLETED ARR HILDIBRAND QUESTLINE</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_hildibrand; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">COMPLETED ARR SIGHTSEEING LOG</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_sightseeing; ?></div>
                </div>
              </div>
         </div>
      </div>

      <!-- Player Behaviour Statistics -->
      <div class="section-title light">PLAYER BEHAVIOURS</div>

      <div class="card">
          <div class="card-content">
              <a id="preorders"><span class="card-title light">GAME EDITIONS</span></a>
              <hr/>
              <div class="row">
                <div class="col s6 light region-title">PRE-ORDER</div>
                <div class="col s6 light region-title">COLLECTORS EDITION</div>
              </div>
              <div class="row">
                <div class="col s6">
                    <div class="light region-subtitle">A REALM REBORN</div>
                    <div class="region-stat"><?php echo $fmt_prearr; ?></div>
                </div>
                <div class="col s6">
                    <div class="light region-subtitle">A REALM REBORN PC</div>
                    <div class="region-stat"><?php echo $fmt_pc_collectors; ?></div>
                    <div class="light region-subtitle">A REALM REBORN PS4</div>
                    <div class="region-stat"><?php echo $fmt_ps4_collectors; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col s6">
                    <div class="light region-subtitle">HEAVENSWARD</div>
                    <div class="region-stat"><?php echo $fmt_prehw; ?></div>
                </div>
                <div class="col s6">
                </div>
              </div>
              <div class="row">
                <div class="col s6">
                    <div class="light region-subtitle">STORMBLOOD</div>
                    <div class="region-stat"><?php echo $fmt_presb; ?></div>
                </div>
                <div class="col s6">
                </div>
              </div>
              <div class="row">
                <div class="col s6">
                    <div class="light region-subtitle">SHADOWBRINGERS</div>
                    <div class="region-stat"><?php echo $fmt_preshb; ?></div>
                </div>
                <div class="col s6">
                    <div class="light region-subtitle">SHADOWBRINGERS</div>
                    <div class="region-stat"><?php echo $fmt_shb_collectors; ?></div>
                </div>
              </div>
              <div class="light region-subtitle">Redeemed minion counted</div>
         </div>
      </div>

      <!-- Footnotes -->
      <div class="section-title light">ABOUT US</div>

      <div class="card">
          <div class="card-content">
              <a id="physical"><span class="card-title light">PHYSICAL ITEMS</span></a>
              <hr/>
              <div class="light region-subtitle">A REALM REBORN SOUNDTRACK</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_soundtrack; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">BEFORE METEOR SOUNDTRACK</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_beforemeteor; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">BEFORE THE FALL SOUNDTRACK</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_beforethefall; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">A REALM REBORN ARTBOOK</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_arrartbook; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">STORMBLOOD ARTBOOK - EASTERN MEMORIES</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_sbartbook; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">STORMBLOOD ARTBOOK - WESTERN MEMORIES</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_sbartbooktwo; ?></div>
                </div>
              </div>

              <div class="light region-subtitle">MOOGLE PLUSH</div>
              <div class="row">
                <div class=" s12 m6 l6   region-stat">
                  <div><?php echo $fmt_moogleplush; ?></div>
                </div>
              </div>

         </div>
      </div>

      <div class="card">
          <div class="card-content">
              <span class="card-title light"></span>
              <div class="light region-title"><a href="<?php echo "https://s3.eu-west-2.amazonaws.com/ffxivcensus.com/" . date("Y-m") . "/ffxivcensus-" . date("Y-m") . ".zip";?>">Download database (MySQL)</a></div>
          </div>

          <div class="card-content">
              <span class="card-title light"></span>
              <div class="light region-title"><a href="/list">View Previous Censuses</a></div>
          </div>
      </div>
	  

        <div class="card">
          <div class="card-content">
            <a id="population"><span class="card-title light">CONTRIBUTORS</span></a>
              <span class="card-title light"></span>
              <ul>
                <li>
                  <div class="light ">> <a href="https://www.linkedin.com/in/jonathanpriceuk/" target="_blank">Jonathan Price</a> | <a href="http://na.finalfantasyxiv.com/lodestone/character/8308898/" target="_blank">John Prycewood @ Ceberus</a></div>
                </li>
                <li>
                  <div class="light ">> <a href="https://twitter.com/ReidWeb" target="_blank">Peter Reid</a> | <a href="https://eu.finalfantasyxiv.com/lodestone/character/11886902/" target="_blank">P'tajha Rihll @ Ceberus</a></div>
                </li>
                <li>
                  <div class="light ">> <a href="https://github.com/matthewhillier" target="_blank">Matt Hillier</a> | <a href="https://eu.finalfantasyxiv.com/lodestone/character/2256025/" target="_blank">Russell Tyler @ Omega</a></div>
                </li>
                <li>
                  <div class="light ">> <a href="https://crakila.moe">Crakila (Padraig)</a> | <a href="https://eu.finalfantasyxiv.com/lodestone/character/1573466/">Crakila Fors'ee @ Ceberus</a></div>
                </li>
              </ul>
          </div>
        </div>

      <!-- End Container -->
  </div>
  <footer class="page-footer">
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
      $(function() {
        Highcharts.theme = {
            chart: {
                backgroundColor: '#303440'
            },

            colors: ['#702670','#9E0000','#0038A8'],

            legend: {
                itemStyle: {
                    color: '#ffffff'
                }

            },

            xAxis: {
                title: {
                    style: {
                        color: '#c3ac5c'
                    }
                },
                labels: {
                    style: {
                        color: '#ffffff'
                    }
                }
            },

            yAxis: {
                title: {
                    style: {
                        color: '#c3ac5c'
                    }
                },
                labels: {
                    style: {
                        color: '#ffffff'
                    }
                }
            },

            plotOptions: {
              column: {
                  borderWidth: 0
              },
              pie: {
                  borderWidth: 0,
                  colors: ['#212121', '#b71c1c', '#ffc107', '#9e9e9e'],
                  dataLabels: {
                    color: '#ffffff'
                  }
              },
              sunburst: {
                borderWidth: 0
              }
            },
        };

        Highcharts.setOptions(Highcharts.theme);
      });
  </script>

  <script>
      $(function () {
          $('#gc_distribution').highcharts({
              title: {
                  text: ''
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
                                      echo "['" . $key . "', " . getValue($value) . "],\n";
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
              title: {
                  text: ''
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
                                      echo "['" . $key . "', " . getValue($value) . "],\n";
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
              colors: ['#9E0000','#039BE5'],
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
                                      echo getValueFromArray($value, "female") . ",";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
                              foreach ($race_gender_count as $value) {
                                      echo getValueFromArray($value,"male") . ",";
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
              colors: ['#9E0000','#039BE5'],
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
                                      echo getValueFromArray($value, "female") . ",";
                              }
                      ?>
                  ],
              }, {
                  name: 'Male',
                  data: [
                      <?php
                              foreach ($active_race_gender_count as $value) {
                                      echo getValueFromArray($value,"male") . ",";
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
                                  echo json_encode($value[TYPE][TITLE]) . ',';
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
                  name: 'Characters',
                  data: [
                      <?php foreach ($classes as $key => $value) { ?>
                            {name: <?php echo json_encode($value[TYPE][TITLE]); ?>, y: <?php echo getValue($value[COUNT]); ?>, color: <?php echo json_encode($value[TYPE][COLOR]) ?> },
                      <?php } ?>
                  ]
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
                                  echo json_encode($value[TYPE][TITLE]) . ',';
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
                  name: 'Active Characters',
                  data: [
                      <?php foreach ($active_classes as $key => $value) { ?>
                            {name: <?php echo json_encode($value[TYPE][TITLE]); ?>, y: <?php echo getValue($value[COUNT]); ?>, color: <?php echo json_encode($value[TYPE][COLOR]) ?> },
                      <?php } ?>
                  ]
              }]
          });
      });
  </script>

  <script>
      $(function () {
          $('#eureka_distribution').highcharts({
              title: {
                  text: ''
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
                              foreach ($eureka as $key => $value) {
                                      echo "['" . $key . "', " . getValue($value) . "],\n";
                              }
                      ?>
                  ],
                  colors: ["#c3ac5c","#007bff","#f06e6e","#25afb7","#54cc65","#9e9e9e"]
              }]
          });
      });
  </script>

  <script>
      $(function () {
          $('#active_eureka_distribution').highcharts({
              title: {
                  text: ''
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
                              foreach ($active_eureka as $key => $value) {
                                      echo "['" . $key . "', " . getValue($value) . "],\n";
                              }
                      ?>
                  ],
                  colors: ["#c3ac5c","#007bff","#f06e6e","#25afb7","#54cc65","#9e9e9e"]
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
                                echo getValueFromArray($realm_count, $value) . ",";
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
                                echo getValueFromArray($active_realm_count, $value) . ",";
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
                                echo getValueFromArray($realm_count, $value) . ",";
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
                                echo getValueFromArray($active_realm_count, $value) . ",";
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
                                echo getValueFromArray($realm_count, $value) . ",";
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
                                echo getValueFromArray($active_realm_count, $value) . ",";
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
                                      echo getValue($value) . ",";
                              }
                      ?>
                  ],
              }]
          });
      });
  </script>

  <script>
    <?php foreach($active_gear as $slot => $gearTotals) {?>
      $(function () {
          $('#equipped-<?php echo str_replace(' ','-',strtolower($slot)) ?>').highcharts({
              title: {
                  text: ''
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
                        arsort($gearTotals);
                        $i = 0;
                        foreach ($gearTotals as $itemId => $count) {
                            if($i < 10) {
                                echo "{name:" . json_encode($gear_cache[$itemId]['name']) . ",y: " . getValue($count) . ", url: 'https://eu.finalfantasyxiv.com/lodestone/playguide/db/item/" . $itemId . "/'},\n";
                                $i++;
                            }
                        }
                      ?>
                  ],
                  colors: ['#702670', '#581845', '#5D254C', '#623254', '#673F5B', '#6C4C62', '#71596A', '#766671', '#7B7378', '#7F7E7F']
              }],
              plotOptions: {
                  pie: {
                      cursor: 'pointer',
                      point: {
                          events: {
                              click: function() {
                                  window.open(this.options.url);
                              }
                          }
                      }
                  }
              }
          });
      });
    <?php }; ?>
  </script>
  </body>

</html>
