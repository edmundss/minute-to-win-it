<?php

namespace App;

use App\MeasurementUnit;
use App\Status;

class ConstantsService
{
	public function locations()
	{
		return [1=>'Nomā', 2 => 'pie klienta', 3=>'Servisā', 4=>'HILTI'];
	}

	public function task_statuses()
	{
		return [
			0 => 'unassigned (refused)',
            1 => 'assigned',
            2 => 'done',
            3 => 'closed',
            4 => 'cancelled',
		];
	}

	public function material_requisition_statuses()
	{
		return [
			0 => 'Melnraksts',
            1 => 'Iesniegts',
            2 => 'Tiek apstrādāts',
            3 => 'Pabeigts',
		];
	}
      
}