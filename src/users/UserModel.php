<?php

    namespace Api\ApiUsers;
    use Api\ApiConnection\Connection;

    // Habilitar CORS solo para solicitudes desde http://localhost:5173
    header("Access-Control-Allow-Origin: http://localhost:5173");
    // Permitir solo solicitudes POST y GET
    header("Access-Control-Allow-Methods: POST, GET");
    // Permitir ciertos encabezados
    header("Access-Control-Allow-Headers: Content-Type");
    //Recibir las urls y decidir que accion ejecutar

    class UserModel extends Connection{

        private $conn;

        public function __construct(){
            $this->conn = $this->connect();
        }

        public function all_users(){
            global $conn; 
            // Consulta para obtener todos los posts de un usuario
            $sql = "SELECT * FROM users";
            $result = $this->conn->query($sql);
    
            // Devolver los resultados como un array JSON
            $users = array();
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            }
            // Cerrar conexión
            $this->conn->close();

            return $users;
        }

        public function add_user($name, $lastname, $password, $username, $img){
            $sql = "INSERT INTO users (name, lastname, username, password, img, creation_user) 
            VALUES ('$name','$lastname','$username','$password','$img',NOW())";

            $is_register;

            if ($this->conn->query($sql) === TRUE) {
                $is_register = 1;
            } else {
                $is_register = 0;
            }
            
            // Cerrar conexión
            $this->conn->close();

            return $is_register;
    
        }

        public function search_user_for_username($username){
            // Consulta para obtener todos los posts de un usuario
            $sql = "SELECT * FROM users
            WHERE username = '$username'";

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
            // $this->conn->close();
    
            return $user;
        }

    }