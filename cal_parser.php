<?php

/*

COPYRIGHT AND LICENSING NOTICE

Copyright 2012 Ron Pringle. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are
permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice, this list of
      conditions and the following disclaimer.

   2. Redistributions in binary form must reproduce the above copyright notice, this list
      of conditions and the following disclaimer in the documentation and/or other materials
      provided with the distribution.

THIS SOFTWARE IS PROVIDED BY Ron Pringle ''AS IS'' AND ANY EXPRESS OR IMPLIED
WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


****************************************************
IMPORTANT! SEE THE README DOC FOR SETUP INSTRUCTIONS
****************************************************


*/

// Set the default timezone
date_default_timezone_set('America/Chicago');

// Check for valid dateID
$dateID = $_GET['dateID'];

$filename = "COA_Event_$dateID.vcs";
header("Content-Type: text/x-vCalendar");
header("Content-Disposition: inline; filename=$filename");

/** Put mysql connection and query statements here **/

// DB Connection
		
// Tell it which DB to use
		
// Calendar of Events query

$result = mysql_query($query, $coacalendar);
		
$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
// Replace ending p tags with Hex'd CRLF
$DescDump = str_replace("</p>", "", $row['summary']);
// Remove starting p tags
$DescDump = str_replace("<p>", "", $DescDump);

// Convert dates
$vCalStart = date("Ymd", strtotime($row['eventDate']));
$vCalStart .= date("\THi00", strtotime($row['eventTimeStart']));

if ($row['eventTimeEnd'] <> "") {
	$vCalEnd = date("Ymd", strtotime($row['eventDate']));
	$vCalEnd .= date("\THi00", strtotime($row['eventTimeEnd']));
	}
?>
BEGIN:VCALENDAR
VERSION:1.0
PRODID:COA Web Calendar
TZ:-06
BEGIN:VEVENT
SUMMARY:City of Aurora - <?php echo $row['title'] . "\n"; ?>
DESCRIPTION;ENCODING=QUOTED-PRINTABLE: <?php echo $DescDump . "\n"; ?>
DTSTART:<?php echo $vCalStart . "\n"; ?>
<?php if ($row['eventTimeEnd'] <> "") { ?>
DTEND:<?php echo $vCalEnd . "\n"; ?>
<?php } ?>
LOCATION:<?php echo $row['location'] . "\n"; ?>
URL:http://www.aurora-il.org/detail.php?dateID=<?php echo $dateID . "\n"; ?>
END:VEVENT
END:VCALENDAR