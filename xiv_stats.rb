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
    name2.gsub('&#39;',"'")
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
    # If race is Au Ra, it has a space in it, re-add Ra back
    if race2 == 'Au'
      race2 = 'Au Ra'
    end
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

    # checks to see if the optional free company line has been added
    fc = lines.grep(/a href=/)

    # GOOD CONDITIONS
    # 5 lines, guarentees they're in a GC
    # 4 lines if they're NOT in an FC, otherwise the 4th line is FC
    if lines.length < 4 || (lines.length == 4 && ! fc.empty?)
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

  # Given a lodestone profile page, return 1 if user has subscribed for 30 days,
  # else return 0
  def get_minion(page, minion)
    minion_begin = page.index{|s| s.include?("<!-- Minion -->")}
    minion_end = page.index{|s| s.include?("<!-- //Minion -->")}
    
    minion_section = page[minion_begin..minion_end]


    minion_section.each do |line|
      if line.include?(minion)
        return 1
      end
    end

    return 0
  end

  # Given a lodestone profile page, return 1 if user has subscribed for 30 days,
  # else return 0
  def get_mount(page, mount)
    mount_begin = page.index{|s| s.include?("<!-- Mount -->")}
    mount_end = page.index{|s| s.include?("<!-- //Mount -->")}

    mount_section = page[mount_begin..mount_end]


    mount_section.each do |line|
      if line.include?(mount)
        return 1
      end
    end

    return 0
  end


  # Given a player object, writes the player's details to the database
  def write_to_db(player)
    @db.execute("INSERT OR IGNORE INTO 'players' (id, name, realm, race, gender, grand_company, level_gladiator, level_pugilist, level_marauder
      , level_lancer, level_archer, level_rogue, level_conjurer, level_thaumaturge, level_arcanist, level_astrologian, level_darkknight, level_machinist, level_carpenter
      , level_blacksmith, level_armorer, level_goldsmith, level_leatherworker, level_weaver, level_alchemist
      , level_culinarian, level_miner, level_botanist, level_fisher, p30days, p60days, p90days, p180days, p270days, p360days, p450days, p630days
      , prearr, prehw, artbook, beforemeteor, beforethefall, soundtrack, saweternalbond, sightseeing, arr_25_complete, comm50, moogleplush
      , hildibrand, ps4collectors, dideternalbond, arrcollector, kobold, sahagin, amaljaa, sylph) 
      values ('#{player.id}',\"#{player.player_name}\",'#{player.realm}',\"#{player.race}\",'#{player.gender}','#{player.grand_company}'
      ,'#{player.level_gladiator}','#{player.level_pugilist}','#{player.level_marauder}','#{player.level_lancer}','#{player.level_archer}'
      ,'#{player.level_rogue}','#{player.level_conjurer}','#{player.level_thaumaturge}','#{player.level_arcanist}','#{player.level_darkknight}'
      ,'#{player.level_machinist}','#{player.level_astrologian}','#{player.level_carpenter}','#{player.level_blacksmith}','#{player.level_armorer}'
      ,'#{player.level_goldsmith}','#{player.level_leatherworker}','#{player.level_weaver}','#{player.level_alchemist}','#{player.level_culinarian}'
      ,'#{player.level_miner}','#{player.level_botanist}','#{player.level_fisher}','#{player.p30days}','#{player.p60days}','#{player.p90days}','#{player.p180days}'
      ,'#{player.p270days}','#{player.p360days}','#{player.p450days}','#{player.p630days}','#{player.prearr}','#{player.prehw}','#{player.artbook}'
      ,'#{player.beforemeteor}','#{player.beforethefall}','#{player.soundtrack}','#{player.saweternalbond}','#{player.sightseeing}'
      ,'#{player.arr_25_complete}','#{player.comm50}','#{player.moogleplush}','#{player.hildibrand}','#{player.ps4collectors}'
      ,'#{player.dideternalbond}','#{player.arrcollector}','#{player.kobold}','#{player.sahagin}','#{player.amaljaa}','#{player.sylph}');")
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
      ,level_botanist INTEGER,level_fisher INTEGER,p30days INTEGER, p60days INTEGER, p90days INTEGER, p180days INTEGER, p270days INTEGER
      ,p360days INTEGER,p450days INTEGER,p630days INTEGER,prearr INTEGER,prehw INTEGER, artbook INTEGER, beforemeteor INTEGER, beforethefall INTEGER
      ,soundtrack INTEGER,saweternalbond INTEGER,sightseeing INTEGER,arr_25_complete INTEGER,comm50 INTEGER,moogleplush INTEGER
      ,hildibrand INTEGER, ps4collectors INTEGER, dideternalbond INTEGER, arrcollector INTEGER, kobold INTEGER, sahagin INTEGER, amaljaa INTEGER, sylph INTEGER);")    

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
        player.p30days = get_minion(page, "Wind-up Cursor")
        player.p60days = get_minion(page, "Black Chocobo Chick")
        player.p90days = get_minion(page, "Beady Eye")
        player.p180days = get_minion(page, "Minion Of Light")
        player.p270days = get_minion(page, "Wind-up Leader")
        player.p360days = get_minion(page, "Wind-up Odin")
        player.p450days = get_minion(page, "Wind-up Goblin")
        player.p630days = get_minion(page, "Wind-up Nanamo")
        player.prearr = get_minion(page, "Cait Sith Doll")
        player.prehw = get_minion(page, "Chocobo Chick Courier")
        player.artbook = get_minion(page, "Model Enterprise")
        player.beforemeteor = get_minion(page, "Wind-up Dalamud")
        player.beforethefall = get_minion(page, "Set Of Primogs")
        player.soundtrack = get_minion(page, "Wind-up Bahamut")
        player.saweternalbond = get_minion(page, "Demon Box")
        player.sightseeing = get_minion(page, "Fledgling Apkallu")
        player.arr_25_complete = get_minion(page, "Midgardsormr")
        player.comm50 = get_minion(page, "Princely Hatchling")
        player.moogleplush = get_minion(page, "Wind-up Delivery Moogle")
        player.hildibrand = get_minion(page, "Wind-up Gentleman")
        player.ps4collectors = get_minion(page, "Wind-up Moogle")
        player.hw_31_complete = get_minion(page, "")
        player.dideternalbond = get_mount(page, "Ceremony Chocobo")
        player.arrcollector = get_mount(page, "Coeurl")
        player.kobold = get_mount(page, "Bomb Palanquin")
        player.sahagin = get_mount(page, "Cavalry Elbst")
        player.amaljaa = get_mount(page, "Cavalry Drake")
        player.sylph = get_mount(page, "Laurel Goobbue")
        player.hw_complete = get_mount(page, "Migardsormr")
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

#        puts "ID: #{i} | Name: #{player.player_name} | Realm: #{player.realm} | Race: #{player.race} | Gender: #{player.gender} | GC: #{player.grand_company}"
        puts "ID: #{i} | Name: #{player.player_name} | Realm: #{player.realm} | Race: #{player.race} | Gender: #{player.gender} | 30Days: #{player.p30days}"
	write_to_db(player)

        # DEBUG
        # page.each { |x| puts x }
      end
    end
  end

end

stats = XIVStats.new
stats.main
