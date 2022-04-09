<?php

class CalendarEvents
{
    public string $calendar_url = 'https://calendar.google.com/calendar/ical/livl4dcs89n80ibouh01kk68i0%40group.calendar.google.com/public/basic.ics';
    public $ical;

    /* How many ToDos are in this ical? */
    public int $todo_count = 0;
    /* How many events are in this ical? */
    public int $event_count = 0;
    /* The parsed calendar */
    public array $cal;
    /* Which keyword has been added to cal at last? */
    private string $last_keyword;

    public function __construct()
    {
        $this->ical = $this->setupICal($this->calendar_url);
        $this->ical = $this->eventsFromRange(time() - 24*3600, time() + 180*24*3600);
    }

    public function all_calendar_events(): array
    {
        $events_ical = [$this->ical][0];
        $events = [];

        foreach ($events_ical as $event) {
            $summary = $event['SUMMARY'];
            $description = $event['DESCRIPTION'];
            $location = stripslashes($event['LOCATION']);
            $location = str_replace(", Schweiz", "", $location);

            $start = $this->iCalDateToUnixTimestamp($event['DTSTART']);
            $end = $this->iCalDateToUnixTimestamp($event['DTEND']);
            $group = $this->group($summary, $description);

            $events[] = [
                'start_ts' => $start,
                'date' => date('d.m.y H:i', $start),
                'end_ts' => $end,
                'title' => $summary,
                'location' => $location,
                'description' => $description,
                'event' => $this->event($summary, $description),
                'group' => $group,
                'img_src' => get_template_directory_uri() . '/public/media/calendar/' . $group . '.png'
            ];
        }

        return $events;
    }

    public function calendar_events(): array
    {
        $all = $this->all_calendar_events();
        $relevant = [];
        $count = 0;

        $timestamp = time();

        foreach ($all as $event) {
            if ($event['end_ts'] > $timestamp) {
                $relevant[] = $event;
                $count++;

                if ($count === 2)
                    break;
            }
        }
        return $relevant;
    }

    public function event(string $summary, string $description): string
    {
        $haystack = $summary . $description;
        return match (true) {
            stristr($haystack, 'Training') !== false => 'training',
            stristr($haystack, 'Spiel') !== false, stristr($haystack, 'Runde') !== false, stristr($haystack, 'Match') !== false => 'game',
            default => 'default',
        };
    }

    public function group(string $summary, string $description): string
    {
        $haystack = $summary . $description;
        return match (true) {
            stristr($haystack, 'Korbball') !== false => 'basket',
            default => 'default',
        };
    }

    public function setupICal(string $filename)
    {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (stristr($lines[0], 'BEGIN:VCALENDAR') === false) {
            return false;
        } else {
            // TODO: Fix multiline-description problem (see http://tools.ietf.org/html/rfc2445#section-4.8.1.5)
            foreach ($lines as $line) {
                $line = trim($line);
                $add = $this->keyValueFromString($line);
                if ($add === false) {
                    $this->addCalendarComponentWithKeyAndValue($type, false, $line);
                    continue;
                }
                list($keyword, $value) = $add;
                switch ($line) {
                    // http://www.kanzaki.com/docs/ical/vtodo.html
                    case "BEGIN:VTODO":
                        $this->todo_count++;
                        $type = "VTODO";
                        break;
                    // http://www.kanzaki.com/docs/ical/vevent.html
                    case "BEGIN:VEVENT":
                        //echo "vevent gematcht";
                        $this->event_count++;
                        $type = "VEVENT";
                        break;
                    //all other special strings
                    case "BEGIN:VCALENDAR":
                    case "BEGIN:DAYLIGHT":
                        // http://www.kanzaki.com/docs/ical/vtimezone.html
                    case "BEGIN:VTIMEZONE":
                    case "BEGIN:STANDARD":
                        $type = $value;
                        break;
                    case "END:VTODO": // end special text - goto VCALENDAR key
                    case "END:VEVENT":
                    case "END:VCALENDAR":
                    case "END:DAYLIGHT":
                    case "END:VTIMEZONE":
                    case "END:STANDARD":
                        $type = "VCALENDAR";
                        break;
                    default:
                        $this->addCalendarComponentWithKeyAndValue($type,
                            $keyword,
                            $value);
                        break;
                }
            }
            return $this->cal;
        }
    }

