<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class leastOrderedExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $day, $start_date, $end_date;

    public function __construct($day, $start_date, $end_date)
    {
        $this->day = $day;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('merchants', 'merchants.id', '=', 'products.merchant_id')
            ->select('products.name', 'products.price', 'merchant_name', 'orders.created_at', 'order_items.quantity')
            ->orderBy('order_items.quantity')
            ->whereBetween('orders.created_at', [$this->start_date, $this->end_date])
            ->whereRAW('DAYNAME(orders.created_at) =?', [$this->day])
            ->get();
    }

    function headings(): array
    {
        return ['Product Name', 'Price ', 'Merchant Name', 'Times been ordered', 'Quantity'];
    }
}
