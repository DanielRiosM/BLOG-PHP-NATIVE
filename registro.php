<?php include("includes/header_front.php") ?>

<?php

$baseDatos = new Basemysql();
$db = $baseDatos->connect();

$usuario = new Usuario($db);

if(isset($_POST['registrarse'])){

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmarPassword = $_POST["confirmar_password"];

    if(empty($nombre) || $nombre =='' || empty($email) || $email == '' || empty($password) || $password =='' || empty($confirmarPassword) || $confirmarPassword == ''){
        $error = "Error, algunos campos estan vacios";
    }else{
        if($password != $confirmarPassword){
            $error = "Error, la contrasena y la confirmacion no coinciden";
        }else{
            $articulo = new Articulos($db);

            if($usuario->validar_email($email)){

                if($usuario->registrar($nombre, $email, $password)){
                    $mensaje = "Te has registrado correctamente, click en el boton acceder para ingresar";
                }else{
                    $error = "Error, no se pudo registrar";
                }

            }else{
                $error = "Error, este email ya se encuentra registrado";
            }
        }
    }
}
?>

<div class="container-fluid">
    <h1 class="text-center">Registro de Usuarios</h1>
    <div class="row">
        <div class="col-sm-6 offset-3">
            <div class="card">
                <div class="card-header">
                    Regístrate para poder comentar
                </div>
                <div class="card-body">
                    <form method="POST" action="">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" placeholder="Ingresa el email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" name="password" placeholder="Ingresa el password">
                        </div>

                        <div class="mb-3">
                            <label for="confirmarPassword" class="form-label">Confirmar password:</label>
                            <input type="password" class="form-control" name="confirmar_password" placeholder="Ingresa la confirmación del password">
                        </div>

                        <br />
                        <button type="submit" name="registrarse" class="btn btn-primary w-100"><i class="bi bi-person-bounding-box"></i> Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include("includes/footer.php") ?>