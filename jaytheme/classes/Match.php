<?php   
    // Builds a matchup of two heroes.
    class Match
    {
        public function __construct($heroA, $heroB)
        {
            $this->heroA = $heroA;
            $this->heroB = $heroB;
            $this->winner = none;
        }
        // Set a match winner based off attribute(s)
        public function battle()
        {
            // heroA wins ties for now.
            if ($this->heroA->attr == $this->heroB->attr) {
                $this->winner = $this->heroA;
            }
            elseif ($this->heroA->attr > $this->heroB->attr) {
                $this->winner = $this->heroA;
            } else {
                $this->winner = $this->heroB;
            }

            return $this->winner;
        }
    }
?>    