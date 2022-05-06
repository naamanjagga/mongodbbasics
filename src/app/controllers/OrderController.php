<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{

    public function indexAction()
    {
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->products;
        $find = $collection->find();


        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->products;
        $find = $collection->find();
        $this->view->find = $find;
    }
    public function productDetailAction()
    {
        $id = $_POST['product'];
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->products;
        $find = $collection->find(array("_id" => new MongoDB\BSON\ObjectID($id)));
        $this->view->find = $find;
    
    }
    public function orderProductAction()
    {
        $date = strtotime(date('Y-m-d'));
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->orders;
        $insertOneResult = $collection->insertOne([
            'costumer_name' => $_POST['costumerName'],
            'name' => $_POST['name'],
            'category' => $_POST['category'],
            'price' => $_POST['price'] * $_POST['quantity'],
            'quantity' => $_POST['quantity'],
            'status' => 'paid',
            'date' =>  $date,
        ]);
        $this->response->redirect('order/viewOrder');
    }
    public function viewOrderAction()
    {
        $today = strtotime("2022-05-05");
        $yesterday = strtotime(date('Y-m-d', (time() - 24 * 3600)));
        $new_date = strtotime(date('Y-m-d', strtotime('-1 month')));
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->orders;
        if (isset($_POST['filter'])) {
            $value = $_POST['filter'];
            $this->session->set('status', $value);
            $value = $this->session->get('status');
            $find = $collection->find(["status" => $value]);
            $this->view->find = $find;
            $this->view->value = $value;
        } elseif (isset($_POST['dateFilter'])) {
            switch ($_POST['dateFilter']) {
                case 'today':
                    $find = $collection->find(["date" => ['$eq' => $today]]);
                    $this->view->find = $find;
                    break;
                case 'this_week':
                    $find = $collection->find(["date" => ['$gte' => $yesterday]]);
                    $this->view->find = $find;
                    break;
                case 'this_month':
                    $find = $collection->find(["date" => ['$gt' => $new_date]]);
                    $this->view->find = $find;
                    break;
                case 'custom':
                    $this->view->show = "on";
                    break;
            }
        } elseif (isset($_POST['dateCustom'])) {
            $startDate = strtotime($_POST['startDate']);
            $endDate = strtotime($_POST['endDate']);
            $find = $collection->find(["date" => ['$gte' => $endDate, '$lte' => $startDate]]);
            $this->view->find = $find;
        } else {

            $find = $collection->find();
            $this->view->find = $find;
        }
    }
    public function changeStatusAction()
    {
        $value = $_POST['status'];
        $array = explode(",", $value);
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->orders;
        $find = $collection->updateOne(
            array("_id" => new MongoDB\BSON\ObjectID($array[1])),
            ['$set' => ["status" => $array[0]]]
        );
        $this->response->redirect('order/viewOrder');
    }
}
