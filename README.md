## XIVStats ##

# What is XIVStats?

XIVStats is actually two things. One part is an application which trawls the lodestone and gathers detail on all of the player characters in FFXIV, and the other is this repository, which takes that lodestone data, and produces graphs and charts of the data.

You can view a live demo of this web page here: [ffxivcensus.com](http://ffxivcensus.com/).

This project is inspired by [xivsoul.com](https://xivsoul.com).

# Configuration #

The path for the database to be used is specified on the line beginning
"db_path" in xiv_stats.rb.

# Usage #

If you have a relatively complete lodestone database, you will find that page execution times are extremely high (>10 minutes). For this reason I recommend you compile the PHP to static HTML if you intend to use it somewhere. To do this, simple run:

    php xiv_stats.php > xiv_stats.html

# Notes #

A complete copy of the database has been compiled and is avaiable
from the following URLs. 

| Release | Live Patch | Live Expansion | Download |
|---------|------------|----------------|----------|
| April 2015 | 2.5 | A Realm Reborn | [Link](https://jonathanprice.org/xiv/players.db)
| July 2015 | 3.0 | Heavensward | [Link](https://jonathanprice.org/xiv/players-20150801.db) 
