<?php    
    // Object to hold a set of matches.
    class Round
    {
        public function __construct(array $matches)
        {
            $this->matches = $matches;
            $this->winners = array();
        }

        // Iterate through matches, see who wins via Hero->battle(), return an array of the winners.
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