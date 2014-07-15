<?php
// Simple class to hold a Hero's name and a single attribute.
// Could expand to hold multiple attributes.
class Hero
{
    public function __construct($name, $attr)
    {
    	$this->name = $name;
        $this->attr = $attr;
    }
}
?>