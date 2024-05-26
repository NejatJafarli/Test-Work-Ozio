@extends('layout/master')
@section('header')
@endsection
@section('content')
    <div class="row flex-grow-1">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Filterler</h6>
                    </div>
                    <div class="row">
                        <div class="col-2 col-md-12 col-xl-2">
                            <h3 class="mb-2">Tarih Aralığı</h3>

                            <label for="startDateFilter">Başlangıç Tarihi</label>
                            <input onclick="openDateTime(this)" type="datetime-local" class="form-control"
                                id="startDateFilter">
                            <label for="startDateFilter">Bitiş Tarihi</label>
                            <input onclick="openDateTime(this)" type="datetime-local" class="form-control"
                                id="endDateFilter">
                        </div>
                        <div class="col-4 col-md-12 col-xl-4">
                            <h3 class="mb-2">Mağazalar</h3>
                            <select id="storeFilter" class="form-select">
                                <option value="0">Hepsi</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->store_code }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 col-md-12 col-xl-4">
                            <h3 class="mb-2">Hansi Kartlar Getirilsin</h3>
                            <select id="filterChoice" class="form-select">
                                <option value="0">Alisveris Eden Kartlar</option>
                                <option value="1">Alisveris Etmeyen Kartlar</option>
                            </select>
                        </div>
                        <div class="col-2 col-md-12 col-xl-2">
                            <h3 class="mb-2">action</h3>
                            <button id="btn-search" class="btn btn-primary w-100">Axtar</button>
                            <button id="btn-reset" class="btn btn-danger mt-3 w-100">Sifirla</button>
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
                            <h6 class="card-title mb-0">Datas</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="MyDataTable" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pt-0">#</th>
                                    <th class="pt-0">Kart No</th>
                                    <th class="pt-0">Magaza</th>
                                    <th class="pt-0">Kullanici Adi</th>
                                    <th class="pt-0">Kullanici Telefonu</th>
                                    <th class="pt-0">Satis Tarixi</th>
                                </tr>
                            </thead>
                        </table>
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
        // npm package: datatables.net-bs5
        // github link: https://github.com/DataTables/Dist-DataTables-Bootstrap5
        // npm package: datatables.net-bs5
        // github link: https://github.com/DataTables/Dist-DataTables-Bootstrap5

        $(function() {
            'use strict';

            $(function() {
                let datatable = $('#MyDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('datatableTaskOne') }}",
                        data: function(d) {
                            d.filters={};
                            d.filters.startDate = $('#startDateFilter').val();
                            d.filters.endDate = $('#endDateFilter').val();
                            d.filters.store = $('#storeFilter').val();
                            d.filters.filterChoice = $('#filterChoice').val();

                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'cardno',
                            name: 'cardno'
                        },
                        {
                            data: 'store',
                            name: 'store'
                        },
                        {
                            data: 'user_name',
                            name: 'user_name'
                        },
                        {
                            data: 'user_phone',
                            name: 'user_phone'
                        },
                        {
                            data: 'date',
                            name: 'date'
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

            $('#btn-search').on('click', function() {
                $('#MyDataTable').DataTable().ajax.reload();
            });

            $('#btn-reset').on('click', function() {
                $('#startDateFilter').val('');
                $('#endDateFilter').val('');
                $('#storeFilter').val('0');
                $('#filterChoice').val('0');

                $('#MyDataTable').DataTable().ajax.reload();
            });

            function openDateTime(element) {
                $(element).showPicker();
            }
        });
    </script>
@endsection
