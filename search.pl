#!/usr/bin/perl
#Alam 150084
#search.pl 
# Standard header stuff
use strict;
use CGI qw( :standard );
use CGI::Carp qw(warningsToBrowser fatalsToBrowser);
use DBI;
use DBD::mysql;

#type is XML
print("Content-type: text/plain; charset=UTF-8\n\n");
#connect to DB
my $databaseHandle = DBI->connect( "dbi:mysql:database=m150084;host=zee.academy.usna.edu:3306;user=m150084;password=m150084");

#get value from URL

my $activityName = param("q");


#construct and prepare query with the value
my $query = "SELECT ActivityName FROM m150084.activities WHERE ActivityName LIKE '$activityName%'";
my $queryHandle = $databaseHandle->prepare($query);
my $var = $queryHandle->execute();

while (my @row = $queryHandle->fetchrow_array) {
    print "$row[0]\n";
  
}

#disconnect and finish
$databaseHandle->disconnect();
$queryHandle->finish();