<?php

namespace ProcessWire;

if ($input->get['departure'] && $input->get['from'] && $input->get['to']) {

	$reis = $input->get['from'] . ' - ' . $input->get['to'];
	//echo $reis;

	$bus_pages = $pages->find("template=buses_item, title~=" . $reis . ", sort=sort");
	$all_bus_ondate = [];

	foreach ($bus_pages as $bus) {
		$departure = $input->get['departure'];
		$departure_exp = explode("-", $departure);
		$month = $departure_exp[1];
		$day = $departure_exp[2];

		foreach($bus->bus_calendar as $bc_item) {
			if ($bc_item->month == $month) {
				$arr_bus_calendar_days = explode(',', $bc_item->days);
				foreach ($arr_bus_calendar_days as $bc_day) {
					$bus_tickets_ondate = $pages->find("template=purchased_tickets, id_bus=" . $bus . ", date_depart=" . $departure . "");
					$count_tickets = count($bus_tickets_ondate);
					$max_seat = 53;
					$free_seat = $max_seat - $count_tickets;
					if ($bc_day == $day) {
						$all_bus_ondate[] = [
							"id" => $bus->id,
							"name" => $bus->title,
							"freeSeats" => $free_seat,
							"departure" => $departure,
						];
					}
				}
			}
		}
	}

	$result["allBusOnDate"] = $all_bus_ondate;

} else {
	$result = setError('Не достаточно параметров для запроса', $result, 404);
}

