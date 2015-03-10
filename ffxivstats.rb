#!/usr/bin/env ruby

class FFXIVStats

  def initialize
    require 'open-uri'
    require 'sqlite3'
    require_relative 'Player'
  end

  # Fetches a lodestone profile page, and returns it as an array of strings
  def get_player_page(player_id)
    page = Array.new
    begin
      open("http://eu.finalfantasyxiv.com/lodestone/character/#{player_id}/") do |f|
        f.each_line { |line| page.push line }
      end
    rescue
#      puts "ID: #{player_id} does not exist"
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
    # The ' in Miqo'te is a pain, easier to just remove it
    race2.sub('&#39;','')
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
      gender = "unknown"
    end
    return gender
  end

  def get_levels
  end

  # Given a player object, writes the player's details to the database
  def write_to_db(player)
    @db.execute("insert into 'players' (id, name, realm, race, gender) values ('#{player.id}','#{player.player_name}','#{player.realm}','#{player.race}','#{player.gender}');")
  end

  def main
    @db = SQLite3::Database.new( "players.db" )
    @db.execute("create table if not exists 'players' (id INTEGER,name TEXT,realm TEXT,race TEXT,gender TEXT);")    

    for i in 1..100
      if page = get_player_page(i)
        player_name = get_name(page)
        realm = get_realm(page)
        race = get_race(page)
	gender = get_gender(page)

        player = Player.new
        player.id = i
        player.player_name = player_name
        player.realm = realm
        player.race = race
        player.gender = gender

        puts "ID: #{i} | Name: #{player.player_name} | Realm: #{player.realm} | Race: #{player.race} | Gender: #{player.gender}"
	write_to_db(player)

#         DEBUG
#        page.each { |x| puts x }
      end
    end
  end

end

stats = FFXIVStats.new
stats.main
