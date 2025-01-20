@extends('layouts.app')
@section('title', ___('Admins'))
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">{{ ___('Admins') }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="iq-search-bar d-none d-md-block">
                        <form action="{{ route('admins.index') }}" class="searchbox">
                            <div class="row">
                                <div class="col">
                                    <input name="search" value="{{ request('search') }}" type="text"
                                        class="text search-input form-control"
                                        placeholder="{{ ___('Search by name, phone or email...') }}">
                                    @error('search')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="table" class="table-editable">
                        <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid"
                            aria-describedby="user-list-page-info">
                            <thead>
                                <tr>
                                    <th>{{ ___('Admin Image') }}</th>
                                    <th>{{ ___('Name') }}</th>
                                    <th>{{ ___('Email') }}</th>
                                    <th>{{ ___('Super Admin') }}</th>
                                    <th>{{ ___('Status') }}</th>
                                    <th>{{ ___('Join Date') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staffUsers as $staffUser)
                                    <tr>
                                        <td class="text-center"><img class="rounded-circle img-fluid avatar-40"
                                                src="{{ getImageUrl($staffUser->staff_user_img) ?? asset('assets/images/user/default_user.png') }}"
                                                alt="profile"></td>
                                        <td>{{ $staffUser->name }}</td>
                                        <td>{{ $staffUser->email }}</td>

                                        <td>
                                            @if ($staffUser->hasRole('super-admin'))
                                                <span class="badge iq-bg-success">✔</span>
                                            @else
                                                <span class="badge iq-bg-warning">X</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($staffUser->status)
                                                <span
                                                    class="badge dark-icon-light iq-bg-primary">{{ ___('Active') }}</span>
                                            @else
                                                <span class="badge iq-bg-danger">{{ ___('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $staffUser->formatted_created_at }}</td>
                                        <td>
                                            <div class="flex align-items-center list-user-action">
                                                @can('manage-staff-users')
                                                <x-delete-button :route="route('admins.destroy', $staffUser)" title="{{ ___('Delete') }}" />

                                                @endcan
                                                @can('manage-staff-users')
                                                    <a class="iq-bg-primary ml-2" data-placement="top" title=""
                                                        data-original-title="{{ ___('Edit') }}"
                                                        href="{{ route('admins.edit', $staffUser->staff_user_id) }}">
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
                                <span>{{ ___('Showing') }} {{ $staffUsers->firstItem() }} {{ ___('to') }}
                                    {{ $staffUsers->lastItem() }} {{ ___('of') }} {{ $staffUsers->total() }}
                                    {{ ___('entries') }}</span>
                            </div>

                            <!-- Pagination -->
                            <div class="col-md-6">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        {{ $staffUsers->links('pagination::bootstrap-4') }}
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
