<?php
namespace app\controllers;

use app\models\Order;
use app\models\OrderDetail;
use utils\Functions;

class OrdersController
{
	//select all data of orders
	public function getAll()
	{
		$orders = Order::getAll();
		$success = "Success";
		$failure = "Failure";
		Functions::returnAPI($orders, $success, $failure);
	}

	// select order with id
	public function getById()
	{
		$data = Functions::getDataFromClient();
		if(isset($data['id'])) {
			$id = $data['id'];
			$order = Order::getAllInfoById($id);
			// die(var_dump($order));
			$success = "Success";
			$failure = "Not found Order!";
			Functions::returnAPI($order, $success,$failure);
		} else {
			$failure = "Missing params";
			Functions::returnAPI([], "", $failure);
		}			
	}

	// insert order
	public function insert()
	{
		$data = Functions::getDataFromClient();
		// die(var_dump($data));
		if (isset($data['user_id'])) {
			$user_id = $data['user_id'];
            $date = date("Y-m-d H:i:s"); 

            $paramsOrder = [
                'user_id' => $user_id,
                'date' => $date,
			];            
			//insert order: id, user_id, date
			$order = Order::insert($paramsOrder);	

			//Get id of inserted order
			$orderId = $order[0]->id;

			//data result(return json)
			$resultArray = array("user_id" => $user_id);

			//order detail array
			$orderDetailArray = array();

			// params of order detail inserted
			$paramsOrderDetail = array();

			//Read json of order detail to insert
			for($i=0; $i< count($data['products']); $i++){
				if(
					isset($data['products'][$i]['name']) && 
					isset($data['products'][$i]['size']) &&	
					isset($data['products'][$i]['quantity']) &&
					isset($data['products'][$i]['price']) &&
					isset($data['products'][$i]['total'])
				) {					
					// die(var_dump(count($data['products'])));
					$paramsOrderDetail = [
						'order_id' => $orderId,
						'name' => $data['products'][$i]['name'],
						'size' => $data['products'][$i]['size'],						
						'quantity' => $data['products'][$i]['quantity'],
						'price' => $data['products'][$i]['price'],
						'total' => $data['products'][$i]['total']
					];

					$orderDetail = OrderDetail::insert($paramsOrderDetail);

					// push data to order detail inserted array
					array_push($orderDetailArray,$paramsOrderDetail); 

				} else {
					$failure = "Missing params";
					Functions::returnAPI([], "", $failure);
					break;
				}		
			}
			
			$resultArray["products"] = $orderDetailArray;			

			$success = "Success";
			$failure = "Failure";
			Functions::returnAPI($resultArray , $success, $failure);

		} else {
			$failure = "Missing params";
			Functions::returnAPI([], "", $failure);
		}
	}

	// delete order by id
	public function delete()
	{
		$data = Functions::getDataFromClient();
		if (isset($data['id'])) {
			$id = $data['id'];
			// die(var_dump("miss".$id ));
			$checkId = [
				'id' => $id
			];
			$checkIdExist = Order::checkDataExist($checkId);
			// die(var_dump($checkIdExist ));
			if($checkIdExist){
				$orderDetail = OrderDetail::getByOrderId($id);
				OrderDetail::deleteByOrderId($id);

				$order = Order::deleteById($id);
				// die(var_dump("miss".$checkIdExist ));
				$result["order"] = $order;
				$result["order_details"] = $orderDetail;

				$success = "Success";
				$failure = "Failure";
				Functions::returnAPI($result, $success, $failure);
			} else {
				$failure = "Order is not exist !";
				Functions::returnAPI([], "", $failure);
			}
			// die("Delete success");
		} else {
			$failure = "Missing params";
			Functions::returnAPI([], "", $failure);
		}
	}

	
}