<?php

namespace ProcessWire;

if ($input->get['departure'] && $input->get['idbus'] && $input->get['idroute']) {

	$departure_date = $input->get['departure'];
	$id_bus = $input->get['idbus'];
	$id_route = $input->get['idroute'];

	$bus = $pages->get("template=buses_item, id=" . $id_bus);
	$routeparam = [];

	$bus_tickets_ondate = $pages->find("template=purchased_tickets, id_bus=" . $id_bus . ", date_depart=" . $departure_date . "");
	$count_tickets = count($bus_tickets_ondate);
	$max_seat = 53;
	$free_seat = $max_seat - $count_tickets;

	$table_bus = $bus->table_price;
	$route = $table_bus->get("id=" . $id_route);

	$departure = $route->name_station;
	$departure_point = mb_substr($departure, 0, mb_strlen($departure, 'UTF-8') - 5, 'UTF-8'); 
	$departure_time = mb_substr($departure, -5, null, 'UTF-8');
	$departure_station = $route->departure_station;

	$destination = $route->name_station_finish;
	$destination_point = mb_substr($destination, 0, mb_strlen($destination, 'UTF-8') - 5, 'UTF-8');
	$destination_time = mb_substr($destination, -5, null, 'UTF-8');
	$destination_station = $route->destination_station;

	$routeparam[] = [
	"id" => $id_bus,
	"idRoute" => $id_route,
	"name" => $departure_point . ' - ' . $destination_point,
	"departurePoint" => $departure_point,
	"departureTime" => $departure_time,
	"departureStation" => $departure_station,
	"travelTime" => $route->travel_time,
	"nextDay" => $route->next_day,
	"destinationPoint" => $destination_point,
	"destinationTime" => $destination_time,
	"destinationStation" => $destination_station,
	"price" => $route->price_ticket,
	"carrier" => $bus->carrier,
	"freeSeats" => $free_seat,
	"departure" => $departure_date,
	];

	$result["routeparam"] = $routeparam;

} else {
	$result = setError('Не достаточно параметров для запроса', $result, 404);
}

