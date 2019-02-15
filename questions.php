<?php

/**
 * Question 1
 */

function equation($a, $b, $c)
{
    $d = ($b * $b) - ((4 * $a) * $c);

    $r1 = (-$b + sqrt($d)) / (2 * $a);
    $r2 = (-$b - sqrt($d)) / (2 * $a);

    if ($r1 != "NAN" and $r2 != "NAN")
    {
        return array("raiz 1" => $r1, "raiz 2" => $r2);
    } else {
        return "Valor de delta negativo!";
    }
}

/**
 * Question 2
 * Em Javascript, o operador "==" compara somente o valor das váriaveis, já o operado "===" compara valor e tipo.
 */

/**
 * Question 3
 * 
 */
class Cliente{}
class Veiculo{}
class Funcionario{}
class Relatorio{}

/**
 * Question 4
 * 42
 */

/**
  * Question 5
  * Sim
  */

/**
 * Questio 6
 */
function sudoku()
{
    $matrix = array(); 

    while ( true ) {   
        for ($x = 1; $x <= 9; $x++) {
            $matrix[$x] = array();
            for ($y = 1; $y <= 9; $y++) {
                $matrix[$x][$y] = range(1, 9);
            }
        }
    
        for ($x = 1; $x <= 9; $x++) {
            for ($y = 1; $y <= 9; $y++) {
    
                $keys = array_keys($matrix[$x][$y]);
                if(count($keys) == 0){
                    continue 3;
                }
    
                $matrix[$x][$y] = $matrix[$x][$y][$keys[mt_rand(0, count($keys) - 1)]];
                $index = $matrix[$x][$y]-1;
    
                for ($z = 1; $z <= 9; $z ++) {
                    if ( is_array($matrix[$x][$z]) ) {
                        unset($matrix[$x][$z][$index]);
                    }
                    if ( is_array($matrix[$z][$y]) ) {
                        unset($matrix[$z][$y][$index]);
                    }
                }
    
                if ( $x <= 3 ) {
                    $w = 1;
                    $W = 3;
                } else if ( $x <= 6 ) {
                    $w = 4;
                    $W = 6;
                } else {
                    $w = 7;
                    $W = 9;
                }
    
                if ( $y <= 3 ) {
                    $z = 1;
                    $Z = 3;
                } else if ( $y <= 6 ) {
                    $z = 4;
                    $Z = 6;
                } else {
                    $z = 7;
                    $Z = 9;
                }
    
                for ( ; $w <= $W; $w++ ) {
                    for ( ; $z <= $Z; $z++ ) {
                        if (is_array($matrix[$w][$z])) {
                            unset($matrix[$w][$z][$index]);
                        }
                    }
                }
            }
        }
        break;
    }
    return $matrix;
}
