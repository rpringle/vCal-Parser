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

SETTING A TIMEZONE
------------------

Since PHP 5.1.0, every call to a date/time function will generate an E_NOTICE if the
timezone isn't valid, and/or a E_WARNING message if using the system settings or the
TZ environment variable.

The timezone identifier variable is optional as it may already be set in the INI file.
vCal Parser checks to see if the default timezone is set in the INI file first. If it isn't,
it defaults to 'America/Denver' for the timezone.

If you need to change the timezone to something other than Mountain Time, for a list of
valid timezones, please see: http://www.php.net/manual/en/timezones.php.

Just edit the line $timezone_identifier = 'America/Denver'; and insert a valid timezone.
Alternately, you can set the default timezone in the INI file.

*/

// Check to see if default timezone is set in INI
$timezone_default = ini_get('date_default_timezone');

if (!isset ($timezone_default))
{
	// Default timezone not set in INI, so we set it here.
	$timezone_identifier = 'America/Denver';
	date_default_timezone_set($timezone_identifier);
}

// User configurable variables. Again, better to pass these
// programmatically from the event itself.

$event_summary		= 'Test'; // Summary (aka Title) of the event
$event_description	= 'Test event.'; // Description of the event
$event_date			= '20121007'; // Year, month, day
$event_time_start	= '083000'; // Hour, minute, second
$event_time_end		= '103000'; // Hour, minute, second
$event_location		= '1101 Arapahoe Drive'; // Physical location of event
$event_url			= 'http://www.bouldercolorado.gov'; // Valid URL for the event
$prodid				= 'City of Boulder'; // Your 'product' identifier, ex. 'City Calendar'

// Create the file name based on event summary
$filename = $event_summary . '.vcs';

// Clean up the description by adding Hex'd CRLFs (carriage return/line feed)
// in place of ending </p> tags and removing starting <p> tags.

$event_description = str_replace("</p>", "=0D=0A=", $event_description);
$event_description = str_replace("<p>", "", $event_description);

// Convert start date/time to proper vcal format
$vcal_start = date("Ymd", strtotime($event_date));
$vcal_start .= date("\THi00", strtotime($event_time_start));

// If there's an end time, convert it as well
if ($event_time_end)
{
	$vcal_end = date("Ymd", strtotime($event_date));
	$vcal_end .= date("\THi00", strtotime($event_time_end));
}

// Write out the header information
header("Content-Type: text/x-vCalendar");
header("Content-Disposition: inline; filename=$filename");

// Output the rest of the vcal information
?>
BEGIN:VCALENDAR
VERSION:1.0
PRODID:<?php echo $prodid . "\n"; ?>
TZ:-06
BEGIN:VEVENT
SUMMARY:<?php echo $event_summary . "\n"; ?>
DESCRIPTION;ENCODING=QUOTED-PRINTABLE: <?php echo $event_description . "\n"; ?>
DTSTART:<?php echo $vcal_start . "\n"; ?>
<?php if ($vcal_end) { ?>
DTEND:<?php echo $vcal_end . "\n"; ?>
<?php } ?>
LOCATION:<?php echo $event_location . "\n"; ?>
URL:<?php echo $event_url . "\n"; ?>
END:VEVENT
END:VCALENDAR