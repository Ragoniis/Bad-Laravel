<?php

class Request{

    public function __construct(array $arr){
        // var_dump($arr);
        foreach($arr as $k =>$v){
            $this->$k = $v;
        }

    }
}