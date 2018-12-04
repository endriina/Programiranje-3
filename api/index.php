<?php
require 'flight/Flight.php';

Flight::route('/', function(){
    echo 'hello world!';
});

//SELECT
Flight::route('/misli', function(){
    $veza = Flight::db();
	$izraz = $veza->prepare("select sifra, korisnik, misao, datum from misao");
    $izraz->execute();
    echo json_encode($izraz->fetchAll(PDO::FETCH_OBJ));
});
Flight::route('/misli/@id', function($sifra){
    $veza = Flight::db();
	$izraz = $veza->prepare("select sifra, korisnik, misao, datum from misao where sifra=:sifra");
	$izraz->execute(array("sifra" => $sifra));
    echo json_encode($izraz->fetch(PDO::FETCH_OBJ));
});
//INSERT CREATE
Flight::route('POST /dodaj', function(){
	$o = json_decode(file_get_contents('php://input'));
	$veza = Flight::db();
	$izraz = $veza->prepare("insert into misao (korisnik, misao, datum) values (:korisnik,:misao,:datum)");
	$izraz->execute((array)$o);
	echo "OK";
});
//UPDATE
Flight::route('POST /update', function(){
	$o = json_decode(file_get_contents('php://input'));
	$veza = Flight::db();
	$izraz = $veza->prepare("update misao set korisnik=:korisnik,misao=:misao,datum=:datum where sifra=:sifra;");
	$izraz->execute((array)$o);
	echo "OK";
});
//DELETE
Flight::route('POST /obrisi/n', function(){
	$o = json_decode(file_get_contents('php://input'));
	$veza = Flight::db();
	$izraz = $veza->prepare("delete from misao where sifra=:sifra;");
	$izraz->execute((array)$o);
	echo "OK";
});
//SEARCH
Flight::route('/search/@uvjet', function($uvjet){
	$veza = Flight::db();
	$izraz = $veza->prepare("select korisnik, misao from misao where concat(korisnik, misao) like :uvjet");
	$izraz->execute(array("uvjet" => "%" . $uvjet . "%"));
	echo json_encode($izraz->fetchAll(PDO::FETCH_OBJ));
});
//utility
Flight::map('notFound', function(){
	$poruka=new stdClass();
	$poruka->status="404";
	$poruka->message="Not found";
	echo json_encode($poruka);
 });
//LOKALNO
//Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=kolokvij1;charset=UTF8','root',''));
//SERVER
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=eeskic_P3;charset=UTF8','eeskic','ihsf732uhi'));

Flight::start();
