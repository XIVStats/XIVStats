#!/usr/bin/env ruby

class SQLiteToMySQL

  def initialize
    require 'sqlite3'
    require 'mysql'

    # Change this to the path of your SQLite database
    @database = "players_old.db"

    # Change this to your MySQL username
    @username = "ffxiv"

    # Change this to your MySQL password
    @password = "qaXFBdmJHkXjnQbD"

    # Change this to your MySQL host
    @host = "localhost"

    # Change this to your MySQL database name
    @mysqldb = "ffxiv"

    if ARGV.empty?
      puts "Usage: sqlite_to_mysql.rb <lowest_id> <highest_id>"
      exit 1
    end
    @lowest_id = ARGV[0].to_i
    @highest_id = ARGV[1].to_i
    if @highest_id < @lowest_id
      puts "Error: The second argument needs to be greater than the first argument"
    end
  end

  def migrate_data

    begin
      @db = SQLite3::Database.new( @database )

      con = Mysql.new @host,@username,@password,@mysqldb

      con.query("CREATE TABLE IF NOT EXISTS players (id INT NOT NULL,name VARCHAR(255),realm VARCHAR(255),race VARCHAR(255)
        ,gender VARCHAR(255),grand_company VARCHAR(255),level_gladiator INT,level_pugilist INT,level_marauder INT
        ,level_lancer INT,level_archer INT,level_rogue INT,level_conjurer INT,level_thaumaturge INT,level_arcanist INT
        ,level_carpenter INT,level_blacksmith INT,level_armorer INT,level_goldsmith INT,level_leatherworker INT,level_weaver INT
        ,level_alchemist INT,level_culinarian INT,level_miner INT,level_botanist INT,level_fisher INT,PRIMARY KEY (id));")

      for i in @lowest_id..@highest_id
        query = @db.prepare "SELECT * FROM players WHERE id = ?"
        query.bind_param 1, i
        result = query.execute
        row = result.next
        unless row.nil?
          row.each_with_index do |val,index| 
             if val == ''
               row[index] = 'NULL'
             end
          end
          id = row[0]
          name = row[1]
          realm = row[2]
          race = row[3]
          gender = row[4]
          grand_company = row[5]
          level_gladiator = row[6]
          level_pugilist = row[7]
          level_marauder = row[8]
          level_lancer = row[9]
          level_archer = row[10]
          level_rogue = row[11]
          level_conjurer = row[12]
          level_thaumaturge = row[13]
          level_arcanist = row[14]
          level_carpenter = row[15]
          level_blacksmith = row[16]
          level_armorer = row[17]
          level_goldsmith = row[18]
          level_leatherworker = row[19]
          level_weaver = row[20]
          level_alchemist = row[21]
          level_culinarian = row[22]
          level_miner = row[23]
          level_botanist = row[24]
          level_fisher = row[25]

          con.query("REPLACE INTO players (`id`, `name`, `realm`, `race`, `gender`, `grand_company`, `level_gladiator`, `level_pugilist`" +
            ", `level_marauder`, `level_lancer`, `level_archer`, `level_rogue`, `level_conjurer`, `level_thaumaturge`, `level_arcanist`" +
            ", `level_carpenter`, `level_blacksmith`, `level_armorer`, `level_goldsmith`, `level_leatherworker`, `level_weaver`, `level_alchemist`" +
            ", `level_culinarian`, `level_miner`, `level_botanist`, `level_fisher`) VALUES('#{id}', '#{name}', '#{realm}', '#{race}', '#{gender}'" +
            ", '#{grand_company}', '#{level_gladiator}', '#{level_pugilist}'" +
            ", '#{level_marauder}', '#{level_lancer}', '#{level_archer}', '#{level_rogue}', '#{level_conjurer}', '#{level_thaumaturge}', '#{level_arcanist}'" +
            ", '#{level_carpenter}', '#{level_blacksmith}', '#{level_armorer}', '#{level_goldsmith}', '#{level_leatherworker}', '#{level_weaver}'" +
            ", '#{level_alchemist}', '#{level_culinarian}', '#{level_miner}', '#{level_botanist}', '#{level_fisher}');")

          puts "Completed ID: #{id} | Name: #{name}"

        end
     end

    rescue SQLite3::Exception => e
      puts e

    rescue Mysql::Error => e
      puts e.errno
      puts e.error

    ensure
      con.close if con
    end
  end
end

run = SQLiteToMySQL.new
run.migrate_data
