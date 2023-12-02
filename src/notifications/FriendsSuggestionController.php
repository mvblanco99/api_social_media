<?php

    namespace Api\ApiNotifications;
    use Api\ApiNotifications\FriendsSuggestionModel;
    use Api\ApiUtilities\Utilities;
    use Firebase\JWT\JWT;
    use Predis\Client;
    use stdClass;

    // Habilitar CORS solo para solicitudes desde http://localhost:5173
    header("Access-Control-Allow-Origin: http://localhost:5173");
    // Permitir solo solicitudes POST y GET
    header("Access-Control-Allow-Methods: POST, GET");
    // Permitir ciertos encabezados
    header("Access-Control-Allow-Headers: Content-Type");
    //Recibir las urls y decidir que accion ejecutar

    class FriendsSuggestionController{

        private $redisClient;
        private $model;
        private $utilities;

        public function __construct(Client $redisClient){
            $this->redisClient = $redisClient;
            $this->utilities = new Utilities();
            $this->model = new FriendsSuggestionModel();
        }

        private $available_actions = [
            'addList' => 'addList',
            'deleteList' => 'deleteList',
            'addUserList' => 'addUserList',
            'deleteUserList' => 'deleteUserList',
            'get_data_friends' => 'get_data_friends'
        ];

        public function get_ids_friends($id_user){
            $responseModel = $this->model->get_ids_friends($id_user);
            return $responseModel;
        }

        public function get_data_friends(){
            $ids_friends = $this->get_ids_friends(85);
            
            return [['friends' => $responseModel],1];
        }

        public function addList(){
            //Obtener datos del cuerpo de la solicitud
            // $datacliente = file_get_contents('php://input');
            // // Decodificar datos como JSON
            // $data = json_decode($datacliente, true);
 
            // // Acceder a los datos
            // $username = $data['username'];

            $responseModel = $this->model->friendship_suggestions(85);
            return [$responseModel,1];

        }

        public function index(){
            if(isset($_GET['accion'])){
    
                //Recuperamos el parametro accion
                $action = $_GET['accion'];
    
                //Verificar si la acción existe en nuestro arreglo de actions disponibles
                if($this->utilities->check_action($action, $this->available_actions)) { 
                    // Ejecutamos la accion
                    $response = $this->$action();
                    return $response;
                }else{
                    return ["Accion no invalida",2];
                }
      
            }else{
                return ["Accion no definida",2];
            }
        }

    }