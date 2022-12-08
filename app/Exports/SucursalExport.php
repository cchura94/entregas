<?php

namespace App\Exports;

use App\Sucursal;
use Maatwebsite\Excel\Concerns\FromCollection;

class SucursalExport implements FromCollection
{
    protected $tienda;
 
    public function __construct($tienda = null)
    {
        $this->tienda = $tienda;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Sucursal::where('tienda_id', $this->tienda->id)->get();
    }
}
