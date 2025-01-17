@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">{{ ___('Users') }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div id="table" class="table-editable">
                        <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid" aria-describedby="user-list-page-info">
                            <thead>
                                <tr>
                                    <th>{{ ___('User Image') }}</th>
                                    <th>{{ ___('Name') }}</th>
                                    <th>{{ ___('Email') }}</th>
                                    <th>{{ ___('Phone') }}</th>
                                    <th>{{ ___('Email Verified') }}</th>
                                    <th>{{ ___('Status') }}</th>
                                    <th>{{ ___('Join Date') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="text-center"><img class="rounded-circle img-fluid avatar-40" src="{{ getImageUrl($user->user_img) ?? asset('assets/images/user/default_user.png') }}" alt="profile"></td>
                                    <td >{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        @if($user->email_verified_at)
                                        <span class="badge iq-bg-success">{{ ___('Verified') }}</span>
                                        @else
                                        <span class="badge iq-bg-warning">{{ ___('Not Verified') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->status)
                                        <span class="badge dark-icon-light iq-bg-primary">{{ ___('Active') }}</span>
                                        @else
                                        <span class="badge iq-bg-danger">{{ ___('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->formatted_created_at }}</td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            @can('delete-users')
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" data-toggle="tooltip" data-placement="top" title="{{ ___('Delete') }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="javascript:void(0);" onclick="this.closest('form').submit();" class="iq-bg-primary">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </a>
                                                </form>
                                            @endcan
                                            @can('edit-users')
                                                <a class="iq-bg-primary ml-2" data-placement="top" title="" data-original-title="{{ ___('Edit') }}" href="{{ route('users.edit', $user->user_id) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
