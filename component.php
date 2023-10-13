<?php

//IMPORTAR CONFIGURACIONES GLOBALES.
require_once __DIR__ . "/Config/Global.php";
require_once __DIR__ . "/Config/Render.php";

//ENRUTADOR PARA EL MODULO (LO MAS SIMILAR A RUTAS DE LARAVEL)
$metodo = $_SERVER["REQUEST_METHOD"]; //GET
match ($metodo) {
    'GET' => match ($_GET["action"] ?? "profile") {
        "profile" => __profile($_GET)
    }
};

//FUNCION PARA RETORNAR LA VISTA DEL PERFIL DEL MODULO
function __profile($request)
{
    //Leer pagina actual
    $login = mb_strtolower($request["login"]);

    //Consulta de Registros
    $context = stream_context_create(
        array(
            "http" => array(
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36",
            ),
        )
    );

    $res = json_decode(file_get_contents("https://api.github.com/search/users?q=YOUR_NAME", false, $context));

    //Consultar el total de registros
    $total = count($res->items);
    $currentUser = [];
    $usuario = '';
    foreach ($res->items as $key => $value) {
        if (strpos(mb_strtolower($value->login), $login) !== false && ($key <= $total)) {
            $user = json_decode(file_get_contents("$value->url", false, $context));
            $data = [
                'created_at' => $user->created_at,
                'name' => $user->name,
                'html_url' => $user->html_url,
                'avatar_url' => $user->avatar_url
            ];
            array_push($currentUser, $data);
            break;
        }
    }

    //Retorno de vista con variables
    view('profile', compact('currentUser'));
}
