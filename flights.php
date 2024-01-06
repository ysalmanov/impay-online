<?php

function find_possible_next_flights($flight, $flights) {

	$to = $flight['to'];
	$find_possible_next_flights = array();

	foreach ($flights as $e) {
		
		if ($e['from'] == $to) {
			$find_possible_next_flights[] = $e;
		}

	}

	return $find_possible_next_flights;

}

function find_next_flight($flight, $flights) {

	$possible_next_flights = find_possible_next_flights($flight, $flights);
	$arrival = strtotime($flight['arrival']);

	foreach ($possible_next_flights as $e) {

		$depart = strtotime($e['depart']);
		if ($depart >= $arrival) return $e;

	}

}

$flights = [

    [   'from'    => 'VKO',
        'to'      => 'DME',
        'depart'  => '01.01.2020 12:44',
        'arrival' => '01.01.2020 13:44'
    ],

    [   'from'    => 'DME',
        'to'      => 'JFK',
        'depart'  => '02.01.2020 23:00',
        'arrival' => '03.01.2020 11:44'
    ],

    [   'from'    => 'DME',
        'to'      => 'HKT',
        'depart'  => '01.01.2020 13:40',
        'arrival' => '01.01.2020 22:22'
    ],

];

$max_time_in_flight = 0;
$longest_flight = null;
foreach ($flights as $flight) {
	
	$last_flight = find_next_flight($flight, $flights);
	if (!$last_flight) {

		$time_in_flight = strtotime($flight['arrival']) - strtotime($flight['depart']);
		if ($time_in_flight > $max_time_in_flight) {

			$max_time_in_flight = $time_in_flight;
			$longest_flight = array($flight);

		}

		continue;

	}
	while (true) {

		$next_flight = find_next_flight($last_flight, $flights);
		if (!$next_flight) break;
		$last_flight = $next_flight;

	}

	$time_in_flight = strtotime($last_flight['arrival']) - strtotime($flight['depart']);
	if ($time_in_flight > $max_time_in_flight) {

		$max_time_in_flight = $time_in_flight;
		$longest_flight = array($flight, $last_flight);

	}

}

foreach ($longest_flight as $flight) {

	$from = $flight['from'];
	$to = $flight['to'];
	$depart = $flight['depart'];
	$arrival = $flight['arrival'];

	echo "$from → $to $depart $arrival<br>";

}

$depart = $longest_flight[0]['depart'];
$arrival = end($longest_flight)['arrival'];

echo "Итого: с $depart по $arrival";

?>