<?php


/*
La librería Juego Memoria posee la definición de constantes y funciones necesarias
para jugar al Juego de la Memoria.
Puede ser utilizada por cualquier programador para incluir en sus programas.
*/



/*
EJEMPLO:

columnas:  1                2                3                 4

   --------------    --------------   --------------    --------------        filas:
   |            |    |            |   |            |    |            |        
A  |  [( $ )]   |    |  ><(((°>   |   | //\(oo)/\\ |    |   (^_^)    |          1
   |            |    |            |   |            |    |            |        
   --------------    --------------   --------------    --------------

   --------------    --------------   --------------    --------------
   |            |    |            |   |            |    |            |        
B  |  ><(((°>   |    | //\(oo)/\\ |   |  [( $ )]   |    |   (^_^)    |          2
   |            |    |            |   |            |    |            |        
   --------------    --------------   --------------    --------------


EJEMPLO ESTRUCTURAS:

tablero: (2x4)
[
   [ ["indiceFigura" => 1, "mostrarFigura" => false], [...], [...], [...] ], 
   [ ["indiceFigura" => 3, "mostrarFigura" => true], [...], [...], [...] ]
];


figuras:
[
   [
      "nombre" => "pez",
      "ascii" => '><(((°>',
      "largo" => 7
   ], 
   [
      ...
   ],
   ...
]


juego:
[
   "jugador1" => "Cris",
   "aciertos1" => 3,
   "jugador2" => "Luis",
   "aciertos2" => 1
]
*/



// ---------------------------------------------------------------------------------------------------------------------------



/**************************************/
/***** DEFINICION DE CONSTANTES *******/
/**************************************/

// Dimensiones del tablero:
const FILAS = 2;
const COLUMNAS = 4;
const CANT_CUADROS = FILAS * COLUMNAS;
// *** RESTRICCIONES: la cantidad de cuadros debe ser menor al doble de figuras para jugar


// Dimensión de un cuadro:
const LARGO_CUADRO = 14;
const ESPACIOS = "              "; //** igual a LARGO_CUADRO


// Datos de la Figura Vacia:
const FIGURA_VACIA = ["nombre" => "vacia", "ascii" => '', "largo" => 0];
const INDICE_FIGURA_VACIA = 0;





/**************************************/
/***** DEFINICION DE FUNCIONES ********/
/**************************************/


/**
 * Carga y retorna una estructura con todas las figuras.
 * Cantidad de figuras para jugar: 11 (no se cuenta la figura vacia)
 * @return array
 */
function cargarFiguras(){
   // array $figuras

   $figuras = [];

   $figuras[0] = FIGURA_VACIA; // ** figura vacia (indice cero)

   $figuras[1] = ["nombre" => "araña", "ascii" => '//\(oo)/\\\\', "largo" => 10];
   $figuras[2] = ["nombre" => "cara", "ascii" => '(^_^)', "largo" => 5];
   $figuras[3] = ["nombre" => "robot", "ascii" => 'd[o_o]b', "largo" => 7];
   $figuras[4] = ["nombre" => "billete", "ascii" => '[( $ )]', "largo" => 7];
   $figuras[5] = ["nombre" => "gato", "ascii" => '(=°.°=)', "largo" => 7];
   $figuras[6] = ["nombre" => "pez", "ascii" => '><(((°>', "largo" => 7];
   $figuras[7] = ["nombre" => "grito", "ascii" => '\_(\'o\')_/', "largo" => 9];
   $figuras[8] = ["nombre" => "vibora", "ascii" => '~~~~~"<', "largo" => 7];
   $figuras[9] = ["nombre" => "raton", "ascii" => '~(__">', "largo" => 6];
   $figuras[10] = ["nombre" => "rosa", "ascii" => '@)}--^--', "largo" => 8];
   $figuras[11] = ["nombre" => "dormir", "ascii" => '(-_-) zzz', "largo" => 9];

   return $figuras;
}


/**
 * Inicializa y retorna la estructura de un juego nuevo.
 * @return array
 */
function iniciarJuego() {
   return [
      "jugador1" => "",
      "aciertos1" => 0,
      "jugador2" => "",
      "aciertos2" => 0
   ];
}


/**
 * Inicializa y retorna el tablero con todos los cuadros vacios y ocultos.
 * @return array
 */
function iniciarTablero() {
   // array $tablero, $fila, $cuadro
   // int $f, $c

   $tablero = [];

   for($f = 0;  $f < FILAS;  $f++) {
      $fila = [];
      for($c = 0;  $c < COLUMNAS;  $c++){
         $cuadro = [];
         $cuadro["indiceFigura"] = INDICE_FIGURA_VACIA;
         $cuadro["mostrarFigura"] = false;
         $fila[$c] = $cuadro;
      }
      $tablero[$f] = $fila;
   }
   
   return $tablero;
}

