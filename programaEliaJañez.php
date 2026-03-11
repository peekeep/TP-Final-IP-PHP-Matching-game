<?php 
include_once("memoria.php");

/****************************************/
/******* DATOS DE LOS INTEGRANTES *******/
/****************************************/

/* Apellido, Nombre. Legajo. Carrera. mail. Usuario Github */
/* ... COMPLETAR ... */

/* Elia, Luciano. FAI-3453. TUDW. luciano.elia@est.fi.uncoma.edu.ar. Usuario Github: peekeep
   Jañez, Matias Edgardo. FAI-6086. TUDW. matias.janez@est.fi.uncoma.edu.ar. Usuario Github: Jantute19 */

/****************************************/
/******* DEFINICION DE FUNCIONES ********/
/****************************************/

/**
 * Inicializa una estructura de datos con ejemplos de juegos y retorna la colección de juegos.
 * @return array
 */
function cargarJuegos() {
    $coleccionJuegos = [];
    $coleccionJuegos[0] = ["jugador1" => "BENJAMIN", "aciertos1" => 3, "jugador2" => "GUILLERMO", "aciertos2" => 1]; 
    $coleccionJuegos[1] = ["jugador1" => "CRISTIAN", "aciertos1" => 1, "jugador2" => "LUIS", "aciertos2" => 3];
    $coleccionJuegos[2] = ["jugador1" => "BENJAMIN", "aciertos1" => 2, "jugador2" => "LUIS", "aciertos2" => 2];
    $coleccionJuegos[3] = ["jugador1" => "CRISTIAN", "aciertos1" => 1, "jugador2" => "GUILLERMO", "aciertos2" => 3];
    $coleccionJuegos[4] = ["jugador1" => "LUIS", "aciertos1" => 3, "jugador2" => "GUILLERMO", "aciertos2" => 1];
    $coleccionJuegos[5] = ["jugador1" => "GUILLERMO", "aciertos1" => 2, "jugador2" => "LUIS", "aciertos2" => 2];
    $coleccionJuegos[6] = ["jugador1" => "BENJAMIN", "aciertos1" => 2, "jugador2" => "CRISTIAN", "aciertos2" => 2];
    $coleccionJuegos[7] = ["jugador1" => "CRISTIAN", "aciertos1" => 1, "jugador2" => "BENJAMIN", "aciertos2" => 3];
    $coleccionJuegos[8] = ["jugador1" => "LUIS", "aciertos1" => 3, "jugador2" => "BENJAMIN", "aciertos2" => 1];
    $coleccionJuegos[9] = ["jugador1" => "GUILLERMO", "aciertos1" => 3, "jugador2" => "CRISTIAN", "aciertos2" => 1];
    return $coleccionJuegos;
}

/**
 * Muestra las opciones del menú en la pantalla.
 * @return int
 */
function seleccionarOpcion() {
    echo "===============================\n";
    echo "        MENÚ DE OPCIONES       \n";
    echo "===============================\n";
    echo "1) Jugar a Memoria \n";
    echo "2) Mostrar un Juego \n";
    echo "3) Mostrar el Primer Juego Ganado \n";
    echo "4) Mostrar Porcentaje de Juegos Ganados \n";
    echo "5) Mostrar Resumen de Jugador \n";
    echo "6) Mostrar Listado de Juegos Ordenado por Jugador 2 \n";
    echo "7) Salir \n";
    echo "===============================\n";
    echo "Seleccione una opción: ";
    $opcion = solicitarNumeroEntre(1,7);
    return $opcion;
}

/**
 * Dado un indice ingresado por el usuario, muestra los datos del juego de dicho indice.
 * @param array $coleccionJuegos
 * @param int $indice
 */
