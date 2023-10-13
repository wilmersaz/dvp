<?php

//GENERA LA RUTA ABSOLUTA DE LA VISTA
    function parse($ruta){

        //Validar el formato
        if (strpos($ruta, "/") !== false || strpos($ruta, ".view") !== false || strpos($ruta, ".php") !== false) {
            throw new Exception("El formato de la vista es incorrecto", 500);
        }

        //Estandar de verificacion
        $rutaArray = explode(".", $ruta);
        $rutaPrepara = implode("/", $rutaArray) . ".view.php";

        //Validar si existe el archivo
        $rutaAbsoluta = __DIR__ ."/../Views/" . $rutaPrepara;

        if (is_file($rutaAbsoluta)) {
            return $rutaAbsoluta;
        } else {
            throw new Exception("La vista $ruta no existe", 500);
        }
    }

//EXTRAE LOS DIFERENTES COMPONENTES DE LA VISTA
function compose($rutaParsdeada){

    $contenido = file_get_contents($rutaParsdeada);

    // se definen las estructura que debe traer la plantilla y el slot mediante expresión regular
    $patronTemplate = '/<template-(.*?)>(.*?)<\/template-(.*?)>/s';
    $patronSubSlots = '/<slot-(.*?)>(.*?)<\/slot-(.*?)>/s';

    if(preg_match_all($patronTemplate, $contenido, $matches)){

        //Extraer parametros;
        $origenParametros = $matches[1][0];
        $origenParametros = preg_replace('/\s+/'," ", $origenParametros);
        $origenParametros = str_replace('"', "", $origenParametros);
        $arregloParametros = explode(" ", $origenParametros);

        //Nombre del template
        $template = $arregloParametros[0];
        unset($arregloParametros[0]);

        $parametros = [];
        foreach ($arregloParametros as $key => $param) {
            $data = explode("=", $param);
            $parametros = array_merge($parametros, [
                $data[0] => $data[1]
            ]);
        }
        
        //Guardar el Slot
        $slot = $matches[2][0];

        if(preg_match_all($patronSubSlots, $slot, $matchesSlots)){
            foreach ($matchesSlots[0] as $key => $search) {
                $slot = str_replace($search, "", $slot);
            }
            $subSlots = [];
            foreach ($matchesSlots[1] as $index => $nameSlot) {
                $subSlots = array_merge($subSlots, [
                    $nameSlot => $matchesSlots[2][$index]
                ]);
            }
        } 
    }
    
    return [
        'template' => $template ?? null,
        'params' => $parametros ?? [],
        'slot' => $slot ?? null,
        'subSlots' => $subSlots ?? [],
    ];       
}

// RETORNA LA RUTA ABSOLUTA DEL LAYOUT O TEMPLATE
function layout($ruta){
    
    $ruta = str_replace("-", "/", $ruta) . ".view.php";
    $rutaAbsoluta = __DIR__ ."/../Views/" . $ruta;

    if (is_file($rutaAbsoluta)) {
        return $rutaAbsoluta;
    } else {
        throw new Exception("El template $ruta no existe", 500);
    }
}

// FUNCION PRINCIPAL PARA GENERAR LA VISTA
function view(string $ruta, array $variables = []){

    //Ruta Parseada de la vista
    $rutaParsdeada = parse($ruta);

    //Valores extraidos
    $compose = compose($rutaParsdeada);

    //Ruta del layout
    $layout = layout($compose['template']);

    //Extraer variables enviadas desde la vista
    extract($variables);

    //Renderizar Slot
    ob_start();
    eval('?>' . $compose['slot']);
    $slot = ob_get_clean();

    //Extaer los subslots
    extract($compose["subSlots"]);

    //Props
    $props = $compose['params'];
    
    //Renderizar Vista Final
    ob_start();
    require_once($layout);
    echo ob_get_clean();
}