<?php

namespace ProcessWire;

$arr_buses = [];
$arr_calendar_search = [];

if ($input->get['from'] && $input->get['where']) {

    $from = trim($sanitizer->text($input->get['from']));
    $where = trim($sanitizer->text($input->get['where']));

    $bus_pages = $pages->find(
        "template=buses_item, station_start.title^=" . $sanitizer->selectorValue($from) . ", station_finish.title^=" . $sanitizer->selectorValue($where)
    );

    foreach ($bus_pages as $bus_page) {
        $price = 5000;

        foreach ($bus_page->table_price as $price_row) {
            $nameStation = trim($price_row->name_station);
            $nameStationFinish = trim($price_row->name_station_finish);

            $startMatch = mb_stripos($nameStation, $from) === 0;
            $finishMatch = mb_stripos($nameStationFinish, $where) === 0;

            if ($startMatch && $finishMatch) {
                $price = !empty($price_row->price_ticket) ? (float)$price_row->price_ticket : 5000;
                break;
            }
        }

        if ($bus_page->title) {
            $year = date('Y');
            $year_plus = date('Y', strtotime('+1 year'));

            foreach ($bus_page->bus_calendar as $bc_item) {
                if ($bc_item->days && $bc_item->month) {
                    $arr_bus_calendar_days = explode(',', $bc_item->days);

                    foreach ($arr_bus_calendar_days as $day) {
                        $day = trim($day);

                        if ($day === '') {
                            continue;
                        }

                        $arr_calendar_search[] = [
                            'date' => $day . '.' . $bc_item->month . '.' . $year,
                            'price' => $price,
                        ];

                        $arr_calendar_search[] = [
                            'date' => $day . '.' . $bc_item->month . '.' . $year_plus,
                            'price' => $price,
                        ];
                    }
                }
            }
        }

        $arr_buses[] = [
            'id' => $bus_page->id,
            'title' => $bus_page->title,
            'price' => $price,
        ];
    }

    $calendar_unique = [];

    foreach ($arr_calendar_search as $item) {
        $date = $item['date'];
        $itemPrice = (float)$item['price'];

        if (!isset($calendar_unique[$date])) {
            $calendar_unique[$date] = $item;
        } elseif ($itemPrice < (float)$calendar_unique[$date]['price']) {
            $calendar_unique[$date] = $item;
        }
    }

    $result["buses"] = $arr_buses;
    $result["calendar"] = array_values($calendar_unique);

} else {
    $result = setError('Не достаточно параметров для запроса', $result, 404);
}