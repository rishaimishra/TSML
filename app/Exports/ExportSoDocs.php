<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ScExcel;
use App\Models\SoTempExcel;

class ExportSoDocs implements FromCollection
{
	protected $contract_number;
	function __construct($contract_number) 
	{
	    $this->contract_number = $contract_number;
	} 
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        return SoTempExcel::whereIn('contract_number',explode(",",$this->contract_number))->get();
    }
}
