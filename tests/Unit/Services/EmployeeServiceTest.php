<?php

namespace Tests\Unit\Services;

use App\Domain\Contracts\EmployeeRepositoryInterface;
use App\Domain\Models\Employee;
use App\Infrastructure\EmployeeFileStorage;
use App\Services\EmployeeService;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EmployeeServiceTest extends TestCase
{
    private EmployeeRepositoryInterface&MockObject $repo;
    private EmployeeFileStorage&MockObject $storage;
    private EmployeeService $service;

    protected function setUp(): void
    {
        $this->repo    = $this->createMock(EmployeeRepositoryInterface::class);
        $this->storage = $this->createMock(EmployeeFileStorage::class);
        $this->service = new EmployeeService($this->repo, $this->storage);
    }

    private function validData(): array
    {
        return [
            'primernombre'    => 'Juan',
            'segundonombre'   => '',
            'primerapellido'  => 'Pérez',
            'segundoapellido' => 'López',
            'idpuesto'        => '3',
            'fechadeingreso'  => '2024-01-15',
        ];
    }

    private function makeEmployee(array $overrides = []): Employee
    {
        return Employee::fromRow(array_merge([
            'ID'              => '1',
            'Primernombre'    => 'Juan',
            'Segundonombre'   => '',
            'Primerapellido'  => 'Pérez',
            'Segundoapellido' => 'López',
            'Foto'            => 'foto.jpg',
            'CV'              => 'cv.pdf',
            'Idpuesto'        => '3',
            'Fecha'           => '2024-01-15',
            'puesto'          => 'Gerente',
        ], $overrides));
    }

    // --- listEmployees ---

    public function test_listEmployees_delegates_to_repository(): void
    {
        $employees = [$this->makeEmployee()];
        $this->repo->expects($this->once())->method('listAllWithPosition')->willReturn($employees);

        $result = $this->service->listEmployees();

        $this->assertSame($employees, $result);
    }

    // --- getEmployee ---

    public function test_getEmployee_returns_employee_from_repository(): void
    {
        $employee = $this->makeEmployee();
        $this->repo->expects($this->once())->method('findById')->with(1)->willReturn($employee);

        $this->assertSame($employee, $this->service->getEmployee(1));
    }

    public function test_getEmployee_returns_null_when_not_found(): void
    {
        $this->repo->method('findById')->willReturn(null);

        $this->assertNull($this->service->getEmployee(99));
    }

    // --- createEmployee: validation ---

    public function test_create_returns_failure_when_primer_nombre_missing(): void
    {
        $this->repo->expects($this->never())->method('create');

        $result = $this->service->createEmployee(
            array_merge($this->validData(), ['primernombre' => '']),
            [],
            '/tmp'
        );

        $this->assertFalse($result['success']);
    }

    public function test_create_returns_failure_when_primer_apellido_missing(): void
    {
        $this->repo->expects($this->never())->method('create');

        $result = $this->service->createEmployee(
            array_merge($this->validData(), ['primerapellido' => '']),
            [],
            '/tmp'
        );

        $this->assertFalse($result['success']);
    }

    public function test_create_returns_failure_when_idpuesto_invalid(): void
    {
        $this->repo->expects($this->never())->method('create');

        $result = $this->service->createEmployee(
            array_merge($this->validData(), ['idpuesto' => '0']),
            [],
            '/tmp'
        );

        $this->assertFalse($result['success']);
    }

    public function test_create_returns_failure_when_date_invalid(): void
    {
        $this->repo->expects($this->never())->method('create');

        $result = $this->service->createEmployee(
            array_merge($this->validData(), ['fechadeingreso' => '31/12/2024']),
            [],
            '/tmp'
        );

        $this->assertFalse($result['success']);
    }

    // --- createEmployee: file upload errors ---

    public function test_create_returns_failure_and_cleans_files_when_upload_error(): void
    {
        $this->storage->method('storeUploadedFile')
            ->willReturnCallback(function ($file, $dir, &$error, $exts) {
                $error = 'El tipo de archivo no está permitido.';
                return '';
            });

        $this->storage->expects($this->never())->method('deleteFileIfExists');
        $this->repo->expects($this->never())->method('create');

        $result = $this->service->createEmployee($this->validData(), ['foto' => ['error' => UPLOAD_ERR_OK]], '/tmp');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Foto:', $result['message']);
    }

    public function test_create_cleans_uploaded_photo_when_cv_upload_fails(): void
    {
        $call = 0;
        $this->storage->method('storeUploadedFile')
            ->willReturnCallback(function ($file, $dir, &$error, $exts) use (&$call) {
                $call++;
                if ($call === 1) {
                    return 'storage/uploads/foto.jpg'; // foto ok
                }
                $error = 'El tipo de archivo no está permitido.';
                return '';
            });

        $this->storage->expects($this->once())
            ->method('deleteFileIfExists')
            ->with('/tmp', 'storage/uploads/foto.jpg');

        $result = $this->service->createEmployee($this->validData(), [], '/tmp');

        $this->assertFalse($result['success']);
    }

    // --- createEmployee: defaults ---

    public function test_create_uses_default_filenames_when_no_files_uploaded(): void
    {
        $this->storage->method('storeUploadedFile')->willReturn('');

        $this->repo->expects($this->once())
            ->method('create')
            ->with($this->callback(function (array $data) {
                return $data['Foto'] === 'user-default.jpg' && $data['CV'] === 'cv_default.pdf';
            }))
            ->willReturn(true);

        $result = $this->service->createEmployee($this->validData(), [], '/tmp');

        $this->assertTrue($result['success']);
    }

    // --- createEmployee: repository failures ---

    public function test_create_returns_failure_when_repository_throws_PDOException(): void
    {
        $this->storage->method('storeUploadedFile')->willReturn('');
        $this->repo->method('create')->willThrowException(new PDOException('DB error'));

        $result = $this->service->createEmployee($this->validData(), [], '/tmp');

        $this->assertFalse($result['success']);
        $this->assertSame('No se pudo agregar el registro.', $result['message']);
    }

    public function test_create_returns_failure_when_repository_returns_false(): void
    {
        $this->storage->method('storeUploadedFile')->willReturn('');
        $this->repo->method('create')->willReturn(false);

        $result = $this->service->createEmployee($this->validData(), [], '/tmp');

        $this->assertFalse($result['success']);
    }

    public function test_create_returns_success_when_repository_succeeds(): void
    {
        $this->storage->method('storeUploadedFile')->willReturn('');
        $this->repo->method('create')->willReturn(true);

        $result = $this->service->createEmployee($this->validData(), [], '/tmp');

        $this->assertTrue($result['success']);
        $this->assertSame('Registro agregado', $result['message']);
    }

    public function test_create_trims_string_fields_before_persisting(): void
    {
        $this->storage->method('storeUploadedFile')->willReturn('');

        $this->repo->expects($this->once())
            ->method('create')
            ->with($this->callback(function (array $data) {
                return $data['Primernombre'] === 'Juan' && $data['Primerapellido'] === 'Pérez';
            }))
            ->willReturn(true);

        $this->service->createEmployee(
            array_merge($this->validData(), ['primernombre' => '  Juan  ', 'primerapellido' => '  Pérez  ']),
            [],
            '/tmp'
        );
    }

    // --- updateEmployee ---

    public function test_update_returns_failure_when_id_invalid(): void
    {
        $this->repo->expects($this->never())->method('findById');

        $result = $this->service->updateEmployee(0, $this->validData(), [], '/tmp');

        $this->assertFalse($result['success']);
    }

    public function test_update_returns_failure_when_employee_not_found(): void
    {
        $this->repo->method('findById')->willReturn(null);

        $result = $this->service->updateEmployee(99, $this->validData(), [], '/tmp');

        $this->assertFalse($result['success']);
        $this->assertSame('No se encontró el empleado a editar.', $result['message']);
    }

    public function test_update_keeps_current_files_when_no_new_uploads(): void
    {
        $existing = $this->makeEmployee(['Foto' => 'old_foto.jpg', 'CV' => 'old_cv.pdf']);
        $this->repo->method('findById')->willReturn($existing);
        $this->storage->method('storeUploadedFile')->willReturn('');

        $this->repo->expects($this->once())
            ->method('update')
            ->with($this->anything(), $this->callback(function (array $data) {
                return $data['Foto'] === 'old_foto.jpg' && $data['CV'] === 'old_cv.pdf';
            }))
            ->willReturn(true);

        $result = $this->service->updateEmployee(1, $this->validData(), [], '/tmp');

        $this->assertTrue($result['success']);
    }

    public function test_update_replaces_files_and_deletes_old_ones_on_success(): void
    {
        $existing = $this->makeEmployee(['Foto' => 'old_foto.jpg', 'CV' => 'old_cv.pdf']);
        $this->repo->method('findById')->willReturn($existing);
        $this->repo->method('update')->willReturn(true);

        $call = 0;
        $this->storage->method('storeUploadedFile')
            ->willReturnCallback(function () use (&$call) {
                $call++;
                return $call === 1 ? 'storage/uploads/new_foto.jpg' : 'storage/uploads/new_cv.pdf';
            });

        $deleted = [];
        $this->storage->method('deleteFileIfExists')
            ->willReturnCallback(function ($dir, $file) use (&$deleted) {
                $deleted[] = $file;
            });

        $this->service->updateEmployee(1, $this->validData(), [], '/tmp');

        $this->assertContains('old_foto.jpg', $deleted);
        $this->assertContains('old_cv.pdf', $deleted);
    }

    public function test_update_does_not_delete_old_files_when_repository_fails(): void
    {
        $existing = $this->makeEmployee(['Foto' => 'old_foto.jpg', 'CV' => 'old_cv.pdf']);
        $this->repo->method('findById')->willReturn($existing);
        $this->repo->method('update')->willReturn(false);

        $call = 0;
        $this->storage->method('storeUploadedFile')
            ->willReturnCallback(function () use (&$call) {
                $call++;
                return $call === 1 ? 'storage/uploads/new_foto.jpg' : 'storage/uploads/new_cv.pdf';
            });

        $deleted = [];
        $this->storage->method('deleteFileIfExists')
            ->willReturnCallback(function ($dir, $file) use (&$deleted) {
                $deleted[] = $file;
            });

        $result = $this->service->updateEmployee(1, $this->validData(), [], '/tmp');

        $this->assertFalse($result['success']);
        $this->assertNotContains('old_foto.jpg', $deleted);
        $this->assertNotContains('old_cv.pdf', $deleted);
    }

    public function test_update_returns_failure_when_repository_throws_PDOException(): void
    {
        $existing = $this->makeEmployee();
        $this->repo->method('findById')->willReturn($existing);
        $this->storage->method('storeUploadedFile')->willReturn('');
        $this->repo->method('update')->willThrowException(new PDOException('DB error'));

        $result = $this->service->updateEmployee(1, $this->validData(), [], '/tmp');

        $this->assertFalse($result['success']);
        $this->assertSame('No se pudo actualizar el registro.', $result['message']);
    }

    public function test_update_returns_success(): void
    {
        $existing = $this->makeEmployee();
        $this->repo->method('findById')->willReturn($existing);
        $this->storage->method('storeUploadedFile')->willReturn('');
        $this->repo->method('update')->willReturn(true);

        $result = $this->service->updateEmployee(1, $this->validData(), [], '/tmp');

        $this->assertTrue($result['success']);
        $this->assertSame('Registro actualizado', $result['message']);
    }

    // --- deleteEmployee ---

    public function test_delete_returns_false_when_id_invalid(): void
    {
        $this->repo->expects($this->never())->method('findFilesById');

        $result = $this->service->deleteEmployee(0, '/tmp');

        $this->assertFalse($result);
    }

    public function test_delete_removes_files_then_calls_repository(): void
    {
        $this->repo->method('findFilesById')->with(5)->willReturn(['Foto' => 'foto.jpg', 'CV' => 'cv.pdf']);
        $this->repo->method('deleteById')->with(5)->willReturn(true);

        $deletedFiles = [];
        $this->storage->method('deleteFileIfExists')
            ->willReturnCallback(function ($dir, $file) use (&$deletedFiles) {
                $deletedFiles[] = $file;
            });

        $result = $this->service->deleteEmployee(5, '/tmp');

        $this->assertTrue($result);
        $this->assertContains('foto.jpg', $deletedFiles);
        $this->assertContains('cv.pdf', $deletedFiles);
    }

    public function test_delete_still_calls_repository_when_files_not_found(): void
    {
        $this->repo->method('findFilesById')->willReturn(null);
        $this->repo->expects($this->once())->method('deleteById')->with(3)->willReturn(true);
        $this->storage->expects($this->never())->method('deleteFileIfExists');

        $result = $this->service->deleteEmployee(3, '/tmp');

        $this->assertTrue($result);
    }
}