function mostrarDatosJuego($coleccionJuegos, $indice) {
    // STRING $resultado
    if ($coleccionJuegos[$indice - 1]["aciertos1"] == $coleccionJuegos[$indice - 1]["aciertos2"]) {
        $resultado = "empate";
    } elseif ($coleccionJuegos[$indice - 1]["aciertos1"] > $coleccionJuegos[$indice - 1]["aciertos2"]) {
        $resultado = "gano jugador 1";
    } else {
        $resultado = "gano jugador 2";
    }
    echo "************************************** \n";
    echo "Juego Memoria: ".$indice." (".$resultado.") \n";
    echo "Jugador 1: ".$coleccionJuegos[$indice - 1]["jugador1"]." obtuvo ".$coleccionJuegos[$indice - 1]["aciertos1"]." aciertos \n";
    echo "Jugador 2: ".$coleccionJuegos[$indice - 1]["jugador2"]." obtuvo ".$coleccionJuegos[$indice - 1]["aciertos2"]." aciertos \n";
    echo "************************************** \n";
}

/** 
 * Dada la coleccion de juegos y un juego nuevo, retorna la coleccion con el juego nuevo incluido.
 * @param array $coleccionJuegos
 * @param array $juego
 * @return array
 */
function agregarJuego($coleccionJuegos, $juego) {
    // INT $nuevoIndice
    $nuevoIndice = count($coleccionJuegos);
    $coleccionJuegos[$nuevoIndice] = $juego;
    return $coleccionJuegos;
}

/**
 * Dado el nombre de un jugador, busca su primera victoria dentro de la coleccion de juegos, si la encuentra retorna el indice del juego, sino retorna -1.
 * @param array $coleccionJuegos
 * @param string $nombreJugador
 * @return int
 */
function primerJuegoGanado($coleccionJuegos, $nombreJugador) {
    // BOOLEAN $encontrado - INT $cantJuegos, $i
    $encontrado = false;
    $cantJuegos = count($coleccionJuegos);
    $i = 0;
    $valorRetornado = -1;
    while ($i < $cantJuegos && !($encontrado)) {
        if (($nombreJugador == $coleccionJuegos[$i]["jugador1"]) && ($coleccionJuegos[$i]["aciertos1"] > $coleccionJuegos[$i]["aciertos2"])) {
            $encontrado = true;
            $valorRetornado = $i;
        } elseif (($nombreJugador == $coleccionJuegos[$i]["jugador2"]) && ($coleccionJuegos[$i]["aciertos2"] > $coleccionJuegos[$i]["aciertos1"])) {
            $encontrado = true;
            $valorRetornado = $i;
        } 
        $i++;
    }
    return $valorRetornado;
}

/**
 * Dado el nombre de un jugador, retorna el resumen de datos de dicho jugador.
 * @param array $coleccionJuegos
 * @param string $nombreJugador
 * @return array
 */
