<?php

    namespace Api\ApiRoutes;
    use Api\ApiUsers\UserController;
    use Api\ApiPosts\PostController;
    use Api\ApiLogin\LoginController;
    use Predis\Client;
    
    class Routes{

        private $available_class = [
            'users' => UserController::class,
            'posts' => PostController::class,
            'reactions' => 'reactions',
            'login' => LoginController::class
        ];

        public function checkCategoryExistence($name_category){
           $exist_category = array_key_exists($name_category, $this->available_class) 
                ? $this->available_class[$name_category] : false ;
            return $exist_category;      
        }

        public function classify_response($arr){
            /*
                - El parametro $arr es un array con dos valores. 
                - Posicion 0 = tiene el mensaje que se enviara al cliente
                - Posicion 1 = tiene el tipo de respuesta. 
                - Tipo de respuesta = 1 -> success, 2 -> error.
            */
            
            if($arr[1] == 1){
                return ['success' => $arr[0]];
            }else if($arr[1] == 2){
                return ['error' => $arr[0]];
            }
        }

        public function check_url($url){

            //La url tiene que verse asi : (Dominio)?categoria=value&accion=value&param=value
            //El parametro 'param' es opcional, se utiliza dependendiendo de la accion seleccionada
            //obligatoriamente de venir la categoria y la accion en la url, en este orden.

            $message_error = ['error' => 'Url no cumple con el formato solicitado'];

            $array_keys= array();

            foreach ($url as $key => $value) {
                array_push($array_keys,explode('=', $value)[0]);
            }

            if(count($array_keys) > 0 && count($array_keys) < 4){

                foreach ($array_keys as $key => $value) {

                    if($key == 0 && $value != 'categoria'){
                        echo json_encode($message_error);
                        die();
                    }

                    if($key == 1 && $value != 'accion'){
                        echo json_encode($message_error);
                        die();
                    }

                    if(count($array_keys) > 2){
                        if($key == 2 && $value != 'param'){
                            echo json_encode($message_error);
                            die();
                        }
                    }
                }

            }else{
                echo json_encode($message_error);
                die();
            }
        }

        public function router(){

            //Obtenemos la url separada en cadenas
            $request_uri = explode('?', $_SERVER['REQUEST_URI']);

            //guardamos la cadena de parametros url
            $url_params = $request_uri[1];

            //Separamos en partes la cadena de parametros
            $url_params = explode('&', $url_params);
            
            //verificamos la url
            $this->check_url($url_params);

            // Obtener todos los parámetros de la URL
            $parameters = $_GET;

            //Verificar que el parametro categoria tenga contenido
            if (isset($parameters['categoria'])) {
                
                $category = $parameters['categoria'];

                $objectClass = $this->checkCategoryExistence($category);

                //Verificar que la categoria sea valida
                if($objectClass !== false){
                    
                    //verificar si existe la clase que se pretende instanciar
                    if(class_exists($objectClass)){
                        /*Creamos una instacia de la clase 
                        correspondiente a la categoria solicitada*/
                        
                        if($objectClass == 'Api\ApiLogin\LoginController'){
                            
                            $redis = new Client();
                            $controller = new $objectClass($redis);
                            $response = $this->classify_response($controller->index());
                            echo json_encode($response);
                        
                        }else{
                            
                            $controller = new $objectClass();
                            $response = $this->classify_response($controller->index());
                            echo json_encode($response);

                        }

                    }else{
                        echo json_encode(['error' =>'Por el momento no podemos procesar su solicitud']);
                    }

                }else{
                    echo json_encode(['error' => 'No se reconoce el recurso solicitado']);
                }

            }else {
                // Acción por defecto si no se proporciona el parámetro 'categoria'
                echo json_encode(['error' => 'No se proporcionó el parámetro categoria']);
            }
        }
    }
