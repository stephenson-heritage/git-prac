<?php

class MenuItem {

    private $html, $target, $is_active, $atarget;
    public $order;

    public function __construct($html, $target, $atarget, $order, $active) {
        $this->html = $html;
        $this->target = $target;
        $this->atarget = $atarget;
        $this->order = $order;   
        $this->is_active = $active;     
    }

    public function __toString()
    {
        $class = "inactive";
        if($this->is_active) {
            $class = "active";
        }
        return '<li class="'.$class.'"><a href="'.$this->target.'" target="'.$this->atarget.'">'.$this->html.'</a></li>';        
    }


}
?>