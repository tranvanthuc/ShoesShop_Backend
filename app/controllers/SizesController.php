<?php

namespace app\controllers;

use app\models\Size;
use app\models\ProductSize;
use utils\Functions;

class SizesController
{
    //index
    public function getAll()
    {
        $sizes = Size::getAll();

        $success = "Get data success";
        $failure = "Failure";
        echo Functions::returnAPI($sizes, $success, $failure );
    }

    //insert new size
    public function insert()
    {
        if (isset($_REQUEST['size'])) {
            $size = $_REQUEST['size'];

            $checkSize = [
                'size' => $size
            ];
            $checkSizeExist = Size::checkDataExist($checkSize);

            if (!$checkSizeExist) {
                Size::insert($size);
                $sizeData = Size::getLastRecord();

                $success = "Insert data success";
                $failure = "Failure";
                echo Functions::returnAPI($sizeData, $success, $failure );
            } else {
                $failure = "Size exists !";
                echo Functions::returnAPI([], "", $failure );
            }
        } else {
            $failure = "Missing params";            
            echo Functions::returnAPI([], "", $failure );
        }        
    }

    //get size by id
    public function getById()
    {
        $id = $_GET['id'];
        $sizeData = Size::getById($id)[0];

        $success = "Get data success";
        $failure = "Failure";
        echo Functions::returnAPI($sizeData, $success, $failure );
    }

    //update size
    public function update()
    {
        if (isset($_REQUEST['id']) && isset($_REQUEST['size'])) {
            $id = $_REQUEST['id'];
            $size = $_REQUEST['size'];

            $checkSize = [
                'size' => $size
              ];

            $checkSizeExist = Size::checkDataExist($checkSize);

            if (!$checkSizeExist) {
                Size::updateById($id, $size);
                $sizeData = Size::getById($id);

                $success = "Update data success";
                $failure = "Failure";
                echo Functions::returnAPI($sizeData, $success, $failure );
            } else {
                $failure = "Size exists !";
                echo Functions::returnAPI([], "", $failure );
            }
        } else {
            $failure = "Missing params";            
            echo Functions::returnAPI([], "", $failure );
        }
    }

    //get delete size by id
    public function delete()
    {
        if (isset($_REQUEST['id'])){
            $id = $_GET['id'];
            $sizeData = Size::getById($id);
            Size::deleteById($id);

            $success = "Delete data success";
            $failure = "Failure";

            echo Functions::returnAPI($sizeData, $success, $failure );
        } else {
            $failure = "Missing params";            
            echo Functions::returnAPI([], "", $failure );
        }
    }
}