/**
 * Dado el tablero y las figuras, carga el tablero de forma aleatoria.
 * Restricción: la cantidad de cuadros (filas * columnas), 
 *              no debe ser mayor a la cantidad de figuras para jugar (2 veces la cantidad de figuras). 
 * @param array $tablero
 * @param array $figuras
 * @return array
 */
function cargarTableroInicial($tablero, $figuras) {
   // int $cantFigurasSinVacia, $i, $indiceFigura, $filaCuadro, $columnaCuadro
   // array $indicesFigurasUtilizadas

   $cantFigurasSinVacia = count($figuras) - 1; // resto una por la figura vacia
   $indicesFigurasUtilizadas = [];

   if (CANT_CUADROS <= ($cantFigurasSinVacia * 2)) { // si tengo mas cuadros que figuras(x2) entra en un ciclo infinito

      for($i = 1;  $i <= CANT_CUADROS;  $i = $i + 2) {

         // figura
         do {
            $indiceFigura = rand(1, $cantFigurasSinVacia);
         } while (in_array($indiceFigura, $indicesFigurasUtilizadas));
         array_push($indicesFigurasUtilizadas, $indiceFigura);  // ** Investigar qué hace la función array_push() **

         // cuadro 1
         do {
            $filaCuadro = rand(0, FILAS-1);
            $columnaCuadro = rand(0, COLUMNAS-1);
         } while (!cuadroVacio($tablero, $filaCuadro, $columnaCuadro));
         $tablero = asignarFigura($tablero, $filaCuadro, $columnaCuadro, $indiceFigura);
         
         // cuadro 2
         if (!($i == CANT_CUADROS  &&  (CANT_CUADROS % 2) == 1)) { // último cuadro y cantidad de cuadros es impar
            do {
               $filaCuadro = rand(0, FILAS-1);
               $columnaCuadro = rand(0, COLUMNAS-1);
            } while (!cuadroVacio($tablero, $filaCuadro, $columnaCuadro));
            $tablero = asignarFigura($tablero, $filaCuadro, $columnaCuadro, $indiceFigura);
         }
      }
   
   } else {
      // ciclo infinito
   }

   return $tablero;
}


/**
 * Muestra por pantalla el tablero del juego.
 * @param array $tablero
 * @param array $figuras
 */
function mostrarTablero($tablero, $figuras) {
   // int $f, $c, $indiceFigura
   // string $figura

   // nros columnas
   dibujarEncabezado();
   
   for($f = 0;  $f < FILAS;  $f++){
      dibujarLinea();
      dibujarEspacios();

      // letras filas
      echo chr(65 + $f) . "   ";

      // dibujar figuras
      for($c = 0;  $c < COLUMNAS;  $c++){
         if ($tablero[$f][$c]["mostrarFigura"]) {
            $indiceFigura = $tablero[$f][$c]["indiceFigura"];
         } else {
            $indiceFigura = INDICE_FIGURA_VACIA;
         }
         $figura = agregarEspacios($figuras[$indiceFigura]);
         echo "|$figura|    ";
      }
      echo "\n";

      dibujarEspacios();
      dibujarLinea();
      echo "\n";
   }
}



/**
 * Dado el tablero, un jugador y número de turno. Solicita una letra y nro para un cuadro.
 * Retorna un arreglo con la fila y columna.
 * @param array $tablero
 * @param string $jugador
 * @param int $nroTurno
 * @param array 
 */
function elegirCuadro($tablero, $jugador, $nroTurno) {
   // bool $cuadroOcupado, $cuadroValido, $letraOk, $numeroOk
   // string $cuadro, $letraFila
   // int $fila, $columna

   echo "** Jugador " . $jugador . " - figura $nroTurno **\n";
   $cuadroOcupado = true;

   do {
      $cuadroValido = false;

      echo "Ingrese letra y número de un cuadro (ejemplo A1): ";
      $cuadro = strtoupper(trim(fgets(STDIN)));
      if (strlen($cuadro) == (1 + strlen(COLUMNAS))) {
         $letraFila = $cuadro[0];
         $fila = ord($letraFila) - 65; // ascii de A -> 65
         $letraOk = ($fila >= 0  &&  $fila < FILAS);
         
         $columna = substr($cuadro, 1);
         $numeroOk = is_numeric($columna)  &&  is_int($columna + 0)  &&  ($columna > 0)  &&  ($columna <= COLUMNAS);

         $cuadroValido = $letraOk && $numeroOk;
      }

      if ($cuadroValido) {
         $columna = $columna - 1;
         $cuadroOcupado = $tablero[$fila][$columna]["mostrarFigura"];
         if ($cuadroOcupado) {
            echo "El cuadro ". $letraFila .  ($columna+1) . " ya fue elegido.\n";
         }         
      } else {
         echo "El cuadro es invalido.\n";
      }
   
   } while($cuadroOcupado);

   return ["fila" => $fila, "columna" => $columna];
}




