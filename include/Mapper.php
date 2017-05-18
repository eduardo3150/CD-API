<?php
/**
 * Created by PhpStorm.
 * User: Eduardo_Chavez
 * Date: 19/3/2017
 * Time: 12:28 AM
 */

abstract class Mapper {
    protected $db;
    public function __construct($db) {
        $this->db = $db;
    }
}