#!/usr/bin/perl
#Alam 150084
#proxy.pl (adds data from the form)
# Standard header stuff
use strict;
use CGI ":standard";
use LWP::Simple "!head";
use LWP::UserAgent;
use HTTP::Request;
use HTTP::Response;


print("Content-type: text/xml\n\n");

#get values
my $year = param("year");
my $month = param("month");
my $day = param("day");

#Construct URL to get the schedule
my $url = "https://api.sportsdatallc.org/mlb-t5/games/$year/$month/$day/schedule.xml?api_key=czemj2bgg2yj8j7p2qp46bd7";

#Get the XML document and send it back to the client
my $contents = get($url);
print $contents;