<?php
require __DIR__ . '/../../vendor/autoload.php';

use Carbon\Carbon;

class CalendarEvents
{
    public string $calendar_url = 'https://calendar.google.com/calendar/ical/l2l520ikti6f56tql9hfccthl0%40group.calendar.google.com/public/basic.ics';
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
        if (function_exists('get_stylesheet_directory')) {
            $json = file_get_contents(get_stylesheet_directory() . '/config.json');
            $config = json_decode($json, 0);

            if (isset($config->calendar_url)) {
                $this->calendar_url = $config->calendar_url;
            }
        }

        $this->ical = $this->setupICal($this->calendar_url);
        $this->ical = $this->eventsFromRange(time() - 24 * 3600, time() + 180 * 24 * 3600);

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
                'group' => $group,
                'img_src' => get_stylesheet_directory_uri() . '/public/media/calendar/' . $group
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

    public function group(string $summary, string $description): string
    {
        if (function_exists('get_stylesheet_directory')) {
            $json = file_get_contents(get_stylesheet_directory() . '/resources/functions/calendar-config.json');
            $config = json_decode($json, 0);

            $haystack = $summary . $description;

            foreach ($config as $file => $terms) {
                foreach ($terms as $term) {
                    if (stristr($haystack, $term) !== false) {
                        return $file;
                    }
                }
            }
        }

        return 'default.png';
    }

    public function setupICal(string $filename): bool|array
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
        $events = [];
        $removal_events = array_column($this->cal['VEVENT'], 'RECURRENCE-ID;TZID=Europe/Zurich');

        foreach ($this->cal['VEVENT'] as $vevent) {
            if (array_key_exists('RRULE', $vevent)) {
                $new_events = $this->generateRepeatingEvent($vevent, $removal_events);
                $events = array_merge($events, $new_events);
            } else {
                $events[] = $vevent;
            }
        }


        return $events;
    }

    /**
     * Erstellt aus einer Sequenz mehrere Einträge
     * @param array $event
     * @param array $removal_events
     * @return array $events
     */
    public function generateRepeatingEvent(array $event, array $removal_events)
    {
        $events = [];
        $rules = [];
        $rules_expoded = explode(';', $event['RRULE']);
        foreach ($rules_expoded as $rule) {
            $r = explode('=', $rule);
            $rules[$r[0]] = $r[1];
        }

        $start = Carbon::parse($event['DTSTART']);
        $end = Carbon::parse($event['DTEND']);
        $duration = $start->diffInMinutes($end);

        $period = null;

        $repeat_end = Carbon::now()->addYear();
        if(array_key_exists('UNTIL', $rules)) {
            $repeat_end = Carbon::parse($rules['UNTIL']);
        }
        switch ($rules['FREQ']) {
            case "WEEKLY":
                $period = $start->toPeriod($repeat_end, 7, 'days');
                break;
        }

        foreach ($period as $day) {
            $start = $day->format('Ymd') . 'T' . $day->format('His');
            if ((!array_key_exists('EXDATE;TZID=Europe/Zurich', $event)
                || $start !== $event['EXDATE;TZID=Europe/Zurich'])
                && !in_array($start, $removal_events)
            ) {
                $add_event = $event;
                $add_event['DTSTART'] = $start;
                $day->addMinutes($duration);
                $add_event['DTEND'] = $day->format('Ymd') . 'T' . $day->format('His');
                unset($add_event['RRULE']);
                $events[] = $add_event;
            }
        }

        return $events;
    }

    /**
     * Returns a boolean value whether thr current calendar has events or not
     *
     * @return bool {boolean}
     */
    public
    function hasEvents(): bool
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
    public
    function eventsFromRange($rangeStart = false, $rangeEnd = false): bool|array
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
    public
    function sortEventsWithOrder($events, int $sortOrder = SORT_DESC): array
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

    public
    function get_timezone_offset($remote_tz, $origin_tz = null): bool|int
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