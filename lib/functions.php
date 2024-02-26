<?php

function get_connection()
{
    $config = require 'config.php';

    $pdo = new PDO(
        $config['dsn'],
        $config['username'],
        $config['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}

function get_pets($limit = null)
{
    $pdo = get_connection();

    try {
        $query = 'SELECT * FROM pet';
        if ($limit !== null) {
            $query .= ' LIMIT :resultLimit';
        }
        $stmt = $pdo->prepare($query);
        if ($limit !== null) {
            $stmt->bindValue(':resultLimit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        $pets = $stmt->fetchAll();

        return $pets;    
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
}

function get_pet($id)
{
    $pdo = get_connection();

    $query = 'SELECT * FROM pet WHERE id = :idVal';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idVal', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

function save_pets($petsToSave)
{
    $json = json_encode($petsToSave, JSON_PRETTY_PRINT);
    file_put_contents('data/pets.json', $json);
}

?>
