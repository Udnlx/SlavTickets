<?php

namespace ProcessWire;

if ($input->get['departure'] && $input->get['from'] && $input->get['to']) {

	$from = $input->get['from'];
	$to = $input->get['to'];

	$bus_pages = $pages->find("template=buses_item, sort=sort");
	$all_bus_ondate = [];

	foreach ($bus_pages as $bus) {
		$departure_date = $input->get['departure'];
		$departure_date_exp = explode("-", $departure_date);
		$month = $departure_date_exp[1];
		$day = $departure_date_exp[2];

		foreach($bus->bus_calendar as $bc_item) {
			if ($bc_item->month == $month) {
				$arr_bus_calendar_days = explode(',', $bc_item->days);
				foreach ($arr_bus_calendar_days as $bc_day) {
					if ($bc_day == $day) {
						$table_bus = $bus->table_price;
						foreach ($table_bus as $item) {
							$departure = $item->name_station;
							$departure_point = mb_substr($departure, 0, mb_strlen($departure, 'UTF-8') - 5, 'UTF-8'); 
							$departure_time = mb_substr($departure, -5, null, 'UTF-8');
							$departure_station = $item->departure_station;

							$destination = $item->name_station_finish;
							$destination_point = mb_substr($destination, 0, mb_strlen($destination, 'UTF-8') - 5, 'UTF-8');
							$destination_time = mb_substr($destination, -5, null, 'UTF-8');
							$destination_station = $item->destination_station;

							if ((strpos($departure_point, $from) !== false) && (strpos($destination_point, $to) !== false)) {
							    $bus_tickets_ondate = $pages->find("template=purchased_tickets, id_bus=" . $bus . ", date_depart=" . $departure_date . "");
								$count_tickets = count($bus_tickets_ondate);
								$max_seat = 53;
								$free_seat = $max_seat - $count_tickets;

								$all_bus_ondate[] = [
								"id" => $bus->id,
								"idRoute" => $item->id,
								"name" => $departure_point . ' - ' . $destination_point,
								"departurePoint" => $departure_point,
								"departureTime" => $departure_time,
								"departureStation" => $departure_station,
								"travelTime" => $item->travel_time,
								"nextDay" => $item->next_day,
								"destinationPoint" => $destination_point,
								"destinationTime" => $destination_time,
								"destinationStation" => $destination_station,
								"price" => $item->price_ticket,
								"carrier" => $bus->carrier,
								"freeSeats" => $free_seat,
								"departure" => $departure_date,
								];
							}
						}
					}
				}
			}
		}
	}

	$result["allBusOnDate"] = $all_bus_ondate;

} else {
	$result = setError('Не достаточно параметров для запроса', $result, 404);
}

