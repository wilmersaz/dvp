<?php

//IMPORTAR CONFIGURACIONES GLOBALES.
require_once __DIR__ . "/Config/Global.php";
require_once __DIR__ . "/Config/Render.php";

//ENRUTADOR PARA EL MODULO (LO MAS SIMILAR A RUTAS DE LARAVEL)
$method = $_SERVER["REQUEST_METHOD"]; //GET
match ($method) {
    'GET' => match ($_GET["action"] ?? "index") {
        "index" => __index($_GET)
    }
};

//FUNCION PARA RETORNAR LA VISTA PRINCIPAL DEL MODULO
function __index($request, $reload = false)
{
    // validacion por backend para evitar buscar la palabra doublevpartners por si el usuario altera el código
    $username = mb_strtolower($request["username"]);
    $error = 'No está permitido ingresar la palabra doublevpartners';
    if($request['username'] == 'doublevpartners')
    view('index',compact('error','username'));

    // validacion por backend para ingresar minimo 4 caracteres por si el usuario altera el código
    $error2 = 'Debe Ingresar mínimo 4 caracteres';
    if(strlen($request['username']) < 4)
    view('index',compact('error2','username'));

    //Leer pagina actual
    $page = $request["page"] ?? 1;
    $previousPage = ($page == 1) ? 1 : ($page - 1);
    $currentPage = $page;
    $nextPage = $page + 1;

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
    $users = [];
    foreach ($res->items as $key => $value) {
        if (strpos(mb_strtolower($value->login), $username) !== false && ($key <= $total)) {
            $data = [
                'login' => $value->login,
                'id' => $value->id,
                'html_url' => $value->html_url,
                'avatar_url' => $value->avatar_url
            ];
            array_push($users, $data);
        }
    }
    // conteo actual de registros con el filtro
    $filterCount = count($users);
    //variables para botones.
    $previousBtn = ($page == 1) ? 'disabled' : '';
    $nextBtn = ($page * 10 > $total) ? 'disabled' : '';

    //Retorno de vista con variables
    view('index', compact('reload', 'previousPage', 'currentPage', 'nextPage', 'total', 'previousBtn', 'nextBtn', 'username','users','filterCount'));
}