<?php
/**
 * Created by PhpStorm.
 * User: димон
 * Date: 27.09.2019
 * Time: 13:45
 */


class Controller
{
    public function model($model)
    {
        require_once('App/Model/'.$model.'.php');
        return new $model();
    }

    public function view($view,$data=[])
    {
        require_once('App/View/'.$view.'.php');
    }
}
