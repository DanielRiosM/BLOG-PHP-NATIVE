<?php include("../includes/header.php") ?>

<?php

//intancias la BD y la conexion
$baseDatos = new Basemysql();
$db = $baseDatos->connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Instanciamos el objecto 
$comentario = new Comentario($db);
$resultado = $comentario->leer_individual($id);

if (isset($_POST['editarComentario'])) {

    $idComentario = $_POST["id"];
    $estado = $_POST["cambiarEstado"];

    $comentario = new Comentario($db);

    //crear comentario
    if ($comentario->actualizar($idComentario, $estado)) {
        $mensaje = "Comentario actualizado correctamente";
        header("Location:comentarios.php?mensaje=" . urldecode($mensaje));
    } else {
        $error = "Error, no se pudo actualizar";
    }
}

if (isset($_POST['borrarComentario'])) {

    $idComentario = $_POST["id"];

    $comentario = new Comentario($db);

    //crear comentario
    if ($comentario->borrar($idComentario)) {
        $mensaje = "Comentario borrado correctamente";
        header("Location:comentarios.php?mensaje=" . urldecode($mensaje));
    } else {
        $error = "Error, no se pudo actualizar";
    }
}
?>

<div class="row">
    <div class="col-sm-12">
        <?php if(isset($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $error; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <h3>Editar Comentario</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 offset-3">
        <form method="POST" action="">

            <input type="hidden" name="id" value="<?php echo $resultado->id_comentario; ?>">

            <div class="mb-3">
                <label for="texto">Texto</label>
                <textarea class="form-control" placeholder="Escriba el texto de su artículo" name="texto" style="height: 200px" readonly>
                <?php echo $resultado->comentario; ?>
                </textarea>
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" value="<?php echo $resultado->nombre_usuario; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="cambiarEstado" class="form-label">Cambiar estado:</label>
                <select class="form-select" name="cambiarEstado" aria-label="Default select example">
                    <option value="">--Seleccionar una opción--</option>
                    <option value="1">Aprobado</option>
                    <option value="0">No Aprobado</option>
                </select>
            </div>

            <br />
            <button type="submit" name="editarComentario" class="btn btn-success float-left"><i class="bi bi-person-bounding-box"></i> Editar Comentario</button>

            <button type="submit" name="borrarComentario" class="btn btn-danger float-right"><i class="bi bi-person-bounding-box"></i> Borrar Comentario</button>
        </form>
    </div>
</div>
<?php include("../includes/footer.php") ?>