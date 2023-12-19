<?php
    include('crud.php');

    // read products
    $products = read();

    
    // create product
    if(isset($_POST['create'])) {
        if(create([$_POST['name'], $_POST['qty'], $_POST['price'], $_POST['discount'], $_FILES['image']['name']])) {
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$_FILES['image']['name']);
            header('Location: web-crud.php?message=Product was created successfully');
        } else {
            header('Location: web-crud.php?message=Something want wrong');
        }
    }

    
    // update product
    if(isset($_GET['action']) && $_GET['action'] == 'edit') {
        $product = read($_GET['id']);
        
    }
    // AZHURNIMI I TE DHENAVE
    if(isset($_POST['update'])) {
        $data = [
            $_POST['name'], $_POST['qty'], $_POST['price'] , $_POST['discount'] , $_FILES['image']['name'] , $_POST['id']

        ];


       
        if(update($data)){
            if(isset($_FILES['image']['name'])) 
            {
                unlink('uploads/' .$_POST['old_image']);
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$_FILES['image']['name']);
                header('Location: web-crud.php?message=Product was updated successfully');
            }
           else{
                header('Location: web-crud.php?message=Something want wrong.');
            }

         }
     }     
      


    // delete product
    if(isset($_GET['action']) && $_GET['action'] == 'delete') {
       if(delete($_GET['id'])){
           header('Location: web-crud.php?message=Product was delete successfully');
       }
       else{
           header('Location: web-crud.php?message=Something want wrong');
       }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <!-- Create -->
    <?php if(isset($_GET['message'])): ?>
    <div class="container mt-5">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $_GET['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php endif; ?>

    <div class="container my-5">
        <?php if(!isset($_GET['action']) || $_GET['action'] !== 'edit'): ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="qty">Quantity</label>
                <input type="number" name="qty" id="qty" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="discount">Discount</label>
                <input type="number" name="discount" id="discount" class="form-control" value="0" required />
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control" />
            </div>
            
            <button type="submit" name="create" class="btn btn-sm btn-outline-primary mt-3">Create</button>
        </form>
        <?php endif; ?>

        <!-- UPDATE -->

        <?php if(isset($_GET['action']) && $_GET['action'] === 'edit'): ?>
          <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
             <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required value="<?= (isset($product['name'])) ? $product['name'] : '' ?>"/>
            </div>
            <div class="form-group">
                <label for="qty">Quantity</label>
                <input type="number" name="qty" id="qty" class="form-control" required value="<?= (isset($product['qty'])) ? $product['qty'] : 0 ?>" />
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" required value="<?= (isset($product['price'])) ? $product['price'] : '' ?>" />
            </div>
            <div class="form-group">
                <label for="discount">Discount</label>
                <input type="number" name="discount" id="discount" class="form-control" required value="<?= (isset($product['discount'])) ? $product['discount'] : 0 ?>" />
            </div>
            <div class="form-group">
                 <label for="image">Image</label>
                 <input type="file" name="image" id="image" class="form-control" />
                 <?php if(isset($product['image'])): ?>
                    <small>current image :</small>
                    <img src="uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" height="70">
                <?php endif; ?>
             </div>
                <input type="hidden" name="old_image" value="<?= $product['image'] ?>">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <button type="submit" name="update" class="btn btn-sm btn-outline-primary">Update</button>
            </form>
        <?php endif; ?>

        <?php if(count($products) > 0): ?>
        <div class="table-responsive my-5">
            <table class="table table-bordered">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Image</th>
                    <th></th>
                </tr>
                <?php foreach($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['qty'] ?></td>
                    <td><?= $product['price'] ?> EUR</td>
                    <td><?= $product['discount'] ?>%</td>
                    <td><img src="uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" height="70"></td>
                    <td>
                        <a href="?action=edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <a href="?action=delete&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>