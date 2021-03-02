<?php
require_once('MenuItem.php');

function order_compare($i1, $i2) {
    if($i1->order > $i2->order) {
        return 1;
    } 

    if ($i1->order == $i2->order) {
        return 0;
    }

    if($i1->order < $i2->order) {
        return -1;
    }     
    

}


class Menu {

    private $name;
    private $items = array();
    private $css_id;
    private $current_page;

    
    public function __construct($page, $name, $css_id = '')
    {
        $this->name = $name;
        $this->current_page = $page;
        $this->css_id = $css_id;
    }

    public function dbLoad($db) {
        $q =  'SELECT cssid, html,link,sort,`target` FROM `menu` JOIN menuitem on menu.menuID=menuitem.menuID WHERE `name` = ? ORDER BY sort';
        $ps = $db->prepare($q);

        $ps->execute([$this->name]);
        


        while($row = $ps->fetch()) {            
            $this->addItem($row['html'],$row['link'],$row['target'],$row['sort']);

            if($this->css_id === '') {
                $this->css_id = $row['cssid'];
            }
        }
        
    }


    public function addItem($html, $target, $atarget, $order=0) {
        $this->items[$target] = new MenuItem(
            $html, 
            $target, 
            $atarget,
            $order, 
            $target==$this->current_page);
    }


    public function __toString(){
        uasort($this->items,"order_compare");

        $out = '<ul id="menu-'.$this->css_id.'">';

        foreach($this->items as $item) {
            $out .= $item;
        }
        
        $out .= '</ul>';
        return $out;
    }
    
}

?>