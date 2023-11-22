<?php

    namespace Api\ApiRoutes;
    use Api\ApiUsers\UserController;
    use Api\ApiPosts\PostController;
    
    class Routes{

        private $available_class = [
            'users' => UserController::class,
            'posts' => PostController::class,
            'reactions' => 'reactions' 
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

        // public function check_url($url){

        //     // Separar la cadena en pares clave=valor
        //     $pares = explode('&', $url);
        //     // Definir el orden esperado de las claves
        //     $orden_esperado = ['categoria', 'accion', 'param'];

        //     // Verificar si las claves están en el orden correcto
        //     if (count($pares) === count($orden_esperado) && array_keys($pares) === $orden_esperado) {
        //         echo "Las claves están en el orden correcto.";
        //     } else {
        //         echo "Las claves no están en el orden correcto.";
        //     }
        // }

        public function router(){

            // Obtener todos los parámetros de la URL
            $parameters = $_GET;

            //Validamos que la url esta escrita correctamente
            // $request_uri = explode('/', $_SERVER['REQUEST_URI']);
            // $cadena = substr($request_uri[2],1,strlen($request_uri[2])-1);
            // $this->check_url($cadena);
            

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
                        $controller = new $objectClass();
                        $response = $this->classify_response($controller->index());
                        echo json_encode($response);
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
