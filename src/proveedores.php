<?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$idnegocio = $_SESSION['idnegocio'];
$permiso = "Proveedores";
$sql = mysqli_query($conexion, "SELECT dp.Id_rol,dp.idusuario,p.idpermisos,p.nombre FROM detalle_permiso dp INNER JOIN permisos p WHERE dp.idusuario = '$id_user' AND dp.idpermisos = p.idpermisos AND p.nombre = '$permiso';");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Status: 301 Moved Permanently");
    header("Location: permisos.php");
    echo"<script language='javascript'>window.location='permisos.php'</script>;";
    exit();
}
    if (!empty($_POST)) {
        $identificacion = $_POST['identificacion'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono= $_POST['telefono'];
        $email = $_POST['email'];
        $Cuenta_bancaria = $_POST['Cuenta_bancaria'];
        $id_negocio = $_POST['id_negocio'];
        $alert = "";
        if (empty($identificacion) || empty($nombre) || empty($direccion) || empty($telefono) ||empty($email) ||empty($Cuenta_bancaria) || empty($id_negocio)) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query_insert = mysqli_query($conexion,"INSERT INTO proveedores(identificacion,nombre,direccion,telefono,email,Cuenta_bancaria, id_negocio) values ('$identificacion','$nombre', '$direccion','$telefono','$email','$Cuenta_bancaria','$id_negocio')");
                if ($query_insert) {
                    
                    $alert = '<div class="alert alert-success" role="alert">
                Proveedor Registrado
              </div>';
                } else {
                   
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el proveedor
              </div>';
                }
        }
    }
    ?>
 <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_proveedor"><i class="fas fa-plus"></i></button>
 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <th>#</th>
                 <th>Identificacion</th>
                 <th>Nombre</th>
                 <th>Telefono</th>
                 <th>Fecha_Emision</th>
                 <th>Fecha Actualizacion</th>
                 <th>Estado</th>
                 <th></th>
             </tr>
         </thead>
         <tbody>
             <?php
                include "../conexion.php";

                if($idnegocio != 1){
                    $query = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id_negocio = $idnegocio");
                }else{
                    $query = mysqli_query($conexion, "SELECT * FROM proveedores");
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
                        <td><?php echo $data['idProveedores']; ?></td>
                        <td><?php echo $data['identificacion']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['telefono']; ?></td>
                        <td><?php echo $data['fecha_emision']; ?></td>
                        <td><?php echo $data['fecha_update']; ?></td>
                        <td><?php echo $estado ?></td>
                         <td>
                             <?php if ($data['estado'] == 1) { ?>
                                 <a href="agregar_proveedor.php?id=<?php echo $data['idProveedores']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>

                                 <a href="editar_proveedor.php?id=<?php echo $data['idProveedores']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_proveedor.php?id=<?php echo $data['idProveedores']; ?>" method="post" class="confirmar d-inline">
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
 <div id="nuevo_proveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Nuevo Proveedor</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <?php if($idnegocio != 1){?>
                        <div class="form-group">
                         <input type="hidden" name="id_negocio" id="id_negocio" value="<?php echo $idnegocio ?>" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control">
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
                         <label for="identificacion">Identificacion</label>
                         <input type="text" placeholder="Ingrese Identificacion" name="identificacion" id="identificacion" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="nombre">Nombre</label>
                         <input type="text" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="direccion">Direccion</label>
                         <input type="text" placeholder="Ingrese ua direccion" name="direccion" id="direccion" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="telefono">Telefono</label>
                         <input type="text" placeholder="Ingrese Telefono" class="form-control" name="telefono" id="telefono">
                     </div>
                     <div class="form-group">
                         <label for="email">Email</label>
                         <input type="email" placeholder="Seleccione un Email" class="form-control" name="email" id="email">
                     </div>
                     <div class="form-group">
                         <label for="Cuenta_bancaria">Cuenta_bancaria</label>
                         <input type="text" placeholder="Ingrese Cuenta Bancaria" class="form-control" name="Cuenta_bancaria" id="Cuenta_bancaria">
                     </div>
                     <input type="submit" value="Guardar Proveedor" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>
 <?php include_once "includes/footer.php"; ?>
