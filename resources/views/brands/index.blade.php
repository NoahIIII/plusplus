@extends('layouts.app')
@section('title',  ___('Brands') )
@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">{{ ___('Brands') }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div id="table" class="table-editable">
                        <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid" aria-describedby="user-list-page-info">
                            <thead>
                                <tr>
                                    <th>{{ ___('Brand Image') }}</th>
                                    <th>{{ ___('Name In Arabic') }}</th>
                                    <th>{{ ___('Name In English') }}</th>
                                    <th>{{ ___('Status') }}</th>
                                    <th>{{ ___('Created At') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brands as $brand)
                                <tr>
                                    <td class="text-center"><img class="rounded-circle img-fluid avatar-40" src="{{ getImageUrl($brand->image) ?? asset('assets/images/user/default_user.png') }}" alt="profile"></td>
                                    <td>{{ $brand->getTranslation('name', 'ar') }}</td>
                                    <td>{{ $brand->getTranslation('name', 'en') }}</td>

                                    <td>
                                        @if($brand->status)
                                        <span class="badge dark-icon-light iq-bg-primary">{{ ___('Active') }}</span>
                                        @else
                                        <span class="badge iq-bg-danger">{{ ___('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->formatted_created_at }}</td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            @can('manage-brands')
                                            <x-delete-button :route="route('brands.destroy', $brand)" title="Delete" />
                                            @endcan
                                            @can('manage-brands')
                                                <a class="iq-bg-primary ml-2" data-placement="top" title="" data-original-title="{{ ___('Edit') }}" href="{{ route('brands.edit', $brand->brand_id) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row justify-content-between mt-3">
                            <!-- Page Info -->
                            <div id="user-list-page-info" class="col-md-6">
                                <span>{{ ___('Showing') }} {{ $brands->firstItem() }} {{ ___('to') }} {{ $brands->lastItem() }} {{ ___('of') }} {{ $brands->total() }} {{ ___('entries') }}</span>
                            </div>

                            <!-- Pagination -->
                            <div class="col-md-6">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        {{ $brands->links('pagination::bootstrap-4') }}
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