    /**
     * Add to $this->ical array one value and key.
     *
     * @param {string} $component This could be VTODO, VEVENT, VCALENDAR, ...
     * @param {string} $keyword   The keyword, for example DTSTART
     * @param {string} $value     The value, for example 20110105T090000Z
     *
     * @return {None}
     */
    public function addCalendarComponentWithKeyAndValue($component, $keyword, $value)
    {
        if ($keyword == false) {
            $keyword = $this->last_keyword;
            switch ($component) {
                case 'VEVENT':
                    $value = $this->cal[$component][$this->event_count - 1]
                        [$keyword] . $value;
                    break;
                case 'VTODO' :
                    $value = $this->cal[$component][$this->todo_count - 1]
                        [$keyword] . $value;
                    break;
            }
        }

        if (stristr($keyword, "DTSTART") or stristr($keyword, "DTEND")) {
            $keyword = explode(";", $keyword);
            $keyword = $keyword[0];
        }
        switch ($component) {
            case "VTODO":
                $this->cal[$component][$this->todo_count - 1][$keyword] = $value;
                //$this->cal[$component][$this->todo_count]['Unix'] = $unixtime;
                break;
            case "VEVENT":
                $this->cal[$component][$this->event_count - 1][$keyword] = $value;
                break;
            default:
                $this->cal[$component][$keyword] = $value;
                break;
        }
        $this->last_keyword = $keyword;
    }

    /**
     * Get a key-value pair of a string.
     *
     * @param {string} $text which is like "VCALENDAR:Begin" or "LOCATION:"
     *
     * @return {array} array("VCALENDAR", "Begin")
     */
    public function keyValueFromString($text)
    {
        preg_match("/([^:]+)[:]([\w\W]*)/", $text, $matches);
        if (count($matches) == 0) {
            return false;
        }
        return array_splice($matches, 1, 2);
    }

    /**
     * Return Unix timestamp from ical date time format
     *
     * @param {string} $icalDate A Date in the format YYYYMMDD[T]HHMMSS[Z] or
     *                           YYYYMMDD[T]HHMMSS
     *
     * @return false|int {int}
     */
    public function iCalDateToUnixTimestamp($icalDate): bool|int
    {
        //Z = UTC Time
        $shift = 0;
        if (str_contains($icalDate, 'Z')) {
            $shift = $this->get_timezone_offset('UTC', 'Europe/Berlin');

        }

        $icalDate = str_replace('T', '', $icalDate);
        $icalDate = str_replace('Z', '', $icalDate);
        $pattern = '/([0-9]{4})';   // 1: YYYY
        $pattern .= '([0-9]{2})';    // 2: MM
        $pattern .= '([0-9]{2})';    // 3: DD
        $pattern .= '([0-9]{0,2})';  // 4: HH
        $pattern .= '([0-9]{0,2})';  // 5: MM
        $pattern .= '([0-9]{0,2})/'; // 6: SS
        preg_match($pattern, $icalDate, $date);
        // Unix timestamp can't represent dates before 1970
        if ($date[1] <= 1970) {
            return false;
        }
        // Unix timestamps after 03:14:07 UTC 2038-01-19 might cause an overflow
        // if 32 bit integers are used.
        $timestamp = mktime((int)$date[4],
            (int)$date[5],
            (int)$date[6],
            (int)$date[2],
            (int)$date[3],
            (int)$date[1]);
        return $timestamp + $shift;
    }

    /**
     * Returns an array of arrays with all events. Every event is an associative
     * array and each property is an element it.
     *
     * @return mixed {array}
     */
    public function events(): mixed
    {
        $array = $this->cal;
        return $array['VEVENT'];
    }

    /**
     * Returns a boolean value whether thr current calendar has events or not
     *
     * @return bool {boolean}
     */
    public function hasEvents(): bool
    {
        return count($this->events()) > 0;
    }

