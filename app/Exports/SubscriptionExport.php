<?php

namespace App\Exports;
use App\Models\UserSubscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;

class SubscriptionExport implements FromCollection,WithHeadings
{
    use Exportable;

    public function __construct(int $deviceInfoId)
    {
        $this->deviceInfoId = $deviceInfoId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    // use Exportable;

    public function headings(): array
    {
        return [
            'ReceiptToken',
            'startDate',
            'endDate',
            'OS'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UserSubscription::select('receipt_base64_data', 'start_date', 'end_date', 'type')->where('device_info_id', $this->deviceInfoId)->get();
    }


}
