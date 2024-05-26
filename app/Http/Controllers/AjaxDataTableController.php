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
        $query = sale_receipts::with(['store'])->where('cardno', $request->filters['cardno']);

        $filters = $request->filters;
        if ($request->has('filters')) {
            if (!empty($filters['store']) && $filters['store'] != '0') {
                $query->where('store_code', $filters['store']);
            }
            if (!empty($filters['cashier'])) {
                $query->where('cashier_code', $filters['cashier']);
            }
            if (!empty($filters['total'])) {
                $query->where('total', $filters['total']);
            }
            if (!empty($filters['bonus'])) {
                $query->where('bonus', $filters['bonus']);
            }
            if (!empty($filters['paymentType']) && $filters['paymentType'] != '0') {
                if ($filters['paymentType'] == '1') {
                    $query->where('cash_payment', '>', 0);
                } else if ($filters['paymentType'] == '2') {
                    $query->where('credit_card_payment', '>', 0);
                }
            }
            if (!empty($filters['sale_date'])) {
                $query->where('sale_date', $filters['sale_date']);
            }
        }

        $orderColumn = isset($request->order[0]['column']) ? $request->order[0]['column'] : null;
        $orderASCorDESC = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : null;


        //find column name by index
        if ($orderColumn != null) {
            switch ($orderColumn) {
                case 0:
                    $orderColumn = 'id';
                    break;
                case 1:
                    $orderColumn = 'cardno';
                case 2:
                    $orderColumn = 'store_code';
                    break;
                case 3:
                    $orderColumn = 'cashier_code';
                    break;
                case 4:
                    $orderColumn = 'total';
                    break;
                case 5:
                    $orderColumn = 'bonus';
                    break;
                case 6:
                    break;
                case 7:
                    $orderColumn = 'sale_date';
            }
        }

        if ($orderColumn != null && $orderASCorDESC != null) {
            $query->orderBy($orderColumn, $orderASCorDESC);
        }

        $data = DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('store', function ($row) {
                if ($row->store) {
                    return $row->store->name;
                }
                return '';
            })
            ->addColumn('cashier', function ($row) {
                return $row->cashier_code;
            })
            ->addColumn('total', function ($row) {
                return $row->total;
            })
            ->addColumn('bonus', function ($row) {
                return $row->bonus;
            })
            ->addColumn('payment_type', function ($row) {
                if ($row->cash_payment > 0) {
                    return 'Nakit';
                } else if ($row->credit_card_payment > 0) {
                    return 'Kredi Kartı';
                }
                return '';
            })
            ->addColumn('sale_date', function ($row) {
                return $row->sale_date;
            })
            ->make(true);
        return $data;
    }
}
