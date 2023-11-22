<?php

    namespace Api\ApiPosts;
    use Api\ApiConnection\Connection;

    // Habilitar CORS solo para solicitudes desde http://localhost:5173
    header("Access-Control-Allow-Origin: http://localhost:5173");
    // Permitir solo solicitudes POST y GET
    header("Access-Control-Allow-Methods: POST, GET");
    // Permitir ciertos encabezados
    header("Access-Control-Allow-Headers: Content-Type");
    //Recibir las urls y decidir que accion ejecutar

    class PostModel extends Connection{

        private $conn;

        public function __construct(){
            $this->conn = $this->connect();
        }

        public function all_posts(){ 
            // Consulta para obtener todos los posts de un usuario
            $sql = "SELECT 
                posts.id as id_post,
                content,
                posts.img as img_post,
                creation_date,
                modification_date,
                posts.user as id_user,
                users.name,
                users.lastname,
                users.img as img_user
            FROM posts
            INNER JOIN users ON posts.user=users.id";
            $result = $this->conn->query($sql);
    
            // Devolver los resultados como un array JSON
            $posts = array();
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $posts[] = $row;
                }
            }

            // Cerrar conexión
            $this->conn->close();
    
            return $posts;
        }

        public function add_post($content,$img='',$user){
    
            $sql = "INSERT INTO posts (
                content,
                img,
                creation_date,
                modification_date,
                user
            ) VALUES ('$content','$img',NOW(),NOW(),'$user')";

            $is_register;

            if ($this->conn->query($sql) === TRUE) {
                $is_register = 1;
            } else {
                $is_register = 0;
            }
            
            // Cerrar conexión
            // $this->conn->close();

            return $is_register;
    
        }

        public function get_latest_post($user){
            // Consulta para obtener todos los posts de un usuario
            $sql = "SELECT 
            posts.id as id_post,
            content,
            posts.img as img_post,
            creation_date,
            modification_date,
            posts.user as id_user,
            users.name,
            users.lastname,
            users.img as img_user
            FROM posts
            INNER JOIN users ON posts.user=users.id
            WHERE posts.user = $user
            ORDER BY posts.id DESC
            LIMIT 1";
            $result = $this->conn->query($sql);
    
            // Devolver los resultados como un array JSON
            $posts = array();
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $posts[] = $row;
                }
            }
    
            return $posts[0];

            // Cerrar conexión
            $this->conn->close();
        }

    }