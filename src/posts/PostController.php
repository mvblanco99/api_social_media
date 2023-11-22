<?php

    namespace Api\ApiPosts;
    use Api\ApiPosts\PostModel;
    use Api\ApiUtilities\Utilities;
    use stdClass;

    class PostController{

        private $utilities;
        private $post_model;

        private $available_actions = [
            'search' => 'search',
            'all_posts' => 'all_posts',
            'add_post' => 'add_post',
            'update' => 'update',
            'delete' => 'delete'
        ];

        public function __construct(){
            $this->utilities = new Utilities(); //No recomendado en caso de que la clase utilities este muy cargada. 
            $this->post_model = new PostModel();     
        }

        public function wrap_data_post($id,$content,$img_post,$creation_date,
            $modification_date, $id_user,$name_user,$lastname_user,$img_user){

            $post = new stdClass();
            $post->id = $id;
            $post->content = $content;
            $post->img_post = $img_post;
            $post->creation_date = $creation_date ;
            $post->modification_date = $modification_date;
            $post->id_user = $id_user;
            $post->name_user = $name_user;
            $post->lastname_user = $lastname_user;
            $post->img_user = $img_user;
            return $post;
        }

        public function search(){
            echo 'buscar';
        }
    
        public function all_posts(){
            
            //Realizamos la consulta
            $data = $this->post_model->all_posts();

            if(count($data) === 0 ) return [$data, 2]; //Retornamos si la consulta no trae resultados
    
            $wrap_data_posts = array();
            //Ordenamos la data de los usuarios
            foreach ($data as $key => $value) {
                $post = $this->wrap_data_post(
                    $value['id_post'],
                    $value['content'],
                    $value['img_post'],
                    $value['creation_date'],
                    $value['modification_date'],
                    $value['id_user'],
                    $value['name'],
                    $value['lastname'],
                    $value['img_user'],  
                );
    
                array_push($wrap_data_posts,$post);
            }
    
            return [$wrap_data_posts,1];
        }
    
        public function add_post(){
            //Obtener datos del cuerpo de la solicitud
            $datacliente = file_get_contents('php://input');
            // Decodificar datos como JSON
            $data = json_decode($datacliente, true);

            // Acceder a los datos
            $content = $data['content'];
            $user_id = $data['user_id'];
            $img = $data['img'];

            //Sanitizar los datos

            $response_of_register = $this->post_model->add_post($content,$img,$user_id);

            if($response_of_register == 0 ) return [0,2];

            //Obtenemos el ultimo post creado
            $last_post = $this->post_model->get_latest_post($user_id);

            //Organizamos la data del post
            $post = $this->wrap_data_post(
                $last_post['id_post'],
                $last_post['content'],
                $last_post['img_post'],
                $last_post['creation_date'],
                $last_post['modification_date'],
                $last_post['id_user'],
                $last_post['name'],
                $last_post['lastname'],
                $last_post['img_user'],
            );
            
            return [$post,1];
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
                    return ["Accion no invalida",2];
                }
      
            }else{
                return ["Accion no definida",2];
            }
        }
    }