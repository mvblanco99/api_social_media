<?php

namespace Api\ApiUsers;
use Api\ApiUsers\UserModel;
use Api\ApiUtilities\Utilities;
use stdClass;

class UserController {

    private $user_model;
    private $utilities;

    private $available_actions = [
        'search' => 'search',
        'all_users' => 'all_users',
        'add_user' => 'add_user',
        'update' => 'update',
        'delete' => 'delete'
    ];

    public function __construct(){
        $this->user_model = new UserModel();  
        $this->utilities = new Utilities(); //No recomendado en caso de que la clase utilities este muy cargada.      
    }
    
    public function wrap_data_user($id, $name, $lastname, $img, $username, $password){
        $user = new stdClass();
        $user->id = $id;
        $user->name = $name;
        $user->lastname = $lastname;
        $user->img = $img;
        $user->username = $username;
        $user->password = $password;
        return $user;
    }

    public function search(){
        echo 'buscar';
    }

    public function all_users(){
        //Realizamos la consulta
        $data = $this->user_model->all_users();

        if(count($data) === 0 ) return [$data, 2]; //Retornamos si la consulta no trae resultados

        $wrap_data_user = array();
        //Ordenamos la data de los usuarios
        foreach ($data as $key => $value) {
            $user = $this->wrap_data_user(
                $value['id'],
                $value['name'],
                $value['lastname'],
                $value['img'],
                $value['username'],
                $value['password']  
            );

            array_push($wrap_data_user,$user);
        }

        return [$wrap_data_user, 1];
    }

    public function add_user(){
        //Obtener datos del cuerpo de la solicitud
        $datacliente = file_get_contents('php://input');
        // Decodificar datos como JSON
        $data = json_decode($datacliente, true);

        // Acceder a los datos
        $name = $data['name'];
        $lastname = $data['lastname'];
        $username = $data['username'];
        $password = $data['password'];

        //Sanitizar los datos


        //Verificar si el usuario existe
        $search_answer = $this->user_model->search_user_for_username($username);
        
        if(count($search_answer) > 0) return ['Usuario ya existe', 2];
        
        //Creamos los directorios correspondientes para el usuario
        $route_directory_images = dirname(__DIR__) . '/Images';

        //Verificamos si la ruta del directorio Images existe
        if(is_dir($route_directory_images)){
            if(!is_dir($route_directory_images.'/'. $username)){
                //creamos el directorio del uduario
                mkdir($route_directory_images.'/'. $username);
            }
        }

        //Insertamos foto de perfil por defecto
        $img = 'Images/Image_Profile_Default/blank-profile-picture.jpg';

        $response =  $this->user_model->add_user($name, $lastname, $password, $username, $img);
        
        if ($response == 0){
            return [$response,2];
        }else{
            return [$response, 1];
        }        
    }

    public function update(){
        echo 'update';
    }

    public function delete(){
        echo 'delete';
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
                return ['Accion no invalida',2];
            }
  
        }else{
            return ['Accion no definida',2];
        }
    }
}