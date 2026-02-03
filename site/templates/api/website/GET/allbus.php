<?php

namespace ProcessWire;

$bus_pages = $pages->find("template=buses_item, sort=sort");
$all_bus = [];
foreach ($bus_pages as $bus) {
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

	$all_bus[] = [
	"id" => $bus->id,
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
	];

	}
}

$result["allBus"] = $all_bus;