@extends('layouts.app')

@section('title', ___('Create User'))
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Create User') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="userForm" data-action="{{ route('users.store') }}" action="{{ route('users.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row align-items-center">
                    <div class="col-md-12">
                        <div class="profile-img-edit">
                            <img class="profile-pic" src="{{ asset('assets/images/user/default_user.png') }}" alt="profile-pic">
                            <div class="p-image">
                                <i class="ri-pencil-line upload-button"></i>
                                <input class="file-upload" name="user_img" type="file" accept="image/*" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Full Name') }}*</label>
                        <input name="name" type="text" class="form-control" id="name"
                            placeholder="{{ ___('Full Name') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="phone">{{ ___('Phone') }}</label>
                        <input name="phone" type="text" class="form-control" id="phone"
                            placeholder="{{ ___('Phone') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="custom-control custom-switch custom-control-inline">
                            <input type="hidden" name="status" value="0">

                            <input name="status" value="1" type="checkbox" class="custom-control-input"
                                id="customSwitch2" checked>
                            <label class="custom-control-label" for="customSwitch2">{{ __('Status') }}*</label>
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
                    window.location.href = '{{ route('users.create') }}';
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
