<?php
namespace Fifteen;

class Game
{
    public $min = 3;
    public $max = 9;
    public $box = 999;

    public $win = false;

    public $gridSize = null;

    public $board = [];
    public $tile_cords = [];
    public $box_cords = [];


    public function getBoard()
    {
        return $this->board;
    }

    public function gameWon()
    {
        return $this->win;
    }

    public function greet()
    {
        return "GAME OF FIFTEEN";
    }

    public function draw()
    {
        $board = $this->board;

        array_walk_recursive($board, function (&$item, $key) {
            if ($item === $this->box) {
                $item = "_";
            }
        });

        return $board;
    }

    public function createBoard()
    {
        $num = pow($this->gridSize, 2) - 1;

        for ($i = 0; $i < $this->gridSize; $i++) {
            $this->board[$i] = [];
            for ($j = 0; $j < $this->gridSize; $j++) {
                if ($num === 0) {
                    $this->board[$i][$j] = 999;
                    return;
                } else {
                    $this->board[$i][$j] = $num;
                    $num--;
                }
            }
        }
    }

    public function end()
    {
        if (!$this->gameWon()) {
            throw new \Exception("Error");
        }

        return $this->winMessage;
    }

    public function won()
    {
        for ($i = 0; $i < $this->gridSize; $i++) {
            for ($j = 0; $j < $this->gridSize; $j++) {
                if ($j < $this->gridSize - 1) {
                    if (!($this->board[$i][$j] < $this->board[$i][$j + 1])) {
                        return false;
                    }
                } elseif ($i < $this->gridSize - 1 && $j == $this->gridSize - 1) {
                    if (!($this->board[$i][$j] < $this->board[$i + 1][1])) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function replaceTileAndBox($tile)
    {
        $this->board[$this->box_cords[0]][$this->box_cords[1]] = $tile;
        $this->board[$this->tile_cords[0]][$this->tile_cords[1]] = $this->box;

        if ($this->won()) {
            $this->win = true;
            $this->winMessage = '~You Win~';
        }
    }

    public function findBoxAndTile($tile)
    {
        $tile = (int) $tile;
        $boxFound = false;

        for ($i = 0; $i < $this->gridSize; $i++) {
            for ($j = 0; $j < $this->gridSize; $j++) {
                if (!$boxFound) {
                    if ($this->board[$i][$j] === $this->box) {
                        $this->box_cords[0] = $i;
                        $this->box_cords[1] = $j;
                        $boxFound = true;
                    }
                }
                if ($this->board[$i][$j] === $tile) {
                    $this->tile_cords[0] = $i;
                    $this->tile_cords[1] = $j;
                }
            }
        }
    }

    public function isValidTile($tile)
    {
        foreach ($this->board as $row) {
            if (!($tile === $this->box) && in_array($tile, $row)) {
                return true;
            }
        }

        return false;
    }

    public function move($tile)
    {
        $tile = (int) $tile;

        if (!$this->isValidTile($tile)) {
            throw new \Exception('Number is invalid');
        }

        if (!$this->canMove($tile)) {
            throw new \Exception('Invalid move');
        }

        $this->replaceTileAndBox($tile);
    }

    public function canMove($tile)
    {
        $this->findBoxAndTile($tile);

        if (
            ($this->tile_cords[0] + 1 === $this->box_cords[0] && $this->tile_cords[1] === $this->box_cords[1]) ||
            ($this->tile_cords[0] - 1 === $this->box_cords[0] && $this->tile_cords[1] === $this->box_cords[1]) ||
            ($this->tile_cords[1] + 1 === $this->box_cords[1] && $this->tile_cords[0] === $this->box_cords[0]) ||
            ($this->tile_cords[1] - 1 === $this->box_cords[1] && $this->tile_cords[0] === $this->box_cords[0])
            ) {
            return true;
        }

        return false;
    }

    public function init($gridSize)
    {
        $gridSize = (int) $gridSize;

        if ($gridSize < 3 || $gridSize > 9) {
            return false;
        }

        $this->gridSize = $gridSize;
        $this->createBoard();

        return true;
    }
}
