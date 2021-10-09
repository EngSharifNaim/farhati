<?php

namespace App\Imports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\ToModel;

class AccountImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Account([
            'area'     => $row[0],
            'account_id'    => $row[1],
            'customer_name'    => $row[2],
            'machine_id'    => $row[3],
            'status'    => $row[4],
            'type'    => $row[5],
            'last_read'    => $row[6],

        ]);
    }
}
