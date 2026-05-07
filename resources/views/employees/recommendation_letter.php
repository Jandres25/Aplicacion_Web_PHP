<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carta de Recomendación – <?= htmlspecialchars($nombreCompleto); ?></title>
    <style><?= file_get_contents(__DIR__ . '/../../../public/css/recommendation_letter.css'); ?></style>
</head>

<body>

    <!-- Membrete -->
    <div class="letterhead">
        <div class="lh-left">
            <div class="lh-company">Gestión Empresarial S.A.</div>
            <div class="lh-tagline">Departamento de Recursos Humanos</div>
            <span class="lh-accent"></span>
        </div>
        <div class="lh-right">
            Av. San Martín N° 1250, Piso 3<br>
            Santa Cruz de la Sierra, Bolivia<br>
            Tel. +591 3 333-0000 &nbsp;·&nbsp; rrhh@gestion.com.bo
        </div>
    </div>

    <!-- Cuerpo -->
    <div class="body">

        <div class="ref-line">
            Ref. RH-<?= date('Y'); ?>-<?= rand(100, 999); ?> &nbsp;|&nbsp; <?= htmlspecialchars($fechaActual); ?>
        </div>

        <div class="date-line">
            Santa Cruz de la Sierra, <?= htmlspecialchars($fechaActual); ?>
        </div>

        <div class="salutation">
            <strong>A quien pueda interesarle:</strong>
        </div>

        <div class="subject">
            <div class="subject-label">Ref.</div>
            <div class="subject-text">
                Carta de Recomendación Laboral — <?= htmlspecialchars($nombreCompleto); ?>
            </div>
        </div>

        <div class="letter-body">
            <p>
                Reciba un cordial saludo de nuestra parte.
            </p>

            <p>
                Por medio de la presente, me dirijo a usted con el propósito de recomendar ampliamente
                al Sr./Sra. <strong><?= htmlspecialchars($nombreCompleto); ?></strong>, quien prestó
                sus servicios en nuestra empresa desde el <strong><?= $fechaIngreso->format('d/m/Y'); ?></strong>,
                desempeñándose en el cargo de <strong><?= htmlspecialchars($puesto); ?></strong>
                durante <strong><?php
                                $partes = [];
                                if ($diferencia->y > 0) {
                                    $partes[] = $diferencia->y . ' año' . ($diferencia->y !== 1 ? 's' : '');
                                }
                                if ($diferencia->m > 0) {
                                    $partes[] = $diferencia->m . ' mes' . ($diferencia->m !== 1 ? 'es' : '');
                                }
                                echo implode(' y ', $partes) ?: 'menos de un mes';
                                ?></strong>.
            </p>

            <p>
                Durante el tiempo que compartimos en esta organización, el Sr./Sra.
                <strong><?= htmlspecialchars($nombreCompleto); ?></strong> demostró ser una persona
                comprometida, responsable y con una conducta intachable. Su desempeño laboral fue siempre
                destacado, cumpliendo con puntualidad y eficiencia todas las tareas encomendadas,
                mostrando en todo momento una actitud proactiva y disposición para trabajar en equipo.
            </p>

            <p>
                Por las razones expuestas, extiendo la presente carta de recomendación sin reserva alguna,
                con la plena confianza de que el Sr./Sra.
                <strong><?= htmlspecialchars($nombreCompleto); ?></strong>
                continuará brindando su mejor esfuerzo y representando positivamente a cualquier
                organización que tenga la oportunidad de contar con sus servicios.
            </p>

            <p>
                Quedo a su disposición para cualquier consulta adicional al respecto.
            </p>
        </div>

        <!-- Cierre -->
        <div class="closing">
            <div class="closing-text">Atentamente,</div>
            <div class="signature-name">Ing. Juan Pablo Pérez</div>
            <div class="signature-title">Gerente de Recursos Humanos</div>
            <div class="signature-title">Gestión Empresarial S.A.</div>
        </div>

    </div><!-- /body -->


</body>

</html>