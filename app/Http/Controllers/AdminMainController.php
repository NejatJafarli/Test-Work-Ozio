<?php

namespace App\Http\Controllers;

use App\Models\sale_receipts;
use App\Models\stores;
use App\Models\user;
use Illuminate\Http\Request;
// use Yajra\DataTables\DataTables;

class AdminMainController extends Controller
{
    //dashboard

    public function dashboard()
    {
        return view('Admin/Main/dashboard');
    }
    public function taskOne()
    {
        // get stores names and store codes
        $stores = stores::select('name', 'store_code')->get();

        $data = [
            'stores' => $stores
        ];
        return view('Admin/Main/task-one', $data);
    }

    public function userDetail($id)
    {
        $user = user::find($id);
        if (!$user) {
            return redirect()->back()->withErrors('Kullanıcı bulunamadı');
        }
        $stores = stores::select('name', 'store_code')->get();
        $data = [
            'user' => $user,
            'stores' => $stores
        ];
        return view('Admin/Main/user-detail', $data);
    }
    // receiptDetail
    public function receiptDetail($id)
    {
        $receipt = sale_receipts::with(['user', 'store'])->find($id);
        if (!$receipt) {
            return redirect()->back()->withErrors('Fiş bulunamadı');
        }
        $data = [
            'receipt' => $receipt
        ];
        return view('Admin/Main/receipt-detail', $data);
    }
    
}
