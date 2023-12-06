<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Clientes";
$idnegocio = $_SESSION['idnegocio'];
$sql = mysqli_query($conexion, "SELECT dp.Id_rol,dp.idusuario,p.idpermisos,p.nombre FROM detalle_permiso dp INNER JOIN permisos p WHERE dp.idusuario = '$id_user' AND dp.idpermisos = p.idpermisos AND p.nombre = '$permiso';");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Status: 301 Moved Permanently");
    header("Location: permisos.php");
    echo"<script language='javascript'>window.location='permisos.php'</script>;";
    exit();
}
if (!empty($_POST)) {
    if (empty($_POST['nombre']) ||empty($_POST['identificacion']) || empty($_POST['direccion']) 
    ||empty($_POST['telefono']) ||empty($_POST['correo']) || empty($_POST['id_negocio'])) {
        $alert = '<div class="alert alert-danger" role="alert">
        Todo los campos son obligatorios
        </div>';
    } else {
        $identificacion = $_POST['identificacion'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $id_negocio = $_POST['id_negocio'];
        $alert = "";
        
        $query_insert = mysqli_query($conexion,"INSERT INTO cliente(identificacion,nombre,direccion,telefono,correo, id_negocio) values ('$identificacion','$nombre', '$direccion','$telefono','$correo','$id_negocio')");;
            if ($query_insert) {
        if ($query_insert) {
            $alert = '<div class="alert alert-primary" role="alert">
                        Cliente registrado
                    </div>';
            header("Location: clientes.php");
            echo"<script language='javascript'>window.location='clientes.php'</script>;";
            exit();
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar
                </div>';
        }
    }
}
}

?>

<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#nueva_cliente"><i class="fas fa-plus"></i></button>
<div id="nueva_cliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Cliente</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <?php if($idnegocio != 1){?>
                        <div class="form-group">
                         <input type="hidden" name="id_negocio" id="id_negocio" value="<?php echo $idnegocio ?>">
                     </div>
                     <?php }else{?>
                        <div class="form-group">
                        <label for="id_negocio">Negocio</label>
                        <select class="form-control" name="id_negocio" id="id_negocio">
                            <option selected>Seleccione un Negocio</option>
                            <?php 
                            $querynego = mysqli_query($conexion, "SELECT * FROM negocio");
                            $negocio =  mysqli_num_rows($querynego);
                            if ($negocio > 0) {
                                while ($data = mysqli_fetch_assoc($querynego)) {
                                    ?>
                                    <option value="<?php echo $data['id_negocio'] ?>"><?php echo $data['nombre'] ?></option>
                                    <?php
                                }
                            }
                             ?>
                        </select>
                     </div>
                    <?php }?>
                    <div class="form-group">
                         <label for="nombre">Identificacion</label>
                         <input type="text" placeholder="Ingrese nombre del cliente" name="identificacion" id="identificacion" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="nombre">Nombre</label>
                         <input type="text" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="costo">Direccion</label>
                         <input type="text" placeholder="Ingrese un Direccion" name="direccion" id="direccion" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="talla">Telefono</label>
                         <input type="text" placeholder="Ingrese telefono" class="form-control" name="telefono" id="telefono">
                     </div>
                     <div class="form-group">
                         <label for="imagen">Correo</label>
                         <input type="text" placeholder="Seleccione correo" class="form-control" name="correo" id="correo">
                     </div>
                     <input type="submit" value="Guardar Cliente" class="btn btn-primary">
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
                 <th>Identificacion</th>
                 <th>Nombre</th>
                 <th>Direccion</th>
                 <th>Correo</th>
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

            if($idnegocio != 1){
                $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE id_negocio = $idnegocio ORDER BY estado DESC");
            }else{
                $query = mysqli_query($conexion, "SELECT * FROM cliente ORDER BY estado DESC");
            }
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
                         <td><?php echo $data['idCliente']; ?></td>
                         <td><?php echo $data['identificacion']; ?></td>
                         <td><?php echo $data['nombre']; ?></td>
                         <td><?php echo $data['direccion']; ?></td>
                         <td><?php echo $data['correo']; ?></td>
                         <td><?php echo $data['fecha_emision']; ?></td>
                        <td><?php echo $data['fecha_update']; ?></td>
                         <td><?php echo $estado ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <a href="editar_cliente.php?id=<?php echo $data['idCliente']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_cliente.php?id=<?php echo $data['idCliente']; ?>" method="post" class="confirmar d-inline">
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

<