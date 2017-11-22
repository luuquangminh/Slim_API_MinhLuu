<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});
// Get All Guitars
$app->get('/api/guitars', function(Request $request, Response $response){
    $sql = "SELECT * FROM guitars";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Guitars = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJSON($data);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
// Get Single Guitar
$app->get('/api/guitars/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM guitars WHERE id = $id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Guitar = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJSON($data);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
// Add Guitar
$app->post('/api/guitar/add', function(Request $request, Response $response){
    $guitar_model = $request->getParam('guitar_model');
    $guitar_type= $request->getParam('guitar_type');
    $brand_name = $request->getParam('brand_name');
    $price = $request->getParam('price');
    $sql = "INSERT INTO guitars (guitar_model,guitar_type,brand_name,price) VALUES
    (:guitar_model,:guitar_type,:brand_name,:price,)";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':guitar_model', $guitar_model);
        $stmt->bindParam(':guitar_type',  $guitar_type);
        $stmt->bindParam(':brand_name',      $brand_name);
        $stmt->bindParam(':price',      $price);
        $stmt->execute();
        echo '{"notice": {"text": "Guitar Added"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
// Update Guitar
$app->put('/api/guitar/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $guitar_model = $request->getParam('guitar_model');
    $guitar_type=$request -> getParam('guitar_type');
    $barnd_name = $request->getParam('brand_name');
    $price = $request->getParam('price');
    $sql = "UPDATE guitars SET
				guitar_model    = :guitar_model,
				guitar_type 	= :guitar_type,
                brand_name		= :brand_name,
                price		    = :price,
			WHERE id = $id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':guitar_model', $guitar_model);
        $stmt->bindParam(':guitar_type',  $guitar_type);
        $stmt->bindParam(':brand_name',      $brand_name);
        $stmt->bindParam(':price',      $price);
        $stmt->execute();
        echo '{"notice": {"text": "Guitar Updated"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
// Delete Guitar
$app->delete('/api/guitar/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM guitars WHERE id = $id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Guitar Deleted"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});