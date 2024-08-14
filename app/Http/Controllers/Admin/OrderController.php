<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf; // Sử dụng package như dompdf
use Mail;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index()
    {
        $orders = Order::with('orderItems')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status_order' => 'required|in:' . implode(',', array_keys(Order::STATUS_ORDER)),
            'status_payment' => 'required|in:' . implode(',', array_keys(Order::STATUS_PAYMENT)),
        ]);

        $order->update([
            'status_order' => $request->status_order,
            'status_payment' => $request->status_payment,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    public function destroy(Order $order)
    {
        $order->orderItems()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công');
    }
    public function generateInvoice(Order $order)
    {
        $order->load('orderItems');
        $pdf = PDF::loadView('admin.orders.invoice', compact('order'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('order-' . $order->id . '.pdf');
    }

    public function sendInvoiceEmail(Order $order)
    {
        $order->load('orderItems');
        $pdf = PDF::loadView('admin.orders.invoice', compact('order'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        Mail::to($order->user_email)->send(new InvoiceMail($order, $pdf));
        return redirect()->back()->with('success', 'Hóa đơn đã được gửi qua email.');
    }
}
