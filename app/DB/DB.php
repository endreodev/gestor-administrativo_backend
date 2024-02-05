<?php

namespace App\DB;

use PDO;
use PDOException;

require_once 'config.php';

class DB {

    private static $instance;

    public static function getInstance(){

        if(!isset(self::$instance)){

            try {
                // Use o namespace completo para a classe PDO
                self::$instance = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                                
            } catch (PDOException $e) {
                            
                echo  '<div class="alert alert-danger alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <h5><i class="icon fa fa-check"></i>Ola, tudo bem.</h5>
                          Houve falha na conexão!
                        </div>';
            }

        }

        return self::$instance;
    }
    
    public static function prepare($sql){
        return self::getInstance()->prepare($sql);
    }

} 