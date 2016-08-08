<?php

namespace Nourhan\Services;

use Nourhan\Database\DB;

class ChangeCarousel{


    public function __construct()
    {
        $this->updateCarousel();
    }

    public function updateCarousel($id,$position,$included)
    {
        $DB = new DB();
        $this->updateCarousel($id,$position,$included);


    }

}