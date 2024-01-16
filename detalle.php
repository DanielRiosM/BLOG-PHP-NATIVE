<?php include("includes/header_front.php") ?>

<?php 

    $baseDatos = new Basemysql();
    $db = $baseDatos->connect();

    if (isset($_GET["id"])) {
        $idArticulo = $_GET["id"];
    }

     //Instancimos el objeto
    $articulo = new Articulos($db);
    $resultado = $articulo->leer_individual($idArticulo);



    //Instanciar los comentario para este artículo
    $comentarios = new Comentario($db);
    $resultado2 = $comentarios->leerPorId($idArticulo);



  //Crear comentario
    if (isset($_POST['enviarComentario'])) {
        //Obtener los valores
        $idArticulo = $_POST['articulo'];
        $email = $_POST['usuario'];
        $comentario = $_POST['comentario'];

        if (empty($email) || $email == '' || empty($comentario) || $comentario == '') {
            $error = "Error, algunos campos están vacíos";
        }else{
            //Instanciamos el comentario
            $comentarioObj = new Comentario($db);

            if ($comentarioObj->crear($email, $comentario, $idArticulo)) {
                $mensaje = "Comentario creado correctamente";
                echo ("<script>location.href = '".RUTA_FRONT."'</script>");
            }else{
                $error = "Error, no se pudo crear el comentario"; 
            }
        }
    }




?>



<div class="row">

</div>

<div class="container-fluid">

    <div class="row">

        <div class="row">
            <div class="col-sm-12">

            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h1><?php echo $resultado->titulo; ?></h1>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid img-thumbnail"
                            src="<?php echo RUTA_FRONT; ?>img/articulos/<?php echo $resultado->imagen; ?>">
                    </div>

                    <p><?php echo $resultado->texto; ?></p>

                </div>
            </div>
        </div>
    </div>


    <div class="row">

        <div class="col-sm-6 offset-3">
            <form method="POST" action="">
                <input type="hidden" name="articulo" value="<?php echo $idArticulo; ?>">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" name="usuario" id="usuario" value="carlos445@gmail.com">
                </div>

                <div class="mb-3">
                    <label for="comentario">Comentario</label>
                    <textarea class="form-control" name="comentario" style="height: 200px"></textarea>
                </div>

                <br />
                <button type="submit" name="enviarComentario" class="btn btn-primary w-100"><i
                        class="bi bi-person-bounding-box"></i> Crear Nuevo Comentario</button>
            </form>
        </div>
    </div>

</div>

<div class="row">
    <h3 class="text-center mt-5">Comentarios</h3>

    <?php foreach($resultado2 as $comentario) : ?>

    <h4><i class="bi bi-person-circle"></i><?php echo $comentario->nombre_usuario; ?></h4>
    <p><?php echo $comentario->comentario; ?></p>
    <?php endforeach; ?>

</div>

</div>
<?php include("includes/footer.php") ?>