/**
 * Dado el tablero y una posición (fila, columna), verifica si un cuadro está vacio (sin figura).
 * @param array $tablero
 * @param int $fila
 * @param int $columna
 * @return bool
 */
function cuadroVacio($tablero, $fila, $columna) {
   return $tablero[$fila][$columna]["indiceFigura"] == INDICE_FIGURA_VACIA;
}

/**
 * Dado el tablero, una fila, una columna y un indice de una figura. 
 * Guarda el valor de la figura en el tablero. 
 * Retorna el tablero con la modificación.
 * @param array $tablero
 * @param int $fila
 * @param int $columna
 * @param bool $valor
 * @return array
 */
function asignarFigura($tablero, $fila, $columna, $indiceFigura) {
   $tablero[$fila][$columna]["indiceFigura"] = $indiceFigura;
   return $tablero;
}

/**
 * Dado el tablero, una fila, una columna y un valor (verdadero/falso). 
 * Cambia el valor para mostrar u ocultar una figura en el tablero. 
 * Retorna el tablero con la modificación.
 * @param array $tablero
 * @param int $fila
 * @param int $columna
 * @param bool $valor
 * @return array
 */
function cambiarMostrarFigura($tablero, $fila, $columna, $valor) {
   $tablero[$fila][$columna]["mostrarFigura"] = $valor;
   return $tablero;
}




/**
 * Solicita un valor y devuelve un número entero entre min y max.
 * @param int $min
 * @param int $max
 * @return int
 */
function solicitarNumeroEntre($min, $max) {
    //int $numero

    $numero = trim(fgets(STDIN));
    while (!(is_numeric($numero) && is_int($numero + 0) && ($numero >= $min && $numero <= $max))) {
        echo "Debe ingresar un número entre " . $min . " y " . $max . ": ";
        $numero = trim(fgets(STDIN));
    }

    return $numero;
}


/**
 * Muestra un saludo de inicio y solicita los nombres de los jugadores.
 * Guarda los nombre en minúscula y con la primera letra en mayúsculas.
 * @return array
 */
function solicitarNombres($juego) {

   limpiarPantalla();
   echo "************************************\n";
   echo "*** Bienvenido al juego Memoria  ***\n";
   echo "************************************\n\n";

   do {
      echo "Ingrese el nombre del jugador 1 que inicia el juego: ";
      $juego["jugador1"] = ucfirst(strtolower(trim(fgets(STDIN))));
      echo "Ingrese el nombre del jugador 2 que juega en segundo lugar: ";
      $juego["jugador2"] = ucfirst(strtolower(trim(fgets(STDIN))));
      
      if ($juego["jugador1"] == $juego["jugador2"]) {
         echo "Los nombres de los jugadores no puden ser iguales.\n\n";
      }

   } while ($juego["jugador1"] == $juego["jugador2"]);

   return $juego;
}


/**
 * Limpia la consola.
 */
function limpiarPantalla() {
   // ** Dependindo la versión del sistema operativo y de PHP, utilizar la función que mejor funcione **

   //shell_exec("cls");
   //shell_exec("clear");
   //system("cls");
   //pclose(popen('cls','w'));
   //for ($i = 0; $i < 50; $i++) echo "\r\n";
   //echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; // funciona bien
   //popen('clear', 'w'); // linux
   popen('cls', 'w'); // funciona bien en windows
}

/**
 * Rellena con espacios una figura para que quede centrada en el cuadro.
 * @param array $figura
 * @return string
 */
function agregarEspacios($figura) {
   // int $espaciosAntes, $espaciosDespues
   // string $figuraConEspacios

   $espaciosAntes = (int)( (LARGO_CUADRO - $figura["largo"]) / 2);
   $espaciosDespues = LARGO_CUADRO - $espaciosAntes - $figura["largo"];
   $figuraConEspacios = substr(ESPACIOS,0,$espaciosAntes) . $figura["ascii"] . substr(ESPACIOS,0,$espaciosDespues);
   
   return $figuraConEspacios;
}

/**
 * Muestra el encabezado del juego
 */
function dibujarEncabezado() {
   // int $espaciosAntes, $espaciosDespues, $c

   $espaciosAntes = ((int)((LARGO_CUADRO+2) / 2)) - 1;
   $espaciosDespues = LARGO_CUADRO - $espaciosAntes + 1;
   for($c = 0;  $c < COLUMNAS;  $c++) {
      echo "    ";
      echo substr(ESPACIOS,0,$espaciosAntes) . ($c + 1) . substr(ESPACIOS,0,$espaciosDespues);
   }
   echo "\n\n";
}

