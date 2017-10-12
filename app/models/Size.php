<?php

namespace app\models;

use core\App;

class Size
{
    static $table = "sizes";
    public $size;

    //get all sizes
    public static function getAll()
    {
        return App::get('database')->getAll(Size::$table);
    }

    //insert new size
    public static function insert($size)
    {
        App::get('database')->insert(Size::$table, ['size' => $size]);
    }
    
    //get size by id
    public static function getById($id)
    {
        return App::get('database')->getById(Size::$table, $id);
    }

    //update size by Id
    public static function updateById($id, $size)
    {
        App::get('database')->updateById(Size::$table, [ 'size' => $size ], $id);
    }

    //delete size by Id
    public static function deleteById($id)
    {
        //get Product_size_id by size_id
        $proSizeId =  ProductSize::getProSizeBySizeId($id);
        //delete all products_sizes has size_id
        foreach ($proSizeId as $data) {
            App::get('database')->deleteById(ProductSize::$table, $data->id);
        }
        App::get('database')->deleteById(Size::$table, $id);
    }

    //get last record in size table
    public static function getLastRecord()
    {
        return App::get('database')->getLastRecord(Size::$table);
    }

    // check data exist
    public static function checkDataExist($params) 
    {
        $size = App::get('database')->checkDataExist(Size::$table, $params);
        return $size ? true : false;
    } 
}
