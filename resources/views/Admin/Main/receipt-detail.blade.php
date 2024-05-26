@extends('layout/master')

@section('header')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
        integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>
    <style>
        .chat {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: black;
            border-radius: 10px;
            max-width: 1200px;
            height: 400px;
            overflow-y: scroll;
        }

        .message {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="row flex-grow-1">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{-- //show error messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger solid alert-dismissible fade show">
                            <h5>Hata</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- //show success messages --}}
                    @if (session('success'))
                        <div class="alert alert-success solid alert-dismissible fade show">
                            <h5>Basarili</h5>
                            <ul class="mb-0">
                                <li>{{ session('success') }}</li>
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-baseline">
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 col-xl-6">
                            <h6 class="card-title mb-0">Cek Melumatlari</h6>
                            <h5 class="mb-2">Magaza: {{ $receipt->store->name }}</h5>
                            <h5 class="mb-2">Kassa No: {{ $receipt->cashier_code }}</h5>
                            <h5 class="mb-2">Istifade Olunan Bonus Kart: {{ $receipt->cardno }}</h5>
                            <h5 class="mb-2">Mebleg: {{ $receipt->total }}</h5>
                            <h5 class="mb-2">Qazanilan Bonus: {{ $receipt->bonus }}</h5>
                            <h5 class="mb-2">Qaytarilma Statusu:
                                @if ($receipt->is_returned == 0)
                                    <span class="badge bg-success">Aktifdir</span>
                                @elseif($receipt->is_returned == 2)
                                    <span class="badge bg-danger">Qaytarilib</span>
                                @endif
                            </h5>
                            <h5 class="mb-2">Odenis Tipi:
                                @if ($receipt->cash_payment > 0)
                                    <span class="badge bg-success">Negd</span>
                                @elseif($receipt->card_payment > 0)
                                    <span class="badge bg-primary">Kart</span>
                                @endif
                            </h5>
                            <h5 class="mb-2">Tarix: {{ $receipt->sale_date }}</h5>
                        </div>
                        <div class="col-6 col-md-6 col-xl-6">
                            <h6 class="card-title mb-0">Alici Melumatlari</h6>
                            <h5 class="mb-2">Ad Soyad: {{ $receipt->user->name }}</h5>
                            <h5 class="mb-2">Email: {{ $receipt->user->email }}</h5>
                            <h5 class="mb-2">Telefon: {{ $receipt->user->user_phone }}</h5>
                            <h5 class="mb-2">Dogum Tarixi: {{ $receipt->user->birthday }}</h5>
                            <h5 class="mb-2">Cinsiyyet:
                                @if ($receipt->user->gender == 1)
                                    <span class="badge bg-primary">Kisi</span>
                                @elseif($receipt->user->gender == 2)
                                    <span style="background-color: pink;" class="badge bg-primary">Qadin</span>
                                @endif
                            </h5>
                            <h5 class="mb-2">Referal Kodu: {{ $receipt->user->referral_code }}</h5>
                            <h5 class="mb-2">Bonus Kart No: {{ $receipt->user->bonus_card_no }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-11">
                            <h6 class="card-title mb-0">Istifadeci Kecmisi</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="MyDataTable" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pt-0">#</th>
                                    <th class="pt-0">Ad</th>
                                    <th class="pt-0">Kod</th>
                                    <th class="pt-0">Barcode</th>
                                    <th class="pt-0">Say</th>
                                    <th class="pt-0">Qiymet</th>
                                    <th class="pt-0">Cemi Qiymet</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>


    <script>
        $(function() {
            'use strict';

            $(function() {
                let datatable = $('#MyDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('datatableReceiptItems') }}",
                        data: function(d) {
                            d.filters = {};
                            d.filters.receipt_id = '{{ $receipt->id }}';
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'barcode',
                            name: 'barcode'
                        },
                        {
                            data: 'quantity',
                            name: 'quantity'
                        },
                        {
                            data: 'price',
                            name: 'price'
                        },
                        {
                            data: 'total_price',
                            name: 'total_price'
                        },
                    ],
                    //order by created_at desc by default
                    pageLength: 10,
                    paging: true,
                    lengthChange: false,
                    searching: false,
                    ordering: true,
                    info: true,
                    autoWidth: false,
                });
            });
        });
    </script>
@endsection
