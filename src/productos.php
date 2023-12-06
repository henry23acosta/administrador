 <?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$idnegocio = $_SESSION['idnegocio'];
$permiso = "Productos";
$sql = mysqli_query($conexion, "SELECT dp.Id_rol,dp.idusuario,p.idpermisos,p.nombre FROM detalle_permiso dp INNER JOIN permisos p WHERE dp.idusuario = '$id_user' AND dp.idpermisos = p.idpermisos AND p.nombre = '$permiso';");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Status: 301 Moved Permanently");
    header("Location: permisos.php");
    echo"<script language='javascript'>window.location='permisos.php'</script>;";
    exit();
}
    if (!empty($_POST)) {
        $nombre = $_POST['nombre'];
        $costo = $_POST['costo'];
        $talla = $_POST['talla'];
        $imagen = $_POST['imagen'];
        $stock = $_POST['stock'];
        $idCategoria = $_POST['idCategoria'];
        $id_negocio = $_POST['id_negocio'];
        $alert = "";
        if (empty($nombre) || $costo <  0 || empty($talla) || empty($imagen) || $stock < 0 || empty($idCategoria) || empty($id_negocio)) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query_insert = mysqli_query($conexion,"INSERT INTO productos(nombre,costo,talla,imagen,stock, idCategoria, id_negocio) values ('$nombre', '$costo','$talla','$imagen', '$stock','$idCategoria','$id_negocio')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Producto Registrado
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
                }
        }
    }
    ?>
 <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button>
 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <th>#</th>
                 <th>Nombre</th>
                 <th>Costo</th>
                 <th>Talla</th>
                 <th>Imagen</th>
                 <th>Stock</th>
                 <th>Fecha_Emision</th>
                <th>Fecha Actualizacion</th>
                 <th>Estado</th>
                 <th></th>
             </tr>
         </thead>
         <tbody>
    <?php
    include "../conexion.php";

    if ($idnegocio != 1) {
        $query = mysqli_query($conexion, "SELECT p.*, i.urlimg FROM productos p
                                          INNER JOIN productos_has_imagen phi ON p.idProductos = phi.idProductos
                                          INNER JOIN imagen i ON i.idimagen = phi.idimagen
                                          WHERE p.id_negocio = $idnegocio");
    } else {
        $query = mysqli_query($conexion, "SELECT p.*, i.urlimg FROM productos p
                                          INNER JOIN productos_has_imagen phi ON p.idProductos = phi.idProductos
                                          INNER JOIN imagen i ON i.idimagen = phi.idimagen");
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
                <td><?php echo $data['idProductos']; ?></td>
                <td><?php echo $data['nombre']; ?></td>
                <td><?php echo $data['costo']; ?></td>
                <td><?php echo $data['talla']; ?></td>
                <td><img src="https://www.appopular.me<?php echo $data['urlimg']; ?>" alt="" width="20px" height="20px"></td>
                <td><?php echo $data['stock']; ?></td>
                <td><?php echo $data['fecha_emision']; ?></td>
                <td><?php echo $data['fecha_update']; ?></td>
                <td><?php echo $estado ?></td>
                <td>
                    <?php if ($data['estado'] == 1) { ?>
                        <a href="editar_producto.php?id=<?php echo $data['idProductos']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                        <form action="eliminar_producto.php?id=<?php echo $data['idProductos']; ?>" method="post" class="confirmar d-inline">
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
 <div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Nuevo Producto</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                        <label for="idCategoria">Categoria</label>
                        <select class="form-control" name="idCategoria" id="idCategoria">
                            <option selected>Seleccione una Categoria</option>
                            <?php 
                            if($idnegocio != 1){
                                $querycat = mysqli_query($conexion, "SELECT * FROM categoria WHERE estado = 1 AND id_negocio = $idnegocio");
                            }else{
                                $querycat = mysqli_query($conexion, "SELECT * FROM categoria WHERE estado = 1");
                            }
                           
                            $category =  mysqli_num_rows($querycat);
                            if ($category > 0) {
                                while ($data = mysqli_fetch_assoc($querycat)) {
                                    ?>
                                    <option value="<?php echo $data['idCategoria'] ?>"><?php echo $data['Nombre'] ?></option>
                                    <?php
                                }
                            }
                             ?>
                        </select>
                     </div>
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
                         <label for="nombre">Nombre</label>
                         <input type="text" placeholder="Ingrese nombre del producto" name="nombre" id="nombre" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="costo">Costo</label>
                         <input type="text" placeholder="Ingrese un costo" name="costo" id="costo" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="talla">Talla</label>
                         <input type="text" placeholder="Ingrese Talla" class="form-control" name="talla" id="talla">
                     </div>
                     <div class="form-group">
                         <label for="imagen">Imagen</label>
                         <input type="text" placeholder="Seleccione Imagen" class="form-control" name="imagen" id="imagen">
                     </div>
                     <div class="form-group">
                         <label for="stock">Stock</label>
                         <input type="number" placeholder="Ingrese Stock" class="form-control" name="stock" id="stock">
                     </div>
                     <input type="submit" value="Guardar Producto" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php include_once "includes/footer.php"; ?>
