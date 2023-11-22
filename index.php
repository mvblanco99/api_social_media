<?php
    
    require_once 'vendor/autoload.php';

    // Ahora puedes utilizar la clase sin tener que incluir manualmente el archivo
    $objeto = new Api\ApiRoutes\Routes();
    $objeto->router();