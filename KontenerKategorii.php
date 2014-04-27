<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KontenerKategorii
 *
 * @author Kamil
 */
class KontenerKategorii {
    //put your code here
    public $nazwaKategorii;
    public $zawartoscKategorii;
    
    public function __construct($nazwaKategorii, $zawartoscKategorii) {
        $this->nazwaKategorii=$nazwaKategorii;
        $this->zawartoscKategorii=$zawartoscKategorii;
    }
}
