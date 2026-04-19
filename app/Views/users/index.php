<section class="mt-4 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center border-bottom gap-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-users-cog me-2"></i>Listado de Usuarios
            </h5>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm shadow-sm" href="usuarios-crear" role="button">
                    <i class="fas fa-user-plus me-1"></i> Crear Usuario
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabla_id" style="visibility: hidden;">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center" style="width: 50px;">#</th>
                            <th scope="col">Nombre del usuario</th>
                            <th scope="col">Correo Electrónico</th>
                            <th scope="col" class="text-center" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($lista_tbl_usuarios as $registro) : ?>
                            <tr>
                                <td class="text-center fw-bold"><?= $counter++; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <span class="fw-medium"><?= htmlspecialchars($registro['Nombreusuario'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        <i class="fas fa-envelope me-2 small"></i><?= htmlspecialchars($registro['Correo'], ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a class="btn btn-outline-success btn-sm"
                                            href="usuarios-editar?txtID=<?= urlencode($registro['ID']); ?>"
                                            data-bs-toggle="tooltip"
                                            title="Editar Usuario">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="delete-user-<?= (int)$registro['ID']; ?>" action="usuarios-eliminar" method="post" class="d-inline">
                                            <input type="hidden" name="txtID" value="<?= (int)$registro['ID']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="borrar('delete-user-<?= (int)$registro['ID']; ?>')"
                                                data-bs-toggle="tooltip"
                                                title="Eliminar Usuario">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
