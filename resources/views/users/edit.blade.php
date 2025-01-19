@extends('layouts.app')

@section('title', ___('Edit User'))
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Edit User') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            {{-- @dd(getImageUrl($user->user_img)); --}}
            <form id="userForm" data-action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row align-items-center">
                    <div class="col-md-12">
                        <div class="profile-img-edit">
                            <img class="profile-pic" src="{{ getImageUrl($user->user_img) ?? asset('assets/images/user/default_user.png') }}" alt="profile-pic">
                            <div class="p-image">
                                <i class="ri-pencil-line upload-button"></i>
                                <input class="file-upload" name="user_img" type="file" accept="image/*" />
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Full Name') }}*</label>
                        <input name="name" type="text" class="form-control" id="name" value="{{ $user->name }}"
                            placeholder="{{ ___('Full Name') }}">
                    </div>
                    <div class="col">
                        <label for="email">{{ ___('Email') }}*</label>
                        <input name="email" type="text" class="form-control" id="email" value="{{ $user->email }}"
                            placeholder="{{ ___('Email') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="phone">{{ ___('Phone') }}</label>
                        <input name="phone" type="text" class="form-control" id="phone" value="{{ $user->phone }}"
                            placeholder="{{ ___('Phone') }}">
                    </div>
                    <div class="col">
                        <label for="password">{{ ___('Password') }}*</label>
                        <input name="password" type="text" class="form-control" id="password"
                            placeholder="{{ ___('Leave blank to keep the current password') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="custom-control custom-switch custom-control-inline">
                            <input name="status" value="1" type="checkbox" class="custom-control-input"
                                id="customSwitch2" @if($user->status) checked="" @endif>
                            <label class="custom-control-label" value="1"
                                for="customSwitch2">{{ ___('Status') }}*</label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <button form="userForm" type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
            <div id="validationErrors"></div>
        </div>
    </div>


    <script>
        var autoForm = $("#userForm");

        $(autoForm).on('submit', function(event) {
            event.preventDefault();

            var url = $(this).attr('data-action');

            var formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    window.location.href = '{{ route('users.edit', $user->user_id) }}';
                    toastr.success('{{ __('messages.added') }}');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                    var errorsReturned = xhr.responseJSON.errors;
                    // var errorsMessage = xhr.responseJSON.message;
                    // if (errorsMessage != '') {
                    //     toastr.error(errorsMessage);
                    // }
                    if (errorsReturned) {
                        Object.keys(errorsReturned).forEach(function(key) {
                            errorsReturned[key].forEach(function(error) {
                                toastr.error(error);
                            });
                        });
                    }
                }
            });
        });
    </script>

@endsection
