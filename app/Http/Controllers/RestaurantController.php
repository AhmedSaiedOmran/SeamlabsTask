<?php

namespace App\Http\Controllers;

use App\CustomClasses\ApiResponseHelper;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Rules\OrderTypeExist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function get_menu()
    {
        $menuItems = MenuItem::select(['id', 'name', 'price'])->get();
        return response()->json(
            ApiResponseHelper::createSuccessResponse("Menu Items List Retrieved", $menuItems),
            200
        );
    }
    public function get_order($order_id)
    {
        $Order = Order::find($order_id);
        if ($Order) {
            return response()->json(
                ApiResponseHelper::createSuccessResponse("Order Retrieved", $Order->JsonData()),
                200
            );

        }else{
            return response()->json(
                ApiResponseHelper::createErrorResponse("Something Wrong Happened!",["Order Not Found"]),
                400
            );

        }
    }


    public function make_order(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'type'  => ['required', new OrderTypeExist()],

            'menu_items' => ['required', 'array'],
            'menu_items.*' => ['required', 'array'],
            'menu_items.*.id' => ['required', 'exists:menu_items,id'],
            'menu_items.*.qty' => ['required', 'exists:menu_items,id', 'numeric'],

            'table_number' => ['required_if:type,' . Order::TYPE_dine_in],
            'waiter_name' => ['required_if:type,' . Order::TYPE_dine_in],
            'service_charge' => ['required_if:type,' . Order::TYPE_dine_in, 'numeric','min:0'],

            'customer_name' => ['required_if:type,' . Order::TYPE_delivery],
            'customer_phone' => ['required_if:type,' . Order::TYPE_delivery],
            'delivery_fees' => ['required_if:type,' . Order::TYPE_delivery, 'numeric'],

        ]);
        if ($validator->fails()) {
            return response()->json(
                ApiResponseHelper::createErrorResponse("Validation error in your request", $validator->messages()),
                400
            );
        }

        $validated = $validator->validated();

        $Order = null;

        if ($validated['type'] == Order::TYPE_dine_in) {
            $Order = Order::create([
                'type' => Order::TYPE_dine_in
            ]);
            $Order->addItemsToOrder($validated['menu_items']);
            $Order->addDineInInfo($validated['table_number'], $validated['waiter_name'], $validated['service_charge']);
            $Order->calculateTotal();
        } else if ($validated['type'] == Order::TYPE_delivery) {
            $Order = Order::create([
                'type' => Order::TYPE_delivery
            ]);
            $Order->addItemsToOrder($validated['menu_items']);
            $Order->adddeliveryInfo($validated['customer_name'], $validated['customer_phone'], $validated['delivery_fees']);
            $Order->calculateTotal();

        } else if ($validated['type'] == Order::TYPE_takeaway) {
            $Order = Order::create([
                'type' => Order::TYPE_takeaway
            ]);
            $Order->addItemsToOrder($validated['menu_items']);
            $Order->calculateTotal();
        }

        if ($Order) {
            return response()->json(
                ApiResponseHelper::createSuccessResponse("Order Created Successfully!",$Order->JsonData()),
                201
            );
        }else{
            return response()->json(
                ApiResponseHelper::createErrorResponse("Something Wrong Happened!",["Order Not Created"]),
                400
            );
        }
    }
}
