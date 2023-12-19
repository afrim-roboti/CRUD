<?php

include('db.php');

$products = read();

// CREATE
function create($data){
    global $pdo;
     $sql = 'INSERT INTO product(name, qty, price , discount, image) VALUES (?, ?, ? , ? ,?)';
     $statement = $pdo->prepare($sql);
    return $statement->execute($data);

}


// READ
function read($id = null){
    global $pdo;
    if(!is_null($id)){
        $sql = 'SELECT * FROM product WHERE id=?';
        $stmt =  $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
   
    $sql = 'SELECT * FROM product ORDER BY id DESC';
    $stmt = $pdo->prepare($sql);         
    $stmt->execute();
    $products=[];

    while($product = $stmt->fetch(PDO::FETCH_ASSOC)){
           $products[] = $product;
    }

    return $products;
}

// UPDATE

function update($data) {
    global $pdo;
    $sql = 'UPDATE product SET name=?, qty=?, price=?, discount=?, image=? WHERE id=?';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}


// DELETE
function delete($id){   
    global $pdo;
    $sql = 'DELETE FROM product WHERE id=?';
    $stmt = $pdo->prepare($sql);
   return  $stmt->execute([$id]);
}

?>