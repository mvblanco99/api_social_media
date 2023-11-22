<?php

    namespace Api\ApiLogin;
    use Api\ApiLogin\LoginModel;
    use Firebase\JWT\JWT;
    use Predis\Client;

    class LoginController{

        private $redisClient;

        public function __construct(Client $redisClient){
            $this->redisClient = $redisClient;
        }

        // Función para verificar las credenciales del usuario
        public function verify_credentials($username, $password) {
            
            $login_model = new LoginModel();
            $data_user = $login_model->search($username,$password);

            return (count($data_user) > 0) ? true : false;      
        }

        // Función para generar un token JWT
        public function generate_jwt($data) {
            // Aquí se debería implementar la lógica para generar un token JWT
            // Utilizando la biblioteca JWT, la función devuelve el token JWT generado

            $payload = array(
                "username" => $data,
                "exp" => time() + 864000 // El token expirará en un dia
            );
            return JWT::encode($payload, $data, 'HS256');
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
            return ['token' => $token];
        }

        public function index(){
            $response = $this->login('Mvblanco99','26532066');
            return [$response,1];
        }
    }
