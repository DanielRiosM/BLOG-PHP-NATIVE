<?php

class Articulos
{

    private $conn;
    private $table = 'articulos';

    //propiedades
    public $id;
    public $titulo;
    public $imagen;
    public $texto;
    public $fecha_creacion;

    //Constructor de nuestra clase
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Obtener los articulos 
    public function leer(){
        $query = 'SELECT id, titulo, imagen, texto, fecha_creacion FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $articulos = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $articulos;
    }

    public function leer_individual($id){
        $query = 'SELECT id, titulo, imagen, texto, fecha_creacion FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';

        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(1, $id);

        $stmt->execute();

        $articulos = $stmt->fetch(PDO::FETCH_OBJ);
        return $articulos;
    }

    public function crear($titulo, $NewImageName, $texto){
        $query = 'INSERT INTO ' . $this->table . ' (titulo, imagen, texto)VALUES(:titulo, :imagen, :texto)';

        //Preparar sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $NewImageName, PDO::PARAM_STR);
        $stmt->bindParam(":texto", $texto, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            printf("error $s\n", $stmt->error);
        }
    }

    public function actualizar($id, $titulo, $texto, $NewImageName){
        if ($NewImageName == "") {
            $query = 'UPDATE ' . $this->table . ' SET titulo = :titulo, texto = :texto WHERE id = :id';

            //Preparar sentencia
            $stmt = $this->conn->prepare($query);

            //Vincular parametro
            $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmt->bindParam(":texto", $texto, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            //Ejecutar query
            if ($stmt->execute()) {
                return true;
            }
        } else {
            $query = 'UPDATE ' . $this->table . ' SET titulo = :titulo, texto = :texto, imagen = :imagen WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            //Vincular parametro
            $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmt->bindParam(":texto", $texto, PDO::PARAM_STR);
            $stmt->bindParam(":imagen", $NewImageName, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
        }

        printf("error $s\n", $stmt->error);
    }

    public function borrar($idArticulo){

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $stmt ->bindParam(":id", $idArticulo, PDO::PARAM_INT);

        //Ejecutar query
        if($stmt->execute()){
            return true;
        }

        printf("error $s\n", $stmt->error);
        
    }
}
