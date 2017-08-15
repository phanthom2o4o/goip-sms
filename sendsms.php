<?php

// This script is mostly the work of paisapimp at stackoverflow. I take no credit other than adding some options and cleaning
// up a few errors. See https://stackoverflow.com/questions/28941059/how-to-send-retrieve-sms-from-goip for the original
// Q&A.

// Errata: Script complained about $fields_string not being initialized, added that variable to the initialization.
// Errata: curl_getinfo was expecting a value to return, chose CURLINFO_HTTP_CODE as this seemed the most useful.

// depends: php5-cgi php5 php5-curl php5-cli and some other things that get installed because of these, assuming you're not
// already running apache2 which (usually) satisfies the cgi depends.

// how to use: /path/to/php /path/to/sendsms.php telnumber "message" > /path/to/output.txt
// returns the html code of the sms page followed by the http return code (200 = ok) at the end.

// set username and password to your box's parameters. default is admin 1234


$rand = rand();
$url = 'http://IP_OF_YOUR_GOIP_BOX/default/en_US/sms_info.html';
$line = '1'; // sim card to use in my case #1
$username = "admin"; //goip username
$password = "1234"; //goip password
$fields_string = ''; // set field to empty value because error...
$telnum = $argv[1]; // first argument is number to send
$smscontent = $argv[2]; // second  argument is message to send


$fields = array(
'line' => urlencode($line),
'smskey' => urlencode($rand),
'action' => urlencode('sms'),
'telnum' => urlencode($telnum),
'smscontent' => urlencode($smscontent),
'send' => urlencode('send')
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_PORT, 80);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
echo curl_exec($ch);
echo curl_getinfo($ch, CURLINFO_HTTP_CODE); // needed a parameter for the getinfo return, chose http code

//close connection
curl_close($ch);
?>
