<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['phone'])) return null;

        return new Contact([
            'name'  => $row['name'] ?? 'Senza nome',
            'phone' => $row['phone'],
        ]);
    }
}