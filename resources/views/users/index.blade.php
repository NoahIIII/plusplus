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
                        <span class="table-add float-right mb-3 mr-2">
                            <button class="btn btn-sm iq-bg-success">
                                <i class="ri-add-fill"><span class="pl-1">Add New</span></i>
                            </button>
                        </span>
                        <table class="table table-bordered table-responsive-md table-striped text-center">
                            <thead>
                                <tr>
                                    <th>{{ ___('Name') }}</th>
                                    <th>{{ ___('Email') }}</th>
                                    <th>{{ ___('Phone') }}</th>
                                    <th>{{ ___('Email Verified') }}</th>
                                    <th>{{ ___('Status') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td contenteditable="true">{{ $user->name }}</td>
                                    <td contenteditable="true">{{ $user->email }}</td>
                                    <td contenteditable="true">{{ $user->phone }}</td>
                                    <td contenteditable="true">
                                        @if($user->email_verified_at)
                                        <span class="badge badge-success">{{ ___('Verified') }}</span>
                                        @else
                                        <span class="badge badge-secondary">{{ ___('Not Verified') }}</span>
                                        @endif
                                    </td>
                                    <td contenteditable="true">
                                        @if($user->status)
                                        <span class="badge badge-success">{{ ___('Active') }}</span>
                                        @else
                                        <span class="badge badge-secondary">{{ ___('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="table-remove"><button type="button"
                                                class="btn iq-bg-danger btn-rounded btn-sm my-0">
                                                Remove
                                            </button></span>
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
