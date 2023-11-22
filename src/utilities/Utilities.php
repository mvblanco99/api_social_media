<?php

    namespace Api\ApiUtilities;

    class Utilities{

        public function check_action($name_action,$array){
            $action = array_key_exists($name_action, $array) 
                ? true : false ;
            return $action;      
        }

    }