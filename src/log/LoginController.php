<?php

    namespace Api\ApiLogin;
    use Api\ApiLogin\LoginModel;
    use Firebase\JWT\JWT;
    use Predis\Client;
    use stdClass;

    class LoginController{

        private $redisClient;
        private $data_user;

        public function __construct(Client $redisClient){
            $this->redisClient = $redisClient;
        }

        public function wrap_data_user($id, $name, $lastname, $img, $username){
            $user = new stdClass();
            $user->id = $id;
            $user->name = $name;
            $user->lastname = $lastname;
            $user->img = $img;
            $user->username = $username;
            return $user;
        }

        // Función para verificar las credenciales del usuario
        public function verify_credentials($username, $password) {
            
            $login_model = new LoginModel();
            $response = $login_model->search($username,$password);

            if(count($response) > 0) {
                $this->data_user = $this->wrap_data_user(
                    $response[0]['id'],
                    $response[0]['name'],
                    $response[0]['lastname'], 
                    $response[0]['img'],
                    $response[0]['username'],
                );
                   
                return true;
            }else { 
                return false; 
            }      
        }

        // Función para generar un token JWT
        public function generate_jwt($data) {

            $secretKey = random_bytes(32);

            $payload = array(
                'data_user' => $this->data_user,
                'expires' => time() + 3600,
            );

            return JWT::encode($payload, $secretKey, 'HS256');
        }

        // Función para guardar el token JWT en Redis
        public function save_token_to_redis($username, $token) {

            // Almacenar el token JWT en Redis con una clave que sea el nombre de usuario
            $this->redisClient->set('token:' . $username, $token);
        }

        // Función para iniciar sesión en la aplicación
        public function login($username, $password) {
            // Verificar las credenciales del usuario
            if (!$this->verify_credentials($username, $password)) {
                return ['Invalid credentials', 2];
            }

            // Generar un token JWT
            $token = $this->generate_jwt($username);

            // Guardar el token JWT en Redis
            $this->save_token_to_redis($username, $token);


            // Devolver el token JWT al cliente
            return [['auth_token' => $token], 1];
        }

        public function index(){
            //Obtener datos del cuerpo de la solicitud
            $datacliente = file_get_contents('php://input');
            // Decodificar datos como JSON
            $data = json_decode($datacliente, true);
 
            // Acceder a los datos
            $username = $data['username'];
            $password = $data['password'];
            $response = $this->login($username,$password);
            return $response;
        }
    }
