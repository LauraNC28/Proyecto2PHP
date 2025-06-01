<?php

/**
 * Clase Database
 * 
 * Esta clase contiene un único método estático que establece una conexión
 * con la base de datos MySQL utilizando la extensión `mysqli`.
 */
class Database {

    /**
     * Establece y devuelve una conexión a la base de datos MySQL.
     * 
     * @return mysqli Objeto de conexión a la base de datos.
     */
    public static function connect(){
		
        // Crear una nueva instancia de mysqli para conectarse al servidor MySQL
        // Parámetros: host, usuario, contraseña, nombre de la base de datos
        $db = new mysqli("localhost", "root", "", "tienda");

        // Establecer el conjunto de caracteres a UTF-8 para evitar problemas con tildes y caracteres especiales
        $db->query("SET NAMES 'utf8'");

        // Retorna el objeto de conexión
        return $db;
    }
}