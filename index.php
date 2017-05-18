<?php
/**
 * Created by PhpStorm.
 * User: Eduardo_Chavez
 * Date: 19/3/2017
 * Time: 12:04 AM
 */

spl_autoload_register(function ($classname) {
    require("include/" . $classname . ".php");
});


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host'] = "localhost";
$config['db']['user'] = "root";
$config['db']['pass'] = "";
$config['db']['dbname'] = "salvadoranRecords";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();


$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/', function (Request $request, Response $response) {
    readfile("webpage/index.html");
});

/**ARTISTAS**/
//LISTAR TODOS LOS ARTISTAS
$app->get('/artists', function (Request $request, Response $response) {
    $sth = $this->db->prepare("SELECT * FROM Artist");
    $sth->execute();
    $artists = $sth->fetchAll();
    return $this->response->withJson($artists);
});

//Obtener artista por ID
$app->get('/artist/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM Artist WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $artist = $sth->fetchObject();
    return $this->response->withJson($artist);
});

//Obtener artistas por medio de nombre
$app->get('/artist/search/[{query}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM Artist WHERE UPPER(nombre) LIKE :query");
    $query = "%" . $args['query'] . "%";
    $sth->bindParam("query", $query);
    $sth->execute();
    $todos = $sth->fetchAll();
    return $this->response->withJson($todos);
});

//POST para artista
$app->post('/newartist', function ($request, $response) {
    $data = $request->getParsedBody();
    $sql = "insert into Artist (nombre,miembros,trayectoria,genero,primersencillo)"
        . " VALUES('$data[nombre]','$data[miembros]','$data[trayectoria]','$data[genero]','$data[primersencillo]')";
    $exe = $this->db->query($sql);
    echo("Artista agregado");
});

$app->delete('/artist/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("DELETE FROM Artist WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    echo("Elemento eliminado");
});

$app->put('/artist/[{id}]', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $sql = "UPDATE Artist SET nombre='$data[nombre]', miembros = '$data[miembros]', trayectoria = '$data[trayectoria]',genero = '$data[genero]', primersencillo = '$data[primersencillo]' WHERE id=:id";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    echo("Artista actualizado");
});


/**CONCIERTOS **/

//Busqueda de conciertos por nombre de artista
$app->get('/concert/search/[{query}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT a.nombre as Artista, c.lugar as Lugar, c.asistencia as Asistencia, c.entrada as Entrada, c.ganancias as Ganacias, c.pago_artista as Pago_artista, c.fecha as Fecha,
c.representante as representante from Artist a INNER JOIN nextConcerts c ON a.id = c.id_artista WHERE UPPER(a.nombre) LIKE :query");
    $query = "%" . $args['query'] . "%";
    $sth->bindParam("query", $query);
    $sth->execute();
    $todos = $sth->fetchAll();
    return $this->response->withJson($todos);
});

$app->get('/concerts', function (Request $request, Response $response) {
    $sth = $this->db->prepare("SELECT c.id as identificador, a.nombre as Artista, c.lugar as Lugar, c.asistencia as Asistencia, c.entrada as Entrada, c.ganancias as Ganacias, c.pago_artista as Pago_artista, c.fecha as Fecha,
c.representante as representante from Artist a INNER JOIN nextConcerts c ON a.id = c.id_artista");
    $sth->execute();
    $concerts = $sth->fetchAll();
    return $this->response->withJson($concerts);
});

$app->get('/concert/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT a.nombre as Artista, c.lugar as Lugar, c.asistencia as Asistencia, c.entrada as Entrada, c.ganancias as Ganacias, c.pago_artista as Pago_artista, c.fecha as Fecha,
c.representante as representante from Artist a INNER JOIN nextConcerts c ON a.id = c.id_artista WHERE c.id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $concerts = $sth->fetchObject();
    return $this->response->withJson($concerts);
});


$app->post('/newconcert', function ($request, $response) {
    $data = $request->getParsedBody();
    $sql = "insert into nextConcerts(id_artista,lugar,asistencia,entrada,ganancias,pago_artista,fecha,representante)"
        . " VALUES('$data[id_artista]','$data[lugar]','$data[asistencia]','$data[entrada]','$data[ganancias]','$data[pago_artista]','$data[fecha]','$data[representante]')";
    $exe = $this->db->query($sql);
    echo("Concierto agregado");
});

$app->delete('/concert/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("DELETE FROM nextConcerts WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    echo("Elemento eliminado");
});

$app->put('/concert/[{id}]', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $sql = "UPDATE nextConcerts SET id_artista='$data[id_artista]', lugar = '$data[lugar]', asistencia = '$data[asistencia]', entrada = '$data[entrada]',ganancias = '$data[ganancias]', pago_artista = '$data[pago_artista]', fecha = '$data[fecha]', representante = '$data[representante]' WHERE id=:id";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    echo("Concierto actualizado");
});


/**DISCOGRAFIA**/

$app->get('/discography', function (Request $request, Response $response) {
    $sth = $this->db->prepare("Select d.id as identificador, a.nombre as Artista, d.nombre as Disco, d.anio as Anio, d.ventas_copias as Ventas_copias, d.pistas as Pistas, d.colaboracion as Colaboracion, d.sencillo as Sencillo_principal 
from discography d INNER JOIN artist a ON a.id = d.id_artista");
    $sth->execute();
    $artists = $sth->fetchAll();
    return $this->response->withJson($artists);
});

$app->get('/disc/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("Select a.nombre as Artista, d.nombre as Disco, d.anio as Anio, d.ventas_copias as Ventas_copias, d.pistas as Pistas, d.colaboracion as Colaboracion, d.sencillo as Sencillo_principal 
from discography d INNER JOIN artist a ON a.id = d.id_artista WHERE d.id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $concerts = $sth->fetchObject();
    return $this->response->withJson($concerts);
});


$app->get('/disc/search/[{query}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("Select a.nombre as Artista, d.nombre as Disco, d.anio as Anio, d.ventas_copias as Ventas_copias, d.pistas as Pistas, d.colaboracion as Colaboracion, d.sencillo as Sencillo_principal 
from discography d INNER JOIN artist a ON a.id = d.id_artista WHERE UPPER(a.nombre) LIKE :query");
    $query = "%" . $args['query'] . "%";
    $sth->bindParam("query", $query);
    $sth->execute();
    $todos = $sth->fetchAll();
    return $this->response->withJson($todos);
});

$app->post('/newdisc', function ($request, $response) {
    $data = $request->getParsedBody();
    $sql = "insert into discography(id_artista,nombre,anio,ventas_copias,pistas,colaboracion,sencillo)"
        . " VALUES('$data[id_artista]','$data[nombre]','$data[anio]','$data[ventas_copias]','$data[pistas]','$data[colaboracion]','$data[sencillo]')";
    $exe = $this->db->query($sql);
    echo("Disco agregado");
});

$app->delete('/disc/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("DELETE FROM Discography WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    echo("Elemento eliminado");
});

$app->put('/disc/[{id}]', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $sql = "UPDATE discography SET id_artista='$data[id_artista]', nombre = '$data[nombre]', anio = '$data[anio]', ventas_copias= '$data[ventas_copias]', pistas = '$data[pistas]', colaboracion = '$data[colaboracion]', sencillo = '$data[sencillo]' WHERE id=:id";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    echo("Concierto actualizado");
});

$app->run();