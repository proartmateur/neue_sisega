<?php

namespace Tests\Unit;

use App\Employee;
use Tests\TestCase;

class EmployeeTest extends TestCase
{

    public function test_not_duplicated_employee()
    {
        /*
         * ¿Qué hace único a un empleado?
         *
         * Nombre completo (Puede repetirse en padre-hijo u homologo en otros estados)
         * Fecha de nacimiento (descarta padre-hijo)
         * CURP (Puede estar vacío)
         * RFC (Puede estar vacío)
         * */
        $employee = [
            'name' => 'Isidro Hernandez Silva',
            'curp' => 'HESI710928HMNRLS010',
            'cell_phone' => '33232342490',
            'birthdate' => '1971-09-28',
            'clabe' => '0023757015728266050'
        ];


        $existent_filtered = $this->findExistentEmployeesDb($employee);
        $t = count($existent_filtered);

        $duplicated = $this->findDuplicatedEmployee($employee, $existent_filtered);
        $error_msg = "";
        if($duplicated['existents'] > 0) {
            $error_msg = $this->duplicatedEmployeeErrorMessage($duplicated);
        }

        $this->assertGreaterThanOrEqual(1, $duplicated['existents']);
        $this->assertGreaterThanOrEqual(1, count($duplicated['fields']));
        $this->assertTrue(true);
    }

    private function duplicatedEmployeeErrorMessage(array $duplicated)
    {
        $fields = $duplicated['fields'];
        $total = $duplicated['existents'];
        $result = "Existen $total empleados con la misma información en los siguientes campos: ";

        foreach ($fields as $field) {
            $fname = $this->renameEmployeeEfieldForDisplay($field);
            $result .= "$fname, ";
        }
        $result = rtrim($result, ', ');
        return $result;
    }

    private function renameEmployeeEfieldForDisplay(string $field)
    {
        $result = "";
        switch ($field) {
            case 'name':
                $result = 'Nombre';
                break;

            case 'curp':
                $result = 'CURP';
                break;

            case 'cell_phone':
                $result = 'Celular';
                break;

            case 'birthdate':
                $result = 'Fecha De Nacimiento';
                break;

            case 'clabe':
                $result = 'CLABE';
                break;

            default:
                $result = $field;
                break;
        }
        return $result;
    }

    private function findDuplicatedEmployee(array $employee, $existents)
    {
        $total = count($existents);
        if ($total === 0) {
            return [
                'existents' => 0,
                'fields' => []
            ];
        }
        $fields = [];
        $total = 0;
        foreach ($existents as $existent) {
            $other = $this->employeeToArray($existent);
            $fields = array_merge($fields, $this->compareEmployees($employee, $other));
            if (count($fields) > 0) {
                $total += 1;
            }
        }
        return [
            'existents' => $total,
            'fields' => array_unique($fields)
        ];
    }

    private function compareEmployees(array $employee, array $other)
    {
        $repeated_fields = [];

        foreach ($employee as $key => $value) {
            if ($employee[$key] === $other[$key]) {
                $repeated_fields[] = $key;
            }
        }
        return $repeated_fields;
    }

    private function employeeToArray(Employee $employee)
    {
        return $employee->toArray();
    }

    private function findExistentEmployeesDb(array $employee)
    {
        return Employee::where('name', 'like', '%' . $employee['name'] . '%')
            ->orWhere('curp', 'like', '%' . $employee['curp'] . '%')
            ->orWhere('birthdate', 'like', '%' . $employee['birthdate'] . '%')
            ->orWhere('cell_phone', 'like', '%' . $employee['cell_phone'] . '%')
            ->get();
    }
}
