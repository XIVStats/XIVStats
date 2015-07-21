#!/usr/bin/env ruby

class XIVStats

  def initialize
    # Set to the desired path for the database to be written to
    # If no directory specified, pwd is used
    @db_path = "players.db"

    require 'open-uri'
    require 'sqlite3'
    require_relative 'player'
    if ARGV.empty?
      puts "Usage: xiv_stats.rb <lowest_id> <highest_id>"
      exit 1
    end
    @lowest_id = ARGV[0].to_i
    @highest_id = ARGV[1].to_i
    if @highest_id < @lowest_id
      puts "Error: The second argument needs to be greater than the first argument"
    end
  end

  # Fetches a lodestone profile page, and returns it as an array of strings
  def get_player_page(player_id)
    page = Array.new
    begin
      open("http://eu.finalfantasyxiv.com/lodestone/character/#{player_id}/") do |f|
        f.each_line { |line| page.push line }
      end
    rescue
      #puts "ID: #{player_id} does not exist"
      return false
    end
    return page
  end

  # Given a lodestone profile page, return the name of the player
  def get_name(page)
    # Get the line that has the player's name
    name = page.grep(/og:title/)
    # Make the name the beginning of the line
    name1 = name[0][35..-1]
    # Get just the name (the first two words)
    name2 = name1.split[0..1].join(' ')
    # Replace the HTML escape for ' with '
    name2.sub('&#39;',"'")
  end

  # Given a lodestone profile page, get the realm the player is on
  def get_realm(page)
    # Get the line that contains the player's realm
    realm = page.grep(/<span> \(/)
    # Get just the realm in brackets
    realm1 = realm[0][/\(.*?\)/]
    # Remove the brackets
    realm2 = realm1[1..-2]
  end

  # Given a lodestone profile page, return the race of the player
  def get_race(page)
    # Get the line that contains the player's race
    race = page.grep(/chara_profile_title/)
    # Make the Race the beginning of the line
    race1 = race[0][36..-1]
    # Get just the race (the first word)
    race2 = race1.split[0]
    # Un-escape the ' in Miqo'te
    race2.sub('&#39;',"'")
  end

  # Given a lodestone profile page, return the gender of the player
  def get_gender(page)
    # Get the line that contains the player's race
    gender = page.grep(/chara_profile_title/)

    # Get the last word (which contains the gender)
    gender1 = gender[0].split[-1]

    if gender1[0] == "♂"
      gender = "male"
    elsif gender1[0] == "♀"
      gender = "female"
    else
      gender = nil
    end
    return gender
  end

  # Given a lodestone profile page, return the grand company the player
  # belongs to (if none, returns nil)
  def get_grand_company(page)
    gc = nil
    lines = page.grep(/txt_name/)
    # 4 things in grep txt_name if not in a grand company
    # 5 things in grep txt_name if in a grand company
    if lines.length != 5
      return gc
    else
      # Make name of grand company the beginning of the line
      line = lines[3][25..-1]
      # Get the name on it's own
      line = line.split('/')
      gc = line[0]
    end
  end

  # Given a lodestone profile page, return all skill levels of the player
  # as an array
  def get_levels(page)
    levels = Array.new
    class_id = 0
    line_no = 1
    page.each do |line|
      if line.include? "ic_class_wh24_box"
        levels[class_id] = page[line_no] # This line contains a class level
        class_id += 1
      end
      line_no += 1
    end

    # Get rid of junk items
    levels.delete_at(0)
    levels.delete_at(0)
    levels.delete_at(9)
    levels.delete_at(12)

    # Get just the numbers
    levels.each_with_index { |val, index| levels[index] = val[5..6] }

    # If the number is a single digit, or non-existant, strip the extra character(s)
    levels.each_with_index do |val,index|
      levels[index] = val.gsub(/\D/, "")
    end

    levels
  end

  # Given a player object, writes the player's details to the database
  def write_to_db(player)
    @db.execute("INSERT OR IGNORE INTO 'players' (id, name, realm, race, gender, grand_company, level_gladiator, level_pugilist, level_marauder
      , level_lancer, level_archer, level_rogue, level_conjurer, level_thaumaturge, level_arcanist, level_astrologian, level_darkknight, level_machinist, level_carpenter
      , level_blacksmith, level_armorer, level_goldsmith, level_leatherworker, level_weaver, level_alchemist
      , level_culinarian, level_miner, level_botanist, level_fisher) values ('#{player.id}',\"#{player.player_name}\"
      ,'#{player.realm}',\"#{player.race}\",'#{player.gender}','#{player.grand_company}','#{player.level_gladiator}','#{player.level_pugilist}'
      ,'#{player.level_marauder}','#{player.level_lancer}','#{player.level_archer}','#{player.level_rogue}','#{player.level_conjurer}'
      ,'#{player.level_thaumaturge}','#{player.level_arcanist}','#{player.level_darkknight}','#{player.level_machinist}','#{player.level_astrologian}'
      ,'#{player.level_carpenter}','#{player.level_blacksmith}','#{player.level_armorer}','#{player.level_goldsmith}','#{player.level_leatherworker}'
      ,'#{player.level_weaver}','#{player.level_alchemist}','#{player.level_culinarian}','#{player.level_miner}','#{player.level_botanist}','#{player.level_fisher}');")
  end

  # Main function. Creates the database, cycles through character profiles and 
  # records the information
  def main
    @db = SQLite3::Database.new(@db_path)
    # Allows the program to wait up to 10 seconds for writing
    # So that the database can be read while this program is executing
    @db.busy_timeout=10000
    @db.execute("CREATE TABLE IF NOT EXISTS 'players' (id INTEGER PRIMARY KEY,name TEXT,realm TEXT,race TEXT,gender TEXT,grand_company TEXT
      ,level_gladiator INTEGER,level_pugilist INTEGER,level_marauder INTEGER,level_lancer INTEGER,level_archer INTEGER
      ,level_rogue INTEGER,level_conjurer INTEGER,level_thaumaturge INTEGER,level_arcanist INTEGER,level_darkknight INTEGER, level_machinist INTEGER
      ,level_astrologian INTEGER,level_carpenter INTEGER,level_blacksmith INTEGER,level_armorer INTEGER,level_goldsmith INTEGER
      ,level_leatherworker INTEGER,level_weaver INTEGER,level_alchemist INTEGER,level_culinarian INTEGER,level_miner INTEGER
      ,level_botanist INTEGER,level_fisher INTEGER);")    

    # Do the player IDs in the range specified at the command-line
    for i in @lowest_id..@highest_id
      if page = get_player_page(i)
        player = Player.new
        player.id = i
        player.player_name = get_name(page)
        player.realm = get_realm(page)
        player.race = get_race(page)
        player.gender = get_gender(page)
        player.grand_company = get_grand_company(page) 
        levels = get_levels(page)

        player.level_gladiator = levels[0]
        player.level_pugilist = levels[1]
        player.level_marauder = levels[2]
        player.level_lancer = levels[3]
        player.level_archer = levels[4]
        player.level_rogue = levels[5]
        player.level_conjurer = levels[6]
        player.level_thaumaturge = levels[7]
        player.level_arcanist = levels[8]
        player.level_darkknight = levels[9]
        player.level_machinist = levels[10]
        player.level_astrologian = levels[11]
        player.level_carpenter = levels[12]
        player.level_blacksmith = levels[13]
        player.level_armorer = levels[14]
        player.level_goldsmith = levels[15]
        player.level_leatherworker = levels[16]
        player.level_weaver = levels[17]
        player.level_alchemist = levels[18]
        player.level_culinarian = levels[19]
        player.level_miner = levels[20]
        player.level_botanist = levels[21]
        player.level_fisher = levels[22]

        puts "ID: #{i} | Name: #{player.player_name} | Realm: #{player.realm} | Race: #{player.race} | Gender: #{player.gender} | GC: #{player.grand_company}"
#	puts "GLA: #{player.level_gladiator} | PUG: #{player.level_pugilist} | MAR: #{player.level_marauder} | LNC: #{player.level_lancer} | ARC: #{player.level_archer}"
#	puts "ROG: #{player.level_rogue} | CNJ: #{player.level_conjurer} | THM: #{player.level_thaumaturge} | ARC: #{player.level_arcanist}"
#	puts "AST: #{player.level_astrologian} | DRK: #{player.level_darkknight} | MCN: #{player.level_machinist} | CRP: #{player.level_carpenter}"
#	puts "BSM: #{player.level_blacksmith} | ARM: #{player.level_armorer} | GSM: #{player.level_goldsmith} | LTW: #{player.level_leatherworker}"
#	puts "WVR: #{player.level_weaver} | ALC: #{player.level_alchemist} | CUL: #{player.level_culinarian} | MIN: #{player.level_miner}"
#	puts "BTN: #{player.level_botanist} | FSH: #{player.level_fisher}"
	write_to_db(player)

        # DEBUG
        # page.each { |x| puts x }
      end
    end
  end

end

stats = XIVStats.new
stats.main
