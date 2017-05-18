<?php
/**
 * Created by PhpStorm.
 * User: Eduardo_Chavez
 * Date: 19/3/2017
 * Time: 12:30 AM
 */

class ArtistMapper extends Mapper {
    public function getArtists(){
        $sql = "SELECT * from Artist";
        $stmt = $this->db->query($sql);

        /**$results = [];
        while ($row = $stmt->fetch()){
            $results[] = new ArtistEntity($row);
        }**/

       return $data = $stmt->fetch();

    }
}