/**
 * Muestra lineas para dibujar los cuadros
 */
function dibujarLinea() {
   // int $c, $i

   for($c = 0;  $c < COLUMNAS;  $c++) {
      echo "    ";
      for($i = 0;  $i < (LARGO_CUADRO+2);  $i++) {
         echo "-";
      }
   }
   echo "\n";
}

/**
 * Muestra espacios para dibujar los cuadros
 */
function dibujarEspacios() {
   // int $c, $i

   for($c = 0;  $c < COLUMNAS;  $c++) {
      echo "    ";
      echo "|";
      for($i = 0;  $i < (LARGO_CUADRO+2)-2;  $i++) {
         echo " ";
      }
      echo "|";
   }
   echo "\n";
}





/**
 * Inicia y juega una partida del juego Memoria.
 * Retorna una estructura con los resultados del juego.
 * @return array estructura con el resultado de la partida, para poder ser utilizada en estadísticas.
 */
function jugarMemoria() {
   // array $figuras, $tablero, $juego, $cuadroLibre
   // int $cantFigurasSinVacia, $nroJugadorActual, $totalAciertos, $fila1, $columna1, $fila2, $columna2
   // string $nombreJugadorActual, $tecla

   $figuras = cargarFiguras();
   $tablero = iniciarTablero();
   $juego = iniciarJuego();

   $juego = solicitarNombres($juego);

   $cantFigurasSinVacia = count($figuras) - 1; // resto una por la figura vacia
   if (CANT_CUADROS <= ($cantFigurasSinVacia * 2)) { // verificar dimensiones del tablero

      $tablero = cargarTableroInicial($tablero, $figuras);
      $nroJugadorActual = 1;
      $totalAciertos = 0;
   
      do {
         limpiarPantalla();
         mostrarTablero($tablero, $figuras);
   
         $nombreJugadorActual = $nroJugadorActual == 1 ? $juego["jugador1"] : $juego["jugador2"];
         
         // una jugada tiene 2 turnos por jugador (figura 1 y figura 2)
         // turno 1
         $cuadroLibre = elegirCuadro($tablero, $nombreJugadorActual, 1);
         $fila1 = $cuadroLibre["fila"];
         $columna1 = $cuadroLibre["columna"];
         $tablero = cambiarMostrarFigura($tablero, $fila1, $columna1, true);
         limpiarPantalla();
         mostrarTablero($tablero, $figuras);
         echo $figuras[$tablero[$fila1][$columna1]["indiceFigura"]]["nombre"] . "\n"; // nombre figura
   
         // turno 2
         $cuadroLibre = elegirCuadro($tablero, $nombreJugadorActual, 2);
         $fila2 = $cuadroLibre["fila"];
         $columna2 = $cuadroLibre["columna"];
         $tablero = cambiarMostrarFigura($tablero, $fila2, $columna2, true);
         limpiarPantalla();
         mostrarTablero($tablero, $figuras);
         echo $figuras[$tablero[$fila2][$columna2]["indiceFigura"]]["nombre"] . "\n"; // nombre figura
   
         // verificar coincidencia
         $figura1 = $tablero[$fila1][$columna1]["indiceFigura"];
         $figura2 = $tablero[$fila2][$columna2]["indiceFigura"];
         if ($figura1 == $figura2) {
            echo "Coincidencia!\n";
            $totalAciertos = $totalAciertos + 1;

            if ($nroJugadorActual == 1) {
               $juego["aciertos1"] = $juego["aciertos1"] + 1;
            } else {
               $juego["aciertos2"] = $juego["aciertos2"] + 1;
            }
         } else {
            echo "No hubo coincidencia.\n";
            $tablero = cambiarMostrarFigura($tablero, $fila1, $columna1, false);
            $tablero = cambiarMostrarFigura($tablero, $fila2, $columna2, false);
         }
   
         $nroJugadorActual = ($nroJugadorActual == 1) ? 2 : 1; // cambia el jugador
   
         echo "Presione una tecla para continuar...";
         $tecla = fgets(STDIN);
   
      } while ((($totalAciertos * 2) + (CANT_CUADROS % 2)) < CANT_CUADROS);   
   
   } else {
      echo "** Error - Las dimensiones del tablero son mayores a la cantidad de figuras para jugar. **\n";
   }

   return $juego;
}


// ** PRUEBA **
// $juego = jugarMemoria();
// echo "jugador 1 " . $juego["jugador1"] . ": " . $juego["aciertos1"] . " aciertos" . "\n";
// echo "jugador 2 " . $juego["jugador2"] . ": " . $juego["aciertos2"] . " aciertos" . "\n";
// echo "Fin\n";