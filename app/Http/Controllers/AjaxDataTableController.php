<?php

namespace App\Http\Controllers;

use App\Models\bonus;
use App\Models\sale_receipts;
use App\Models\user;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AjaxDataTableController extends Controller
{
    public function datatableTaskOne(Request $request)
    {
        $query = sale_receipts::with(['user', 'store']);

        $filters = $request->filters;
        if ($request->has('filters')) {
            // Store filter
            if (!empty($filters['store']) && $filters['store'] != '0') {
                $query->where('store_code', $filters['store']);
            }

            // Date filter
            if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
                $query->whereBetween('sale_date', [$filters['startDate'], $filters['endDate']]);
            }

            // Bonus card numbers
            if ($filters['filterChoice'] !== null) {
                $cards = bonus::pluck('cardno')->toArray();
                //remove duplicates
                $cards = array_unique($cards);
                if ($filters['filterChoice'] == '0') {
                    $query->whereIn('cardno', $cards);
                } else if ($filters['filterChoice'] == '1') {
                    $excludedCards = $query->whereIn('cardno', $cards)->pluck('cardno')->toArray();
                    $query = user::whereNotIn('bonus_card_no', $excludedCards);
                }
            }
        }

        $data = DataTables::of($query)
            ->addIndexColumn()
            //columns card no user name and user phone
            ->addColumn('cardno', function ($row) use ($filters) {
                if ($filters['filterChoice'] == '1') {
                    return $row->bonus_card_no;
                }

                return $row->cardno;
            })
            //add store column
            ->addColumn('store', function ($row) use ($filters) {
                if ($filters['filterChoice'] == '1') {
                    return "Not Found";
                }
                if ($row->store) {
                    return $row->store->name;
                }
                return '';
            })
            ->addColumn('user_name', function ($row)  use ($filters) {
                if ($filters['filterChoice'] == '1') {
                    return '<a href="' . route('userDetail', $row->id) . '">' . $row->name . '</a>';
                }
                if ($row->user) {
                    //use route userDetail
                    return '<a href="' . route('userDetail', $row->user->id) . '">' . $row->user->name . '</a>';
                }
                return '';
            })
            ->addColumn('user_phone', function ($row) use ($filters) {
                if ($filters['filterChoice'] == '1') {
                    return $row->user_phone;
                }
                if ($row->user) {
                    return $row->user->user_phone;
                }
                return '';
            })
            ->addColumn('date', function ($row) {
                if ($row->sale_date) {
                    return $row->sale_date;
                }
                return "";
            })
            ->rawColumns(['user_name'])
            ->make(true);

        return $data;
    }
    public function datatableUserReceiptsHistory(Request $request)
    {
        $query= sale_receipts::with(['store'])->where('cardno', $request->cardno);

        if ($request->has('filters')) {
            // Date filter
          
        }
    }
}
