<?php

class Tournament
    {
        // Attributes of the Tournament that will be used later.
        // We don't want them to be able to be changed by anything other than the tournament class
        // So they're set to private.
        private $heroes_entered;
        private $winner;
        private $heroes_remaining;
        private $round_matches;
        private $round_num;

        // Create an empty Tournament (starts at round 1 obviously) upon instantiating.
        public function __construct()
        {
            $this->heroes_entered = array();
            $this->winner = none;
            $this->heroes_remaining = array();
            $this->round_matches = array();
            $this->round_num = 1;
        }

        // Use this method to add Hero objects into the Tournament object.
        public function add_hero($hero)
        {
            array_push($this->heroes_entered, $hero);
        }
        // Simple method to return how many rounds were processed in the tournament.
        // Need this in order to keep $round_num private but still have access to how many rounds passed.
        public function count_rounds()
        {
            if ($this->winner) {
                return $this->round_num;
            }
            else {
                echo "Could not retrieve number of rounds -- tournament still running.";
            }
        }

        // Simple method to return the tournament winner.
        public function get_tournament_winner() {
            return $this->winner;
        }

        // This method will place Hero objects into Matches, Matches into a Round, and return the winners of the round.
        // Set to private as it should never need to be used outside of the Tournament class.
        private function solve_round()
        {
            // Empty array where we'll store the Match objects for each round.
            $matches = array();
            // Loop through the heroes_reamining array selecting two Hero objects at a time.
            for ($i = 0; $i < count($this->heroes_remaining); $i = $i + 2) {
                $heroA = $this->heroes_remaining[$i];
                $heroB = $this->heroes_remaining[$i + 1];
                // Create a new Match object with the 2 Hero objects.
                $match = new Match($heroA, $heroB);
                // Add the new Match to the array and repeat until all Heroes in the heroes_ remaining array have been placed.
                $matches[] = $match;
            }
            // Store the matches array for each round in an associative array with the round number as the key.
            // This will allow us to retrieve an array of Match objects based on a round number later.
            // Note that each Match object stores both Hero objects as wall as the match winner.
            $this->round_matches[$this->round_num] = $matches;

            // Create a new Round with the array of Match objects.
            $round = new Round($matches);
            // play_round() returns an array of Hero objects that won their match.
            $winners = $round->play_round();
            // Return the array of winners.
            return $winners;
        }

        // This function will keep creating and playing/solving rounds until there is only 1 Hero object in the winners array from solve_round().
        public function run_tournament()
        {
            // Bracket needs to be a power of 4. Will probably validate the bracket earlier somewhere.
            if (count($this->heroes_entered) % 4 != 0) {
                exit("Invalid bracket -- must be a power of 4!");
            }
            else {
                $this->heroes_remaining = $this->heroes_entered;
            }
            // While there's more than 1 Hero object in our heroes_remaining array, keep creating Rounds and returning the winners.
            while (count($this->heroes_remaining) > 1) {
                // solve_round() returns an array of that round's winners.
                $round_winners = $this->solve_round();
                // Set the heroes_remaining array to the Heroes that won the round.
                $this->heroes_remaining = $round_winners;
                // When solve->round() is called with only 2 Hero objects remaining, the $winners array will contain
                // only one Hero object and the script will end.
                if (count($round_winners) == 1) {
                    $this->winner = $round_winners[0];
                    break;
                }
                $this->round_num += 1;
            }
        }

        public function get_matches($round_num)
        {
            $matches = $this->round_matches[$round_num];    
            return $matches;
        }

    }
?>