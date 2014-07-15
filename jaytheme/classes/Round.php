<?php    
    // Sets of Match objects will be stored in a Round object.
    // The Round class has a method, play_round(), which loops through the Match objects to have the Hero objects battle.
    class Round
    {
        public function __construct(array $matches)
        {
            $this->matches = $matches;
            $this->winners = array();
        }

        // Iterate through matches, see who wins via Match->battle(), return an array of the winners.
        public function play_round()
        {
            foreach ( $this->matches as $match ) {
                $winner = $match->battle();
                array_push($this->winners, $winner);
            }
            return $this->winners;
        }
    }
?>