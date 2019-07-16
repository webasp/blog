<?php
namespace core;

class Calendar {

	/**
	 * Array of Week Day Names
	 * @var array|false
	 */
	public $wday_names = false;
	public $week = ['星期一','星期二','星期三','星期四','星期五','星期六','星期日'];
	private $now;
	private $dailyHtml = array();
	private $offset = 0;

	/**
	 * Constructor - Calls the setDate function
	 *
	 * @see setDate
	 * @param null|string $date_string
	 */
	public function __construct( $date_string = null ) {
		$this->setDate($date_string);
	}

	/**
	 * Sets the date for the calendar
	 *
	 * @param null|string $date_string Date string parsed by strtotime for the calendar date. If null set to current timestamp.
	 */
	public function setDate( $date_string = null ) {
		if( $date_string ) {
			$this->now = getdate(strtotime($date_string));
		} else {
			$this->now = getdate();
		}
	}

	/**
	 * Add a daily event to the calendar
	 *
	 * @param string      $html The raw HTML to place on the calendar for this event
	 * @param string      $start_date_string Date string for when the event starts
	 * @param null|string $end_date_string Date string for when the event ends. Defaults to start date
	 */
	public function addDailyHtml( $html, $start_date_string, $end_date_string = null ) {
		static $htmlCount = 0;
		$start_date = strtotime($start_date_string);
		if( $end_date_string ) {
			$end_date = strtotime($end_date_string);
		} else {
			$end_date = $start_date;
		}

		$working_date = $start_date;
		do {
			$tDate        = getdate($working_date);
			$working_date += 86400;

			$this->dailyHtml[$tDate['year']][$tDate['mon']][$tDate['mday']][$htmlCount] = $html;
		} while( $working_date < $end_date + 1 );

		$htmlCount++;
	}

	/*
	 * $arr = arr[];
	 *
	 * */
    public function addArrayDailyHtml($arr = []){
        static $htmlCount = 0;
	    if(!is_array($arr)){ return ;}

	    foreach ($arr as $value){

            $start_date = strtotime($value[0]);
            if(isset($value[3])) {
                $end_date = strtotime($value[3]);
            } else {
                $end_date = $start_date;
            }

            $HtmlItem = '';
            $HtmlItem .= '<p class="HtmlItem '.$value[3].'">';
            $HtmlItem .= $value[1];
            $HtmlItem .= '<span class="pull-right">'.$value[2].'</span>';
            $HtmlItem .= '</div>';




            $working_date = $start_date;
            do {
                $tDate        = getdate($working_date);
                $working_date += 86400;

                $this->dailyHtml[$tDate['year']][$tDate['mon']][$tDate['mday']][$htmlCount] = $HtmlItem;
            } while( $working_date < $end_date + 1 );

            $htmlCount++;
        }
    }

	/**
	 * Clear all daily events for the calendar
	 */
	public function clearDailyHtml() { $this->dailyHtml = array(); }

	/**
	 * Sets the first day of the week
	 *
	 * @param int|string $offset Day to start on, ex: "Monday" or 0-6 where 0 is Sunday
	 */
	public function setStartOfWeek( $offset ) {
		if( is_int($offset) ) {
			$this->offset = $offset % 7;
		} else {
			$this->offset = date('N', strtotime($offset)) % 7;
		}
	}

	/**
	 * Returns/Outputs the Calendar
	 *
	 * @param bool $echo Whether to echo resulting calendar
	 * @return string HTML of the Calendar
	 */
	public function show( $echo = true ) {
		if( $this->wday_names ) {
			$wdays = $this->wday_names;
		} else {
			$today = (86400 * (date("N")));
			$wdays = array();
			for( $i = 0; $i < 7; $i++ ) {
				$wdays[] = strftime('%a', time() - $today + ($i * 86400));
			}
		}

		$this->arrayRotate($wdays, $this->offset);
		$wday    = date('N', mktime(0, 0, 1, $this->now['mon'], 1, $this->now['year'])) - $this->offset;
        $no_days    = date('t', mktime(0, 0, 1, $this->now['mon'], 1, $this->now['year'])) - $this->offset;
		$out = '<table cellpadding="0" cellspacing="0" class="table table-bordered Calendar"><thead><tr>';

		for( $i = 0; $i < 7; $i++ ) {
			$out .= '<th>' . $this->week[$i] . '</th>';
		}

		$out .= "</tr></thead>\n<tbody>\n<tr>";

		$wday = ($wday + 6) % 7;
		if( $wday == 7 ) {
			$wday = 0;
		} else {
			$out .= str_repeat('<td class="SCprefix"></td>', $wday);
		}

		$count = $wday + 1;

		for( $i = 1; $i <= $no_days; $i++ ) {
			$out .= '<td' . ($i == $this->now['mday'] && $this->now['mon'] == date('n') && $this->now['year'] == date('Y') ? ' class="today"' : '') . '>';
			$datetime = mktime(0, 0, 1, $this->now['mon'], $i, $this->now['year']);

			$out .= '<time datetime="' . date('Y-m-d', $datetime) . '">' . $i . '</time>';

			$dHtml_arr = false;
			if( isset($this->dailyHtml[$this->now['year']][$this->now['mon']][$i]) ) {
				$dHtml_arr = $this->dailyHtml[$this->now['year']][$this->now['mon']][$i];
			}

			if( is_array($dHtml_arr) ) {

				foreach( $dHtml_arr as $dHtml ) {
					$out .= $dHtml;
				}

			}

			$out .= "</td>";

			if( $count > 6 ) {
				$out .= "</tr>\n" . ($i < $no_days ? '<tr>' : '');
				$count = 0;
			}
			$count++;
		}
		$out .= ( $count != 1 ) ? str_repeat('<td class="SCsuffix"></td>', 8 - $count) . '</tr>' : '';
		$out .= "\n</tbody></table>\n";
		if( $echo ) {
			echo $out;
		}

		return $out;
	}

	/**
	 * @param array $data
	 * @param int $steps
	 */
	private function arrayRotate( array &$data, $steps ) {
		$count = count($data);
		if( $steps < 0 ) {
			$steps = $count + $steps;
		}
		$steps = $steps % $count;
		for( $i = 0; $i < $steps; $i++ ) {
			array_push($data, array_shift($data));
		}
	}
}
