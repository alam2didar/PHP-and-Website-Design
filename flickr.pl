#!/usr/bin/perl
use CGI ":standard";
use strict;
use Digest::MD5 qw(md5 md5_hex);
# Need to have "!head" to avoid loading the head function from LWP::Simple.
# - The above CGI module also has a head function...
use LWP::Simple "!head";
use LWP::UserAgent;
use HTTP::Request;
use HTTP::Response;
use DBI;
use DBD::mysql;
# Do we need to include this??
#use IO::Socket::SSL qw(debug3);
#type is XML
#connect to DB
my $databaseHandle = DBI->connect( "dbi:mysql:database=m150084;host=zee.academy.usna.edu:3306;user=m150084;password=m150084");

my $user = param("user");
my $hashpwd = md5_hex(param("pwd"));
my $tags = param("tags");

#construct and prepare query with the value
my $query = "SELECT UserName, hash from mypasswords where UserName = ? and hash = ? ";
my $queryHandle = $databaseHandle->prepare($query);
#execute
my $var = $queryHandle->execute($user,$hashpwd) or return 0;

#if login invalid
if($var eq '0E0'){

   print "Content-type: text/plain\n\n";
   print "Login Error.\n Access denied!\n";   
   
}
#if the login is valid 
else { 

 # We want to send XML back
 print "Content-type: text/xml\n\n";

 # Construct URL to get the weather
 my $URL = "https://api.flickr.com/services/rest/?method=flickr.photos.search&name=value&per_page=5&tags=$tags&api_key=05b91cc08c5a46f3c8399a2e7429cf71";

#$URL="https://www.google.com";

#my $agent = LWP::UserAgent->new;

#$agent->ssl_opts(verify_hostname => 0);

#print "$URL\n";

# Get the XML document and send it back to requestor (the browser)
 my $contents = get($URL);
 print $contents;
}

#disconnect and finish
$databaseHandle->disconnect();
$queryHandle->finish();
