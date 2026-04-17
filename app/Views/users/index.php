<section class="mt-4 mb-4">
    <h2>Lista Usuarios</h2>

    <div class="card">
        <div class="card-header">
            <a name="" id="" class="btn btn-outline-primary" href="usuarios-crear" role="button">Agregar Registro</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-hover" id="tabla_id">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre del usuario</th>
                            <th scope="col">Contraseña</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_tbl_usuarios as $registro) { ?>
                            <tr>
                                <td><?= htmlspecialchars((string)$registro['ID'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars((string)$registro['Nombreusuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= str_repeat('*', strlen((string)$registro['Password'])); ?></td>
                                <td><?= htmlspecialchars((string)$registro['Correo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a name="btneditar" id="btneditar" class="btn btn-outline-info" href="usuarios-editar?txtID=<?= urlencode((string)$registro['ID']); ?>" role="button">Editar</a>
                                    <a name="" class="btn btn-outline-danger" href="javascript:borrar('usuarios?txtID=<?= (int)$registro['ID']; ?>');" role="button">Eliminar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