function resumenJugador($coleccionJuegos, $nombreJugador) {
    // INT $cantJuegos, $juegosGanados, $juegosPerdidos, $juegosEmpatados, $aciertosAcumulados, $i
    $resumenJugador = [];
    $resumenJugador = ["nombre" => $nombreJugador];
    $cantJuegos = count($coleccionJuegos);
    $juegosGanados = 0;
    $juegosPerdidos = 0;
    $juegosEmpatados = 0;
    $aciertosAcumulados = 0;
    for ($i = 0; $i < $cantJuegos; $i++) {
        if (($nombreJugador == $coleccionJuegos[$i]["jugador1"]) && ($coleccionJuegos[$i]["aciertos1"] == $coleccionJuegos[$i]["aciertos2"])) {
            $juegosEmpatados++;
            $aciertosAcumulados = $aciertosAcumulados + $coleccionJuegos[$i]["aciertos1"];
        } elseif (($nombreJugador == $coleccionJuegos[$i]["jugador2"]) && ($coleccionJuegos[$i]["aciertos1"] == $coleccionJuegos[$i]["aciertos2"])) {
            $juegosEmpatados++;
            $aciertosAcumulados = $aciertosAcumulados + $coleccionJuegos[$i]["aciertos1"];
        } elseif (($nombreJugador == $coleccionJuegos[$i]["jugador1"]) && ($coleccionJuegos[$i]["aciertos1"] > $coleccionJuegos[$i]["aciertos2"])) {
            $juegosGanados++;
            $aciertosAcumulados = $aciertosAcumulados + $coleccionJuegos[$i]["aciertos1"];
        } elseif (($nombreJugador == $coleccionJuegos[$i]["jugador2"]) && ($coleccionJuegos[$i]["aciertos2"] > $coleccionJuegos[$i]["aciertos1"])) {
            $juegosGanados++;
            $aciertosAcumulados = $aciertosAcumulados + $coleccionJuegos[$i]["aciertos2"];
        } elseif (($nombreJugador == $coleccionJuegos[$i]["jugador1"]) && ($coleccionJuegos[$i]["aciertos1"] < $coleccionJuegos[$i]["aciertos2"])) {
            $juegosPerdidos++;
            $aciertosAcumulados = $aciertosAcumulados + $coleccionJuegos[$i]["aciertos1"];
        } elseif (($nombreJugador == $coleccionJuegos[$i]["jugador2"]) && ($coleccionJuegos[$i]["aciertos2"] < $coleccionJuegos[$i]["aciertos1"])) {
            $juegosPerdidos++;
            $aciertosAcumulados = $aciertosAcumulados + $coleccionJuegos[$i]["aciertos2"];
        }
    }
    $resumenJugador = ["juegosGanados" => $juegosGanados, "juegosPerdidos" => $juegosPerdidos, "juegosEmpatados" => $juegosEmpatados, "aciertosAcumulados" => $aciertosAcumulados];
    return $resumenJugador;
}

/**
 * Calcula y retorna la cantidad de juegos ganados, sin importar si fue el jugador 1 o 2.
 * @param array $coleccionJuegos
 * @return int
 */
function cantidadJuegosGanados($coleccionJuegos) {
    // INT $cantJuegos, $cantJuegosGanados, $i
    $cantJuegos = count($coleccionJuegos);
    $cantJuegosGanados = 0;
    for ($i = 0; $i < $cantJuegos; $i++) {
        if ($coleccionJuegos[$i]["aciertos1"] !== $coleccionJuegos[$i]["aciertos2"]) {
            $cantJuegosGanados++;
        }
    }
    return $cantJuegosGanados;
}

/**
 * Dado el numero de jugador ingresado por el usuario, calcula y retorna la cantidad de juegos ganados por el número ingresado.
 * @param array $coleccionJuegos
 * @param int $numeroJugador
 * @return int
 */
function cantidadJuegosGanadosJugador($coleccionJuegos, $numeroJugador) {
    // INT $cantJuegos, $i, $cantJuegosGanadosJugador
    $cantJuegos = count($coleccionJuegos);
    $cantJuegosGanadosJugador = 0;
    for ($i = 0; $i < $cantJuegos; $i++) {
        if (($numeroJugador == 1) && ($coleccionJuegos[$i]["aciertos1"] > $coleccionJuegos[$i]["aciertos2"])) {
            $cantJuegosGanadosJugador++;
        }
        if (($numeroJugador == 2) && ($coleccionJuegos[$i]["aciertos2"] > $coleccionJuegos[$i]["aciertos1"])) {
            $cantJuegosGanadosJugador++;
        }
    }
    return $cantJuegosGanadosJugador;
}

/**
 * Compara los valores dentro del arreglo cuya clave sea "jugador2".
 * @param array $a
 * @param array $b
 * @return int
 */
function comparacion($a, $b) {
    if($a["jugador2"] == $b["jugador2"]) {
        $orden = 0;
    } elseif ($a["jugador2"] < $b["jugador2"]) {
        $orden = -1;
    } else {
        $orden = 1;
    }
    return $orden;
}

/**
 * Muestra por pantalla la coleccion de juegos ordenada alfabeticamente por el jugador 2.
 * @param array $coleccionJuegos
 */
