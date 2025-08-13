<?php

/**=====================================
    CONNECTION DATA BASE
======================================**/

class Connection{
    static function connect(){
        try {
            $user = "root";
            $password = "";
            $link = new PDO("mysql:host=localhost;dbname=datanet_pos",$user,$password);
            $link->exec("set names utf8");
            $link = null;
        } catch (PDOException $th) {
            print "¡Error!: " . $th->getMessage() . "<br/>";
            die();
        }
    }
}