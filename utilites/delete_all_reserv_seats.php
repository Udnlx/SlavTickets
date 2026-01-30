<?php

namespace ProcessWire;

require_once '../index.php';

ini_set('max_execution_time', 0);
ini_set('memory_limit', '4096M');

echo 'Delete all Reserv Seats' . '<br>';

// $all_passengers = $pages->find('template=reserv_seats, limit=1000');

// foreach ($all_passengers as $passenger) {
// 	echo $passenger->title . '<br>';
// 	$del_page = $passenger;
// 	$pages->delete($del_page);
// }