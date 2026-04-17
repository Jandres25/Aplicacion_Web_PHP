<section class="mt-4 mb-4">
    <h2>Lista Empleados</h2>

    <div class="card text-bg-light" style="margin-bottom: 3%;">
        <div class="card-header">
            <a name="" id="" class="btn btn-outline-primary" href="empleados-crear" role="button">Agregar Registro</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-hover" id="tabla_id">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Foto</th>
                            <th scope="col">CV</th>
                            <th scope="col">Puesto</th>
                            <th scope="col">Fecha Ingreso</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($lista_tbl_empleados as $registro) : ?>
                            <tr>
                                <td><?= $counter++; ?></td>
                                <td class="w-20">
                                    <?= $registro["Primernombre"]; ?> <?= $registro["Segundonombre"]; ?>
                                    <?= $registro["Primerapellido"]; ?> <?= $registro["Segundoapellido"]; ?>
                                </td>
                                <td>
                                    <?php if (!empty($registro["Foto"])) : ?>
                                        <?php $fotoPath = (string)$registro["Foto"]; ?>
                                        <?php if ($fotoPath !== '' && strpos($fotoPath, '/') === false) { $fotoPath = 'storage/uploads/' . $fotoPath; } ?>
                                        <img width="50" src="<?= htmlspecialchars($fotoPath, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded" alt="">
                                    <?php else : ?>
                                        <img width="50" src="https://via.placeholder.com/50" class="img-fluid rounded" alt="Foto no disponible">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($registro["CV"])) : ?>
                                        <?php $cvPath = (string)$registro["CV"]; ?>
                                        <?php if ($cvPath !== '' && strpos($cvPath, '/') === false) { $cvPath = 'storage/uploads/' . $cvPath; } ?>
                                        <a href="<?= htmlspecialchars($cvPath, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?= htmlspecialchars($cvPath, ENT_QUOTES, 'UTF-8'); ?></a>
                                    <?php else : ?>
                                        <span class="text-muted">CV no disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $registro["puesto"]; ?></td>
                                <td><?= $registro["Fecha"]; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fad fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownMenuButton">
                                            <li class="dropdown-item">
                                                <a href="empleados-carta-recomendacion?txtID=<?= $registro['ID']; ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Carta de recomendación">
                                                    <i class="fad fa-envelope-open-text fa-2x"></i>
                                                </a>
                                            </li>
                                            <li class="dropdown-item">
                                                <a href="empleados-editar?txtID=<?= $registro['ID']; ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Editar registro">
                                                    <i class="fad fa-edit fa-2x"></i>
                                                </a>
                                            </li>
                                            <li class="dropdown-item">
                                                <a href="javascript:borrar('empleados?txtID=<?= $registro['ID']; ?>');" data-bs-toggle="tooltip" data-bs-placement="right" title="Eliminar registro">
                                                    <i class="fad fa-trash fa-2x"></i>
                                                </a>
                                            </li>
                                        </ul>
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
