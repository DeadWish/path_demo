<?php
require_once 'astar.php';
$postData = file_get_contents("php://input");
$data = json_decode($postData, true);

$width = $data['width'];
$height = $data['height'];

$startArr = explode('_', $data['start']);
$startY = $startArr[0];
$startX = $startArr[1];
$endArr = explode('_', $data['end']);
$toX = $endArr[1];
$toY = $endArr[0];
$map = [];
//init
for ($y=0; $y < $height; $y++) {
	$map[$y] = [];
	for ($x=0; $x < $width; $x++) {
        $map[$y][$x] = new Point($x, $y);
	}
}

$hinderList = $data['hinderList'];

//init hinder
foreach ($hinderList as $value) {
	$tmpArr = explode('_', $value);
	$y = intval($tmpArr[0]);
	$x = intval($tmpArr[1]);
	$map[$y][$x]->enable = false;
}

$aStar = new AStar($map);
$path = $aStar->find($startX, $startY, $toX, $toY);
$result = [];

foreach ($path as $point) {
	$result[] = $point->y . '_' . $point->x;
}

$resultMap = $aStar->getMap();
$fMap = [];
foreach ($resultMap as $line) {
	$fLine = [];
	foreach ($line as $point) {
		$fLine[] = $point->f;
	}
	$fMap[] = $fLine;
}

echo json_encode([
	'path' => $result,
	'map'  => $fMap
]);

