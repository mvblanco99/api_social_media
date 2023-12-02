<?php

    namespace Api\ApiNotifications;
    use Api\ApiConnection\Connection;

    // Habilitar CORS solo para solicitudes desde http://localhost:5173
    header("Access-Control-Allow-Origin: http://localhost:5173");
    // Permitir solo solicitudes POST y GET
    header("Access-Control-Allow-Methods: POST, GET");
    // Permitir ciertos encabezados
    header("Access-Control-Allow-Headers: Content-Type");
    //Recibir las urls y decidir que accion ejecutar

    class FriendsSuggestionModel extends Connection{

        private $conn;

        public function __construct(){
            $this->conn = $this->connect();
        }

        public function get_ids_friends($id_user){
            
            $sql = "(SELECT friends.Id_user2 as usuarios
            FROM users
            INNER JOIN friends ON users.id = friends.Id_user1
            WHERE friends.Id_user1 ='$id_user' LIMIT 5)
            
            UNION
            
            (SELECT friends.Id_user1
            FROM users
            INNER JOIN friends ON users.id = friends.Id_user1
            WHERE friends.Id_user2 = '$id_user' LIMIT 5)";
            $result = $this->conn->query($sql);
    
            // Devolver los resultados como un array JSON
            $users = array();
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $users[] = $row['usuarios'];
                }
            }
            // Cerrar conexión
            $this->conn->close();

            return $users;
                
        }

        public function get_data_friends($array_ids_user){

            $sql = "SELECT * FROM users WHERE id = '$id_user'";
            $result = $this->conn->query($sql);
    
            // Devolver los resultados como un array JSON
            $users = array();
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $users[] = $row['usuarios'];
                }
            }
            // Cerrar conexión
            $this->conn->close();

            return $users;
                
        }



    }
