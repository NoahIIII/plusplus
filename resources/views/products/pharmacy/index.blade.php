@extends('layouts.app')
@section('title', ___('Products'))
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">{{ ___('Products') }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <table id="pharmacy-products-table" class="table table-striped table-borderless mt-4" role="grid"
                        style="width:100%" class="display">
                        <thead>
                            <tr>
                                <th>{{ ___('Name In Arabic') }}</th>
                                <th>{{ ___('Name In English') }}</th>
                                <th>{{ ___('Price') }}</th>
                                <th>{{ ___('Quantity') }}</th>
                                <th>{{ ___('Status') }}</th>
                                <th>{{ ___('Created At') }}</th>
                                <th>{{ ___('Actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#pharmacy-products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('pharmacy-products.data') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'name_ar',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'name_en',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        targets: [5],
                        render: function(data, type, row) {
                            // Format the 'Created At' column to a readable date
                            return new Date(data).toLocaleString();
                        }
                    },
                    {
                        targets: [6],
                        render: function(data, type, row) {
                            // Render the actions column as HTML
                            return data;
                        }
                    }
                ],
                dom: 'lfrtip',

                order: [
                    [5, 'desc']
                ],
                language: {
                    search: '{{ ___('Search') }}',
                    lengthMenu: '{{ ___('Show _MENU_ entries') }}',
                    info: '{{ ___('Showing _START_ to _END_ of _TOTAL_ entries') }}',
                    paginate: {
                        previous: '{{ ___('Previous') }}',
                        next: '{{ ___('Next') }}'
                    }
                }
            });
        });
    </script>


@endsection
