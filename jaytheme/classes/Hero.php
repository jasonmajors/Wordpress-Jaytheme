<?php
// Simple class to hold a Hero's name and a single attribute.
// Could expand to hold multiple attributes.
class Hero
{
    public function __construct($attr, $name)
    {
        $this->attr = $attr;
        $this->name = $name;
    }
}
?>