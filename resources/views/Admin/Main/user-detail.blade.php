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
                        <h6 class="card-title mb-0">Istifadeci Melumatlari</h6>
                    </div>
                    <div class="row">

                        <div class="col-6 col-md-12 col-xl-5">
                            <h5 class="mb-2">Ad Soyad: {{ $user->name }}</h5>
                            <h5 class="mb-2">Email: {{ $user->email }}</h5>
                            <h5 class="mb-2">Telefon: {{ $user->country_code . ' ' . $user->phone }}</h5>
                            <h5 class="mb-2">Dogum Tarixi: {{ $user->birth_date }}</h5>
                            <h5 class="mb-2">Cinsiyyet:
                                @if ($user->gender == 1)
                                    <span class="badge bg-primary">Kisi</span>
                                @elseif($user->gender == 2)
                                    <span class="badge bg-pink">Qadin</span>
                                @endif
                            </h5>
                            <h5 class="mb-2">Referal Kodu: {{ $user->referal_code }}</h5>
                            <h5 class="mb-2">Bonus Kart No: {{ $user->bonus_card_no }}</h5>
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
                                    <th class="pt-0">Kart No</th>
                                    <th class="pt-0">Magaza <select id="storeFilter" class="form-select">
                                            <option value="0">Hepsi</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->store_code }}">{{ $store->name }}</option>
                                            @endforeach
                                        </select></th>
                                    <th class="pt-0">Kassa No <input name="filterCashier" type="number"
                                            class="form-control" placeholder="Kassa Nomresi"></th>
                                    <th class="pt-0">Mebleg <input name="filterAmount" type="number" class="form-control"
                                            placeholder="Mebleg"></th>
                                    <th class="pt-0">Qazanilan Bonus <input name="filterBonus" type="number"
                                            class="form-control" placeholder="Qazanilan Bonus"></th>
                                    <th class="pt-0">Odenis Tipi <select id="FilterpaymentType" class="form-select">
                                            <option value="0">Hepsi</option>
                                            <option value="1">Nagd</option>
                                            <option value="2">Kredit Karti</option>
                                        </select></th>
                                    <th class="pt-0">Satis Tarixi <input name="filterDate" type="date"
                                            class="form-control" placeholder="Satis Tarixi"></th>
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
                        url: "{{ route('datatableUserReceiptsHistory') }}",
                        data: function(d) {
                            d.filters = {};
                            d.filters.cardno = '{{ $user->bonus_card_no }}';
                            d.filters.store = $('#storeFilter').val();
                            d.filters.cashier = $('input[name="filterCashier"]').val();
                            d.filters.total = $('input[name="filterAmount"]').val();
                            d.filters.bonus = $('input[name="filterBonus"]').val();
                            d.filters.paymentType = $('#FilterpaymentType').val();
                            d.filters.sale_date = $('input[name="filterDate"]').val();
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'cardno',
                            name: 'cardno',
                            searchable: false
                            orderable: false
                        },
                        {
                            data: 'store',
                            name: 'store',
                            searchable: false
                            orderable: false
                        },
                        {
                            data: 'cashier',
                            name: 'cashier',
                            searchable: false
                        },
                        {
                            data: 'total',
                            name: 'total',
                            searchable: false
                        },
                        {
                            data: 'bonus',
                            name: 'bonus',
                            searchable: false
                        },
                        {
                            data: 'payment_type',
                            name: 'payment_type',
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'sale_date',
                            name: 'sale_date',
                            searchable: false
                        },
                    ],
                    order: [
                        [7, 'desc']
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
