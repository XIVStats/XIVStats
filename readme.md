## XIV Stats ##

XIV Stats is primarily a script to produce a database of player
information for FFXIV. The information is pulled from directly from the
lodestone.

# Configuration #

The path for the database to be used is specified on the line beginning
"db_path" in xiv_stats.rb.

# Usage #

The script will collect information on all players with IDs in the specified
range, as shown below:

    ./xiv_stats.rb <lowest ID> <highest ID>

The player ID can be determined by looking at the URL for the lodestone
profile page of a given player.

# Notes #

The script deliberately only retrieves between 1-2 players per second. This
is to avoid excessive load on the lodestone's servers. Due to the slow
execution, a complete copy of the database has been compiled and is avaiable
from the following URL: [link to database]

A simple example PHP web page has been included. This web page draws data
directly from the database and uses it to draw a few charts. Due to the
large amount of data that has to be compiled to produce the charts,
performance is quite slow. If you were planning to embed data from the
database in a web page, it would be recommended to gather the data seperately
and hard-code it in, and then only update the data if you re-run a scan of the
lodestone.

The complete database provided was compiled using an early version of the
script, which did not properly handle apostrophes. Due to this, players with
apostrophes in there name will instead have the string "&#39;". There is an
included script "apostrophe_cleanup.rb", which works similarly to xiv_stats.rb,
and will go through the range specified and fix all apostrophes. I am currently
investigating a faster way of achieving this.
