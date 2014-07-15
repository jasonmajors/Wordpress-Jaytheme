<?php   
    // Takes 2 Hero objects and compares their attribute(s).
    class Match
    {
        // Set up the attributes. After the battle() method has run this Match will now have a "winner" attribute.
        public function __construct($heroA, $heroB)
        {
            $this->heroA = $heroA;
            $this->heroB = $heroB;
            $this->winner = none;
        }
        // Set a match winner based off attribute(s)
        public function battle()
        {
            // heroA wins ties for now because I'm lame.
            if ($this->heroA->attr == $this->heroB->attr) {
                $this->winner = $this->heroA;
            }
            elseif ($this->heroA->attr > $this->heroB->attr) {
                $this->winner = $this->heroA;
            } else {
                $this->winner = $this->heroB;
            }
            // Return the match winner.
            return $this->winner;
        }
    }
?>    