function mostrarJugador2($coleccionJuegos) {
    uasort($coleccionJuegos, "comparacion"); // uasort: Ordena array en el lugar de tal manera que la correlación entre las claves y los valores sea conservada, utilizando una función de comparación definida por el usuario.
    print_r($coleccionJuegos); // print_r: muestra por pantalla los datos del arreglo, de manera que sea legible.
}

/******************************************/
/*********** PROGRAMA PRINCIPAL ***********/
/******************************************/

//Declaración de variables:

/*
ARRAY $coleccionJuegos, $juego, $resumenJugador
INT $opcion, $cantidadJuegosTotales, $numeroJuego, $primeraVictoria, $numeroJugador, $totalGanados, $totalGanadosJugador
STRING $nombreJugador, $nombreJugadorResumen
FLOAT $porcentajeGanados
*/

//Proceso:
$coleccionJuegos = cargarJuegos();
do {
    $opcion = seleccionarOpcion();    
    switch ($opcion) {
        case 1:
            $juego = jugarMemoria();
            $juego["jugador1"] = strtoupper($juego["jugador1"]);
            $juego["jugador2"] = strtoupper($juego["jugador2"]);
            $coleccionJuegos = agregarJuego($coleccionJuegos, $juego);         
            break;
        case 2:
            echo "Ingrese un número de juego para ver: ";
            $cantidadJuegosTotales = count($coleccionJuegos);
            $numeroJuego = solicitarNumeroEntre(1, $cantidadJuegosTotales);
            mostrarDatosJuego($coleccionJuegos, $numeroJuego);
            break;
        case 3:
            echo "Ingrese el nombre de un jugador para ver su primer juego ganado: ";
            $nombreJugador = strtoupper(trim(fgets(STDIN)));
            $primeraVictoria = primerJuegoGanado($coleccionJuegos, $nombreJugador);
            if ($primeraVictoria == -1) {
                echo "El jugador ".$nombreJugador." no ganó ningún juego. \n";
            } else {
                mostrarDatosJuego($coleccionJuegos, $primeraVictoria + 1);
            }
            break;
        case 4:
            echo "Elija un número de jugador (1 o 2): ";
            $numeroJugador = solicitarNumeroEntre(1, 2);
            $totalGanados = cantidadJuegosGanados($coleccionJuegos);
            $totalGanadosJugador = cantidadJuegosGanadosJugador($coleccionJuegos, $numeroJugador);
            $porcentajeGanados = $totalGanadosJugador / $totalGanados * 100;
            echo "************************************** \n";
            echo "Jugador ". $numeroJugador. " ganó el " . round($porcentajeGanados, 2) . "% de los juegos ganados.\n";
            echo "************************************** \n";
            break;
        case 5:
            echo "Ingrese el nombre de un jugador para ver su historial de partidas: ";
            $nombreJugadorResumen = strtoupper(trim(fgets(STDIN)));
            $resumenJugador = resumenJugador($coleccionJuegos, $nombreJugadorResumen);
            if ($resumenJugador["aciertosAcumulados"] <= 0) {
                echo "No se encontraron registros del jugador ". $nombreJugadorResumen."\n";
            } else {
                echo "************************************** \n";
                echo "Jugador: ". $nombreJugadorResumen ."\n";
                echo "Ganó: ". $resumenJugador["juegosGanados"]. " juegos\n";
                echo "Perdió: ". $resumenJugador["juegosPerdidos"]. " juegos\n";
                echo "Empato: ". $resumenJugador["juegosEmpatados"]. " juegos\n";
                echo "Total de aciertos acumulados: ". $resumenJugador["aciertosAcumulados"]."\n";
                echo "************************************** \n";
            }
            break;
        case 6:
            mostrarJugador2($coleccionJuegos);
            break;
    }
} while ($opcion != 7);
echo "***************************************** \n";
echo "El juego ha sido finalizado correctamente \n";
echo "***************************************** \n";
?>