<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Categorias";
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
    $alert = "";
    if (empty($_POST['Nombre']) || empty($_POST['Descripcion']) || empty($_POST['id_negocio'])) {
        $alert = '<div class="alert alert-danger" role="alert">
        Todo los campos son obligatorios
        </div>';
    } else {
        $Nombre = $_POST['Nombre'];
        $Descripcion = $_POST['Descripcion'];
        $id_negocio = $_POST['id_negocio'];
        
        $query_insert = mysqli_query($conexion, "INSERT INTO categoria(Nombre,Descripcion,id_negocio) values ('$Nombre','$Descripcion', $id_negocio)");
        if ($query_insert) {
            $alert = '<div class="alert alert-primary" role="alert">
                        Usuario registrado
                    </div>';
            header("Location: categoria.php");
            echo"<script language='javascript'>window.location='categoria.php'</script>;";
            exit();
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar
                </div>';
        }
    }
}
?>
<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#nueva_categoria"><i class="fas fa-plus"></i></button>
<div id="nueva_categoria" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nueva Categoria</h5>
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
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="Nombre" id="Nombre">
                    </div>
                    <div class="form-group">
                        <label for="correo">Descripcion</label>
                        <input type="text" class="form-control" placeholder="Ingrese una Descripcion" name="Descripcion" id="Descripcion">
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
                <th>Descripcion</th>
                <th>Fecha emision</th>
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
                $query = mysqli_query($conexion, "SELECT * FROM categoria WHERE id_negocio = $idnegocio ORDER BY estado DESC");
            }else{
                $query = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY estado DESC");
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

                        <td><?php echo $data['idCategoria']; ?></td>
                        <td><?php echo $data['Nombre']; ?></td>
                        <td><?php echo $data['Descripcion']; ?></td>
                        <td><?php echo $data['fecha_emision']; ?></td>
                        <td><?php echo $data['fecha_update']; ?></td>
                        
                        <td><?php echo $estado; ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <a href="editar_categoria.php?id=<?php echo $data['idCategoria']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_categoria.php?id=<?php echo $data['idCategoria']; ?>" method="post" class="confirmar d-inline">
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