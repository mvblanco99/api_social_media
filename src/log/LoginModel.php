<?php

    namespace Api\ApiLogin;
    use Api\ApiConnection\Connection;

    // Habilitar CORS solo para solicitudes desde http://localhost:5173
    header("Access-Control-Allow-Origin: http://localhost:5173");
    // Permitir solo solicitudes POST y GET
    header("Access-Control-Allow-Methods: POST, GET");
    // Permitir ciertos encabezados
    header("Access-Control-Allow-Headers: Content-Type");
    //Recibir las urls y decidir que accion ejecutar

    class LoginModel extends Connection{

        private $conn;

        public function __construct(){
            $this->conn = $this->connect();
        }

        public function search($username, $password){
            // Consulta para obtener todos los posts de un usuario
            $sql = "SELECT * FROM users
            WHERE username = '$username' 
            and password = '$password'";

            $result = $this->conn->query($sql);
    
            // Devolver los resultados como un array JSON
            $user = array();
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $user[] = $row;
                }
            }

            // Cerrar conexión
            $this->conn->close();
    
            return $user;
        }

    }