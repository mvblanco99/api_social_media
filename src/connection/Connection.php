<?php

    namespace Api\ApiConnection;
    use mysqli;
    use Exception;

    class Connection{

        private $host;
        private $username;
        private $password;
        private $db_name;
    
        public function get_data_config() {
            // Obtén detalles de conexión desde el archivo de configuración
            $config_file = dirname(dirname(__DIR__)) . '/config.php';
            if (file_exists($config_file)) {
                require_once $config_file;
                $this->host = $config['host'];
                $this->username = $config['username'];
                $this->password = $config['password'];
                $this->db_name = $config['db_name'];
            } else {
                throw new Exception("No se encontró el archivo de configuración");
            }
        }

        public function connect(){

            $this->get_data_config();

            // Crear conexión
            $conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            // Verificar conexión
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            return $conn;
        }
    }