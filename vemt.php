<?php

/**
 * Description:
 * A squad of robotic rovers are to be landed by NASA on a plateau on Pluto. This plateau, which is curiously rectangular,
 * must be navigated by the rovers so that their on-board cameras can get a complete view of the surrounding terrain to send
 * back to the NASA HQ.
 * A rover’s position is represented by a combination of an x and y co-ordinates and a letter representing one of the four
 * cardinal compass points. The plateau is divided up into a grid to simplify navigation. An example position might be 0, 0, N ,
 * which means the rover is in the bottom left corner and facing North.
 * In order to control a rover, NASA sends a simple string of letters. The possible letters are L , R and M . L and R makes the
 * rover spin 90 degrees left or right respectively, without moving from its current spot. M means move forward one grid point,
 * and maintain the same heading. Assume that the square directly North from (x,y) is (x,y+1).
 *
 * Input:
 * The problem below requires some kind of input.
 * The first line of input is the upper-right coordinates of the plateau, the lower-left coordinates are assumed to be (0,0) .
 * The rest of the input is information pertaining to the rovers that have been deployed. Each rover has two lines of input.
 * The first line gives the rover’s position, and the second line is a series of instructions telling the rover how to explore
 * the plateau.
 * The position is made up of two integers and a letter separated by spaces, corresponding to the x and y co-ordinates and
 * the rover’s orientation. Each rover will be finished sequentially, which means that the second rover won’t start to move
 * until the first one has finished moving.
 *
 *
 * Output:
 * The output for each rover should be its final co-ordinates and heading.
 * Example of input and output
 * Test Input:
 * 5 5
 * 1 2 N
 * LMLMLMLMM
 * 3 3 E
 * MMRMMRMRRM
 * Expected Output:
 * 1 3 N
 * 5 1 E
 **/

    $rovers = new Rovers();
    $rovers->execute();

    class Rovers
    {
        // could build a config file with all configurable options
        const ROVERS = 2;

        const CARDINAL_POINTS = ["N" => "E", "E" => "S", "S" => "V", "V" => "N"];

        const ACTIONS = ["N" => "y+", "E" => "x+", "S" => "y-", "V" => "x-"];

        private $grid;

        private $instructions = [];

        private $hasErrors = false;

        private $errors = [];

        /**
         * Main function that executes the code
         */
        public function execute()
        {
            $this->addData();

            foreach ($this->instructions as $instruction) {
                $this->executeCommands($instruction);
            }
        }

        /**
         * Read data from console
         */
        private function addData()
        {
            $this->grid = readline("Please enter the upper-right coordinates of the plateau: ");

            for ($i = 0; $i < self::ROVERS; $i++) {
                $this->instructions[$i]['position'] = readline("Please enter the position of the rover: ");
                $this->instructions[$i]['commands'] = readline("Please enter the commands for the rover: ");
            }

            $this->validateData();

            if ($this->hasErrors) {
                // could be styled into something more beautiful
                print_r($this->errors);exit;
            }
        }

        /**
         * Validate the data that was inserted. Could be expanded with multiple other validations
         */
        private function validateData()
        {
            // simple check to see if space is added
            $grid = explode(" ", $this->grid);
            if (count($grid) !== 2) {
                $this->hasErrors = true;
                $this->errors[] = 'Incorrect grid data';
            }

            // simple check to see if space is added
            foreach ($this->instructions as $instruction) {
                $position = explode(" ", $instruction['position']);

                if (count($position) !== 3) {
                    $this->hasErrors = true;
                    $this->errors[] = 'Incorrect position data';
                }
            }

            // different validations could be added here (command direction only in cardinal points), actions only in L,R OR M,
            // position of rover not outside the grid, commands only uppercase etc
        }

        /**
         * For a set of instructions we execute the commands for the rover
         * The instructions are made of the current position of the rover and the input send by NASA
         *
         * @param $instruction
         */
        private function executeCommands(array $instruction)
        {
            $position = explode(" ", $instruction['position']);
            $actions = $instruction['commands'];

            $commands = str_split($actions);
            foreach($commands as $command) {
                if ($command == 'L' || $command == 'R') {
                    $position = $this->rotate($position, $command);
                } else {
                    $position = $this->move($position);
                }
            }
            echo "Position of the rover is: ".implode(" ", $position) . PHP_EOL;
        }

        /**
         * Rotate the rover 90 degrees to the right or left. Returns the new position of the rover (x, y, D)
         *
         * @param array $position
         * @param $command
         * @return array
         */
        private function rotate(array $position, $command)
        {
            $direction = $position[2];
            switch ($command)
            {
                case "L":
                    $cardinalPoints = array_flip(self::CARDINAL_POINTS);
                    $direction = $cardinalPoints[$direction];
                    break;
                case "R":
                    $cardinalPoints = self::CARDINAL_POINTS;
                    $direction = $cardinalPoints[$direction];
                    break;
            }

            $position[2] = $direction;
            return $position;
        }

        /**
         * Moves the rover one position in the direction given by the cardinal point.
         * Returns a new rover position (x, y, D)
         *
         * @param array $position
         * @return array
         */
        private function move(array $position)
        {
            $x = $position[0];
            $y = $position[1];
            $cardinalPoint = $position[2];
            switch (self::ACTIONS[$cardinalPoint])
            {
                case 'y+':
                    $y++;
                    break;
                case 'y-':
                    $y--;
                    break;
                case 'x+':
                    $x++;
                    break;
                case 'x-':
                    $x--;
                    break;
            }

            $position[0] = $x;
            $position[1] = $y;
            return $position;
        }
    }
 ?>