<?php
class Point
{
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public $x;
    public $y;
    public $g;
    public $h;
    public $f;
    public $enable = true;
    public $isOpened = false;
    public $isClosed = false;
    public $parent = null;
}

class MinHeap extends SplMinHeap
{
    protected function compare($a, $b)
    {
        return $b->f - $a->f;
    }
}

class AStar
{
    protected $map;

    protected $openList;

    public function __construct($map)
    {
        $this->map = $map;
    }

    public function find($x, $y, $toX, $toY)
    {
        $from = $this->map[$y][$x];
        $to = $this->map[$toY][$toX];
        $openList = new MinHeap();

        $openList->insert($from);

        while (!$openList->isEmpty()) {
            // 获得f最小的点
            $point = $openList->extract();;
            $point->isClosed = true;

            //找出它相邻的点
            $nearPoints = $this->getNearPoints($point);

            foreach ($nearPoints as $nearPoint) {
                if (!$nearPoint->enable || $nearPoint->isClosed) {
                    continue;
                } else if ($nearPoint->isOpened){
                    $g = $point->g + 1;
                    $h = abs($nearPoint->x - $to->x)+abs($nearPoint->y - $to->y);
                    $f = $h + $g;

                    if ($f < $nearPoint->f) {
                        $nearPoint->f = $f;
                        $nearPoint->g = $g;
                        $nearPoint->h = $h;
                        $nearPoint->parent = $point;
                    }
                } else {
                    $g = $point->g  + 1;
                    $h = abs($nearPoint->x - $to->x)+abs($nearPoint->y - $to->y);
                    $f = $h + $g;
                    $nearPoint->f = $f;
                    $nearPoint->g = $g;
                    $nearPoint->h = $h;
                    $nearPoint->parent = $point;
                    $nearPoint->isOpened = true;
                    $openList->insert($nearPoint);
                }
                
                if ($nearPoint == $to) {
                    return $this->getPath($nearPoint);
                }
            }
            
        }
        
        return [];
    }

    protected function getPath(Point $point)
    {
        $path = [];
        while ($point) {
            $path[] = $point;
            if ($point->parent) {
                $point = $point->parent;
            } else {
                break;
            }
        }

        return array_reverse($path);
    }
    
    public function getMap()
    {
        return $this->map;
    }

    protected function getNearPoints(Point $point)
    {
        $points = [];
        $x = $point->x;
        $y = $point->y;
        if (isset($this->map[$y - 1][$x])) {
            $points[] = $this->map[$y - 1][$x];
        }
        if (isset($this->map[$y][$x - 1])) {
            $points[] = $this->map[$y][$x - 1];
        }
        if (isset($this->map[$y + 1][$x])) {
            $points[] = $this->map[$y + 1][$x];
        }
        if (isset($this->map[$y][$x + 1])) {
            $points[] = $this->map[$y][$x + 1];
        }

        return $points;
    }
}

