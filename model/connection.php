<?php

/**=====================================
    CONNECTION DATA BASE
======================================**/

// CONEXION HOST 
// class Connection{
//     static public function connect(){
//         try {
//             $servername = "localhost";
//             $user = "itechsol_administrator";
//             $password = "{_z;qn0j9,?{~b=j";
//             $conn = new PDO("mysql:host=$servername;dbname=itechsol_pos",$user,$password);
//             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             $conn->exec("set names utf8");
//             return $conn;
//         } catch (PDOException $e) {
//             print "¡Error!: " . $e->getMessage() . "<br/>";
//             die();
//         }
//     }
// }

// CONEXION LOCAL
class Connection{
    static public function connect(){
        try {
            $servername = "localhost";
            $user = "root";
            $password = "";
            $conn = new PDO("mysql:host=$servername;dbname=mantemaquinas",$user,$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("set names utf8");
            return $conn;
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}