    /**
     * Returns false when the current calendar has no events in range, else the
     * events.
     *
     * Note that this function makes use of a UNIX timestamp. This might be a
     * problem on January the 29th, 2038.
     * See http://en.wikipedia.org/wiki/Unix_time#Representing_the_number
     *
     * @param bool $rangeStart
     * @param bool $rangeEnd
     * @return bool|array {mixed}
     * @throws Exception
     */
    public function eventsFromRange($rangeStart = false, $rangeEnd = false): bool|array
    {
        $events = $this->sortEventsWithOrder($this->events(), SORT_ASC);
        if (!$events) {
            return false;
        }
        $extendedEvents = array();

        if ($rangeStart !== false) {
            $rangeStart = new DateTime();
        }
        if ($rangeEnd !== false or $rangeEnd <= 0) {
            $rangeEnd = new DateTime('2038/01/18');
        } else {
            $rangeEnd = new DateTime($rangeEnd);
        }
        $rangeStart = $rangeStart->format('U');
        $rangeEnd = $rangeEnd->format('U');

        // loop through all events by adding two new elements
        foreach ($events as $anEvent) {
            $timestamp = $this->iCalDateToUnixTimestamp($anEvent['DTSTART']);
            if ($timestamp >= $rangeStart && $timestamp <= $rangeEnd) {
                $extendedEvents[] = $anEvent;
            }
        }
        return $extendedEvents;
    }

    /**
     * Returns a boolean value whether thr current calendar has events or not
     *
     * @param {array} $events    An array with events.
     * @param int $sortOrder
     * @return array {boolean}
     */
    public function sortEventsWithOrder($events, int $sortOrder = SORT_DESC): array
    {
        $extendedEvents = array();

        // loop through all events by adding two new elements
        foreach ($events as $anEvent) {
            if (!array_key_exists('UNIX_TIMESTAMP', $anEvent)) {
                $anEvent['UNIX_TIMESTAMP'] =
                    $this->iCalDateToUnixTimestamp($anEvent['DTSTART']);
            }
            if (!array_key_exists('REAL_DATETIME', $anEvent)) {
                $anEvent['REAL_DATETIME'] =
                    date("d.m.Y", $anEvent['UNIX_TIMESTAMP']);
            }

            $extendedEvents[] = $anEvent;
        }

        foreach ($extendedEvents as $key => $value) {
            $timestamp[$key] = $value['UNIX_TIMESTAMP'];
        }
        array_multisort($timestamp, $sortOrder, $extendedEvents);
        return $extendedEvents;
    }

