<?php

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