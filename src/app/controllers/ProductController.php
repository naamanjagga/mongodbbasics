<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

class ProductController extends Controller
{

    public function indexAction()
    {
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->products;
        $find = $collection->find();
        $this->view->find = $find;
    }
    public function productAction()
    {
    }
    public function addproductAction()
    {
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $db = $connect->test;
        $collection = $db->products;
        $insertOneResult = $collection->insertOne([
            'name' => $_POST['name'],
            'category' => $_POST['category'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'],
            'meta_fields' => [$_POST['label'], $_POST['value']],
            'Variations' => [$_POST['v_name'], $_POST['v_value'],$_POST['v_price']],
        ]);
        $this->response->redirect('product/index');
    }
    public function deleteProductAction()
    {
        $id = $_POST['delete'];
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->products;
        $find = $collection->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($id)));
        $this->response->redirect('product/index');
    }
    public function updateProductAction()
    {
        $id = $_POST['update'];
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->products;
        $find = $collection->find(array("_id" => new MongoDB\BSON\ObjectID($id)));
        $this->view->find = $find;
    }
    public function updateAction()
    {
        if (isset($_POST['updatebtn'])) {
            $id = $_POST['updatebtn'];
            $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
            $collection = $connect->test->products;
            $update = $collection->updateOne(
                array("_id" => new MongoDB\BSON\ObjectID($id)),
                ['$set' => [
                    'name'     => $_POST['name'],
                    'category' => $_POST['category'],
                    'price'     => $_POST['price'],
                    'stock'     => $_POST['stock'],
                    'meta_fields' => [$_POST['label'] => $_POST['value']],
                    'Variations' => [$_POST['v_name'] => $_POST['v_value']],
                ]]

            );
            $this->response->redirect('product/index');
        }
        
    }
    public function quickViewAction()
    {
        $id = $_POST['view'] ;
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->products;
        $find = $collection->find(array("_id" => new MongoDB\BSON\ObjectID($id)));
        $this->view->find = $find; 
    }
    
}
