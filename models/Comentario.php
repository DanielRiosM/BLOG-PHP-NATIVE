<?php

class Comentario
{

    private $conn;
    private $table = 'comentarios';

    //Propiedades
    public $id;
    public $comentario;
    public $estado;
    public $fecha_creacion;

    //Constructor de nuestra clase
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function leer()
    {
        $query = 'SELECT c.id AS id_comentario, c.comentario AS comentario, c.estado AS estado, c.fecha_creacion AS fecha, c.usuario_id, u.email 
        AS nombre_usuario, a.titulo AS titulo_articulo  FROM ' . $this->table . ' c LEFT JOIN usuarios u ON u.id = c.usuario_id LEFT JOIN articulos a ON a.id = c.articulo_id';

        $stmt = $this->conn->prepare($query);

        //Ejecutar query
        $stmt->execute();
        $comentarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $comentarios;
    }

    public function leer_individual($id)
    {
        $query = 'SELECT c.id AS id_comentario, c.comentario AS comentario, c.estado AS estado, c.fecha_creacion AS fecha, c.usuario_id, u.email 
        AS nombre_usuario, a.titulo AS titulo_articulo  FROM ' . $this->table . ' c LEFT JOIN usuarios u ON u.id = c.usuario_id LEFT JOIN articulos a ON a.id = c.articulo_id 
        WHERE c.id = ? LIMIT 0,1';

        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(1, $id);

        //Ejecutar query
        $stmt->execute();
        $comentario = $stmt->fetch(PDO::FETCH_OBJ);
        return $comentario;
    }

    public function actualizar($idComentario, $estado)
    {

        $query = 'UPDATE ' . $this->table . ' SET estado = :estado WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        //Vincular parametro
        $stmt->bindParam(":id", $idComentario, PDO::PARAM_INT);
        $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

        printf("error $s\n", $stmt->error);
    }

    public function borrar($idComentario)
    {

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $idComentario, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        printf("error $s\n", $stmt->error);
    }

    public function leerPorId($idArticulo)
    {
        $query = 'SELECT c.id AS id_comentario, c.comentario AS comentario, c.estado AS estado, c.fecha_creacion AS fecha, c.usuario_id, u.email AS nombre_usuario FROM '
            . $this->table . ' c INNER JOIN usuarios u ON u.id = c.usuario_id WHERE articulo_id = :articulo_id && estado = 1';

        //preparar sentencia
        $stmt = $this->conn->prepare($query);

        //vincular parametro
        $stmt->bindParam(":articulo_id", $idArticulo);

        //ejecutar query
        $stmt->execute();
        $comentarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $comentarios;
    }

    public function crear($email, $comentario, $idArticulo){

        //Obtener el id del usuario usando el email
        $query = "SELECT * FROM usuarios WHERE email = :email";

        //Preparar sentencia
        $stmt = $this->conn->prepare($query);

        //Vincular parámetro
        $stmt->bindParam(":email", $email);

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
        
        $idUsuario = $usuario->id;

        //Crear el query para la inserción del comentario
        $query2 = 'INSERT INTO ' . $this->table . ' (comentario, usuario_id, articulo_id, estado)VALUES(:comentario, :usuario_id, :articulo_id, 0)';

        //Preparar sentencia
        $stmt = $this->conn->prepare($query2);

        //Vincular parámetro
        $stmt->bindParam(":comentario", $comentario, PDO::PARAM_STR);
        $stmt->bindParam(":usuario_id", $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(":articulo_id", $idArticulo, PDO::PARAM_INT);

        //Ejecutar query
        if ($stmt->execute()) {
            return true;
        }

        //Si hay error 
        printf("error $s\n", $stmt->error);
        return false;
    }
}
