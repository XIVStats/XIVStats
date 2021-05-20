## XIVStats ##

# What is XIVStats?

XIVStats is actually two things. One part is an application which trawls the lodestone and gathers detail on all of the player characters in FFXIV, and the other is this repository, which takes that lodestone data, and produces graphs and charts of the data.

You can view a live demo of this web page here: [ffxivcensus.com](http://ffxivcensus.com/).

This project is inspired by **XIV Soul**.

# I want to make my own copy of the Lodestone database, which repository should I use? #

The data used by this PHP is gathered by the XIVStats Java Gatherer, linked below:

- [https://github.com/XIVStats/XIVStats-Gatherer-Java](https://github.com/XIVStats/XIVStats-Gatherer-Java)


# Configuration #


# Usage #

If you have a relatively complete lodestone database, you will find that page execution times are extremely high (>10 minutes). For this reason I recommend you compile the PHP to static HTML if you intend to use it somewhere. To do this, simple run:

    php xiv_stats.php > xiv_stats.html

# Notes #

Complete scans of the Lodestone are completed on a monthly basis and are available for download from [Link](https://ffxivcensus.com)
