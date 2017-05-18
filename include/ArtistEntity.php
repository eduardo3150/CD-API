<?php
/**
 * Created by PhpStorm.
 * User: Eduardo_Chavez
 * Date: 19/3/2017
 * Time: 12:36 AM
 */

class ArtistEntity{
    protected $codArtist;
    protected $nameArtist;
    protected $membersNum;
    protected $amouTrayectoria;
    protected $musicalGenre;
    protected $firstSingle;


    public function __construct(array $data)
    {
        if (isset($data['id_artista']));
        $this->codArtist = $data['id_artista'];

        $this->nameArtist = $data['nombre_artista'];
        $this->membersNum = $data['miembro/s'];
        $this->amouTrayectoria = $data['trayectoria(anios)'];
        $this->musicalGenre = $data['genero_musical'];
        $this->firstSingle = $data['primer_sencillo'];
    }

    /**
     * @return mixed
     */
    public function getCodArtist()
    {
        return $this->codArtist;
    }

    /**
     * @return mixed
     */
    public function getNameArtist()
    {
        return $this->nameArtist;
    }

    /**
     * @return mixed
     */
    public function getMembersNum()
    {
        return $this->membersNum;
    }

    /**
     * @return mixed
     */
    public function getAmouTrayectoria()
    {
        return $this->amouTrayectoria;
    }

    /**
     * @return mixed
     */
    public function getMusicalGenre()
    {
        return $this->musicalGenre;
    }

    /**
     * @return mixed
     */
    public function getFirstSingle()
    {
        return $this->firstSingle;
    }







}