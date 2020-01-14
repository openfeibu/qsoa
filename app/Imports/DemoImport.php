<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;

class DemoImport
{
    use Importable;

    public function mapping(): array
    {
        return [
            'name'  => 'B1',
            'email' => 'B2',
        ];
    }

    public function model(array $row)
    {
        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
        ]);
    }
}