    public function get_timezone_offset($remote_tz, $origin_tz = null): bool|int
    {
        if ($origin_tz === null) {
            if (!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }
}

/*

$timestamp = date_timestamp_get(date_create());
$trainings = [];
$spiele = [];
$anlaesse = [];

foreach ($_SESSION['events'] as $event) {
    $tsp_end = $_SESSION['ical']->iCalDateToUnixTimestamp($event['DTEND']);
    if ($event != null && $tsp_end > $timestamp) {
        if ((str_contains($event['SUMMARY'], 'Training')) || (str_contains($event['DESCRIPTION'], 'Training'))) {
            array_push($trainings, $event);
        } else if ((str_contains($event['SUMMARY'], 'Spiel')) || (str_contains($event['DESCRIPTION'], 'Spiel')) || (str_contains($event['SUMMARY'], 'Runde')) || (strpos($event['SUMMARY'], 'Match') !== false) || (strpos($event['DESCRIPTION'], 'Runde') !== false) || (strpos($event['DESCRIPTION'], 'Match') !== false) || (strpos($event['DESCRIPTION'], 'Auswärtsspiel') !== false) || (strpos($event['SUMMARY'], 'Auswärtsspiel') !== false)) {
            array_push($spiele, $event);
        } else {
            array_push($anlaesse, $event);
        }
    }
}

echo('
	<div class="sidebar-box">
	<h2>Nächste Trainings</h2>
	<table style="width: 100%">
        <colgroup>
            <col style="width: 25%">
            <col style="width: 75%">
        </colgroup>');

$count = 0;
foreach ($trainings as $training) {
    $sport = "undefined";
    if (str_contains($training['SUMMARY'], 'Korbball')) {
        $sport = "Korbball";
    } else if (str_contains($training['SUMMARY'], 'Unihockey')) {
        $sport = "Unihockey";
    } else if (str_contains($training['SUMMARY'], 'Fussball')) {
        $sport = "Fussball";
    }

    $start_zeit = $_SESSION['ical']->iCalDateToUnixTimestamp($training['DTSTART']);
    $end_zeit = $_SESSION['ical']->iCalDateToUnixTimestamp($training['DTEND']);

    if ($training['LOCATION'] != null) {
        $location = stripslashes($training['LOCATION']);
        $location = str_replace(", Schweiz", "", $location);
    } else {
        if (strftime("%A", $start_zeit) == "Mittwoch") {
            $location = "Rickenhalle";
        } else if (strftime("%A", $start_zeit) == "Freitag") {
            $location = "Schulhaus";
        } else {
            $location = "";
        }
    }
    echo('<tr>
                <td>');
    if ($sport == 'undefined') {
        echo($training['SUMMARY']);
    } else {
        echo('<img src="' . get_stylesheet_directory_uri() . '/bilder/sport/' . $sport . '.png" class="symbol">');
    }
    echo('</td>
                <td>' . strftime("%A", $start_zeit) . ', ' . date("d.m.", $start_zeit) . '<br>' . date('H:i', $start_zeit) . '<br>' . $location . '</td>
            </tr>');
    $count++;
    if ($count > 2) {
        break;
    }
}
if ($count == 0) {
    echo('<tr><td></td><td>Momentan keine</td></tr>');
}
echo('</table></div>');

$count = 0;
echo('<div class="sidebar-box"><h2>Nächstes Spiel</h2>
	<table style="width: 100%">
        <colgroup>
            <col style="width: 25%">
            <col style="width: 75%">
        </colgroup>');
foreach ($spiele as $spiel) {
    $sport = "undefined";
    if (strpos($spiel['SUMMARY'], 'Korbball') !== false || strpos($spiel['SUMMARY'], 'ISM') !== false || strpos($spiel['DESCRIPTION'], 'Korbball') !== false) {
        $sport = "Korbball";
    } else if (strpos($spiel['SUMMARY'], 'Unihockey') !== false || strpos($spiel['DESCRIPTION'], 'Unihockey') !== false) {
        $sport = "Unihockey";
    } else if (strpos($spiel['SUMMARY'], 'Fussball') !== false || strpos($spiel['DESCRIPTION'], 'Fussball') !== false) {
        $sport = "Fussball";
    }

    $start_zeit = $_SESSION['ical']->iCalDateToUnixTimestamp($spiel['DTSTART']);
    $end_zeit = $_SESSION['ical']->iCalDateToUnixTimestamp($spiel['DTEND']);
    if ($sport !== "undefined") {
        $file = get_stylesheet_directory_uri() . '/bilder/sport/' . $sport . '.png';
    } else {
        $file = get_stylesheet_directory_uri() . '/bilder/sport/anlass.png';
    }

    $location = stripslashes($spiel['LOCATION']);
    $location = str_replace(", Schweiz", "", $location);
    echo('<tr>
                <td><img src="' . $file . '" class="symbol"></td>
                <td>' . strftime("%A", $start_zeit) . ', ' . date("d.m.", $start_zeit) . '<br>');
    if (date('H:i', $start_zeit) !== "00:00") {
        echo(date('H:i', $start_zeit) . ', ');
    }
    echo($spiel['SUMMARY'] . '<br>' . $location . '</td>
            </tr>');
    $count++;
    break;
}
if ($count == 0) {
    echo('<tr><td></td><td>Momentan keine</td></tr>');
}
echo('</table></div>');

$count = 0;
echo('<div class="sidebar-box"><h2>Nächster Anlass</h2>
	<table style="width: 100%">
        <colgroup>
            <col style="width: 25%">
            <col style="width: 75%">
        </colgroup>');
foreach ($anlaesse as $anlass) {

    echo('<tr>
                <td><img src="' . get_stylesheet_directory_uri() . '/bilder/sport/anlass.png" class="symbol"></td>
                <td>' . strftime("%A", $start_zeit) . ', ' . date("d.m.", $start_zeit) . '<br>' . $anlass['SUMMARY'] . '<br>' . $anlass['LOCATION'] . '</td>
            </tr>');
    $count++;
    break;
}
if ($count == 0) {
    echo('<tr><td></td><td>Momentan keine</td></tr>');
}
echo('</table></div>');
*/