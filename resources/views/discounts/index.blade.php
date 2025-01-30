@extends('layouts.app')
@section('title',  ___('Discounts') )
@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">{{ ___('Discounts') }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div id="table" class="table-editable">
                        <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid" aria-describedby="user-list-page-info">
                            <thead>
                                <tr>
                                    <th>{{ ___('Discount Number') }}</th>
                                    <th>{{ ___('Discount Type') }}</th>
                                    <th>{{ ___('Discount Value') }}</th>
                                    <th>{{ ___('Start Date') }}</th>
                                    <th>{{ ___('End Date') }}</th>
                                    <th>{{ ___('Status') }}</th>
                                    <th>{{ ___('Created At') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($discounts as $discount)
                                <tr>
                                    <td>#{{ $discount->discount_id }}</td>
                                    <td>{{  ___($discount->type)  }}</td>
                                    <td>{{ $discount->value }}</td>
                                    <td>{{ $discount->formatted_start_date }}</td>
                                    <td>{{ $discount->formatted_end_date }}</td>
                                    <td>
                                        @if($discount->status)
                                        <span class="badge dark-icon-light iq-bg-primary">{{ ___('Active') }}</span>
                                        @else
                                        <span class="badge iq-bg-danger">{{ ___('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $discount->formatted_created_at }}</td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            <x-delete-button :route="route('discounts.destroy', $discount)" title="Delete" />
                                                <a class="iq-bg-primary ml-2" data-placement="top" title="" data-original-title="{{ ___('Edit') }}" href="{{ route('discounts.edit', $discount->discount_id) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row justify-content-between mt-3">
                            <!-- Page Info -->
                            <div id="user-list-page-info" class="col-md-6">
                                <span>{{ ___('Showing') }} {{ $discounts->firstItem() }} {{ ___('to') }} {{ $discounts->lastItem() }} {{ ___('of') }} {{ $discounts->total() }} {{ ___('entries') }}</span>
                            </div>

                            <!-- Pagination -->
                            <div class="col-md-6">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        {{ $discounts->links('pagination::bootstrap-4') }}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
