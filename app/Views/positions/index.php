<section class="mt-4 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center border-bottom gap-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-briefcase me-2 text-primary"></i>Listado de Puestos
            </h5>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm shadow-sm" href="puestos-crear" role="button">
                    <i class="fas fa-plus-circle me-1"></i> Agregar Puesto
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabla_id" style="visibility: hidden;">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center" style="width: 80px;">ID</th>
                            <th scope="col">Nombre del Puesto</th>
                            <th scope="col" class="text-center" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_tbl_puestos as $registro) : ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= htmlspecialchars((string)$registro['ID'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-id-badge text-primary"></i>
                                        </div>
                                        <span class="fw-medium"><?= htmlspecialchars((string)$registro['Nombredelpuesto'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a class="btn btn-outline-success btn-sm"
                                            href="puestos-editar?txtID=<?= urlencode((string)$registro['ID']); ?>"
                                            data-bs-toggle="tooltip"
                                            title="Editar Puesto">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm"
                                            onclick="borrar('puestos?txtID=<?= (int)$registro['ID']; ?>')"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar Puesto">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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
