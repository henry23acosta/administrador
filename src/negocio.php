<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Negocios";
$sql = mysqli_query($conexion, "SELECT dp.Id_rol,dp.idusuario,p.idpermisos,p.nombre FROM detalle_permiso dp INNER JOIN permisos p WHERE dp.idusuario = '$id_user' AND dp.idpermisos = p.idpermisos AND p.nombre = '$permiso';");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Status: 301 Moved Permanently");
    header("Location: permisos.php");
    echo"<script language='javascript'>window.location='permisos.php'</script>;";
    exit();
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) ||  empty($_POST['ruc']) || empty($_POST['direccion']) || empty($_POST['telefono']) || empty($_POST['correo'])  ) {
        $alert = '<div class="alert alert-danger" role="alert">
        Todo los campos son obligatorios
        </div>';
    } else {
        $nombre = $_POST['nombre'];
        $ruc = $_POST['ruc'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];

        $query_insert = mysqli_query($conexion, "INSERT INTO negocio(nombre,ruc,direccion,telefono,correo) values ('$nombre','$ruc', '$direccion','$telefono','$correo')");
        if ($query_insert) {
            $alert = '<div class="alert alert-primary" role="alert">
                        Negocio registrado
                    </div>';
            header("Location: negocio.php");
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar
                </div>';
        }
    }
}
?>
<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#nueva_negocio"><i class="fas fa-plus"></i></button>
<div id="nueva_negocio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Negocio</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="correo">Ruc</label>
                        <input type="text" class="form-control" placeholder="Ingrese un Ruc" name="ruc" id="ruc">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Direccion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion">
                    </div>
                    <div class="form-group">
                        <label for="correo">Telefono</label>
                        <input type="text" class="form-control" placeholder="Ingrese una Telefono" name="telefono" id="telefono">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Correo</label>
                        <input type="text" class="form-control" placeholder="Ingrese Correo" name="correo" id="correo">
                    </div>
                    <input type="submit" value="Registrar" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Ruc</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Fecha_Emision</th>
                <th>Fecha Actualizacion</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php ?>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM negocio ORDER BY id_negocio DESC");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    if ($data['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                    } else {
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                    }
            ?>
                    <tr>

                        <td><?php echo $data['id_negocio']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['ruc']; ?></td>
                        <td><?php echo $data['direccion']; ?></td>
                        <td><?php echo $data['telefono']; ?></td>
                        <td><?php echo $data['fecha_emision']; ?></td>
                        <td><?php echo $data['fecha_update']; ?></td>
                        <td><?php echo $estado ?></td>
                        <td>
                        <?php if ($data['estado'] == 1) { ?>
                                <a href="editar_negocio.php?id=<?php echo $data['id_negocio']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_negocio.php?id=<?php echo $data['id_negocio']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                         <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<?php include_once "includes/footer.php"; ?>