<?php

namespace App\Http\Controllers\Api;

use App\Exports\leastOrderedExport;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\leastOrderedRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItems;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends ApiBaseController
{
    public function index(): JsonResponse
    {
        try {

            if (Cache::has('orders')) {
                $orders = Cache::get('orders');
            } else {
                $orders = Cache::rememberForever('orders', function () {
                    return Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
                        ->join('products', 'products.id', '=', 'order_items.product_id')
                        ->join('merchants', 'merchants.id', '=', 'products.merchant_id')
                        ->select('orders.created_at', 'merchant_name', 'products.name', 'products.price', 'order_items.quantity')
                        ->orderBy('order_items.quantity')
                        ->get();
                });
            }

            return $this->successResponse(OrderResource::collection($orders), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function leastOrdered(leastOrderedRequest $request): JsonResponse
    {
        try {
            Cache::forget('orders');

            $orders = Cache::rememberForever('orders', function () use ($request) {

                $start_date = Carbon::parse($request->start_date)
                    ->toDateTimeString();

                $end_date = Carbon::parse($request->end_date)
                    ->toDateTimeString();

                return Order::join('order_items', 'order_items.order_id', '=', 'orders.id')
                    ->join('products', 'products.id', '=', 'order_items.product_id')
                    ->join('merchants', 'merchants.id', '=', 'products.merchant_id')
                    ->select('orders.created_at', 'merchant_name', 'products.name', 'products.price', 'order_items.quantity')
                    ->orderBy('order_items.quantity')
                    ->whereBetween('orders.created_at', [$start_date, $end_date])
                    ->whereRAW('DAYNAME(orders.created_at) =?', [$request->day])
                    ->get();
            });

            return $this->successResponse(OrderResource::collection($orders), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function export(leastOrderedRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->start_date)
                ->toDateTimeString();

            $end_date = Carbon::parse($request->end_date)
                ->toDateTimeString();

            $day = $request->day;

            return Excel::download(new leastOrderedExport($day, $start_date, $end_date), 'orders.xlsx');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

}
