<?php

class Tournament
    {
        private $heroes_entered;
        private $winner;
        private $round_matches;
        private $round_num;

        public function __construct()
        {
            $this->heroes_entered = array();
            $this->winner = none;
            $this->heroes_remaining = array();
            $this->round_matches = array();
            $this->round_num = 1;
        }

        public function add_hero($hero)
        {
            array_push($this->heroes_entered, $hero);
        }

        public function get_round()
        {
            return $this->round_num;
        }
        // Place the heroes remaining (heroes_remaining array) in the tournament into matches and those matches into a round.
        // Get the winners of the round and set that to the heroes remaining.
        private function solve_round()
        {
            $matches = array();
            for ($i = 0; $i < count($this->heroes_remaining); $i = $i + 2) {
                $heroA = $this->heroes_remaining[$i];
                $heroB = $this->heroes_remaining[$i + 1];
                $match = new Match($heroA, $heroB);
                array_push($matches, $match);
            }

            $round = new Round($matches);
            $winners = $round->play_round();
            $this->heroes_remaining = $winners;
            // Update the round_winners associate array with the round number as the key
            // the value is an array of the winners (Hero class).
            $this->round_winners[$this->round_num] = $winners;

            //TODO - loop through round_matches like round_winners
            $this->round_matches[$this->round_num] = $matches;

            return $winners;
        }

        // Keep solving rounds until there is a winner.
        public function tournament()
        {
            // Bracket needs to be 4**x.
            if (count($this->heroes_entered) % 4 != 0) {
                exit("Invalid bracket -- must be a power of 4!");
            }
            else {
                $this->heroes_remaining = $this->heroes_entered;
            }
            

            while (count($this->heroes_remaining) > 1) {
                $round_winners = $this->solve_round();
                
                if (count($round_winners) == 1) {
                    $this->winner = $round_winners[0];
                    break;
                }

                $this->round_num += 1;
            }
        }

        public function list_heroes($query='')
        {
            $heroes = array();
            if ( $query ) {
                foreach ( $this->heroes_entered as $hero ) {
                array_push($heroes, $hero->$query);
                }
            } else {
                foreach ( $this->heroes_entered as $hero ) {
                    array_push($heroes, $hero);
                }
            }

            return $heroes;
        }


        public function get_round_winners($round_num)
        {
            $winner_array = array();
            $matches = $this->round_matches[$round_num];
            foreach ($matches as $match) {
                $winner = $match->winner;
                array_push($winner_array, $winner);
            }

            return $winner_array;
        }

        public function get_matches($round_num)
        {
            $matches = $this->round_matches[$round_num];    
            return $matches;
        }

    }
?>