@extends('layouts.app')

@section('title', ___('Edit Admin'))
@section('content')
    {{-- @dd($permissions) --}}
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Edit Admin') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="adminForm" data-action="{{ route('admins.update',$staffUser) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row align-items-center">
                    <div class="col-md-12">
                        <div class="profile-img-edit">
                            <img class="profile-pic" src="{{ getImageUrl($staffUser->staff_user_img) ?? asset('assets/images/user/default_user.png') }}"
                                alt="profile-pic">
                            <div class="p-image">
                                <i class="ri-pencil-line upload-button"></i>
                                <input class="file-upload" name="staff_user_img" type="file" accept="image/*" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Full Name') }}*</label>
                        <input name="name" type="text" class="form-control" id="name"
                            value="{{ $staffUser->name }}"
                            placeholder="{{ ___('Full Name') }}">
                    </div>
                    <div class="col">
                        <label for="email">{{ ___('Email') }}*</label>
                        <input name="email" type="text" class="form-control" id="email"
                            value="{{ $staffUser->email }}"
                            placeholder="{{ ___('Email') }}">
                    </div>
                </div>
                <br>
                <div class="row">
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
                            <input type="hidden" name="status" value="0">
                            <input name="status" value="1" type="checkbox" class="custom-control-input"
                                id="customSwitch2" checked="">
                            <label class="custom-control-label" value="1"
                                for="customSwitch2">{{ ___('Status') }}*</label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="custom-control custom-checkbox">
                            <input
                                type="checkbox"
                                class="custom-control-input"
                                id="superAdminToggle"
                                name="super_admin"
                                value="1"
                                @if($staffUser->hasRole('super-admin')) checked @endif
                            />
                            <label class="custom-control-label" for="superAdminToggle">
                                {{ ___('Super Admin') }}
                            </label>
                        </div>
                    </div>

                    {{-- Chunk permissions into groups of 3 --}}
                    @foreach($permissions->chunk(3) as $chunk)
                        <div class="col-md-3">
                            @foreach($chunk as $permission)
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input permission-checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        id="customCheck{{ $permission->id }}"
                                        {{ in_array($permission->id, $staffUserPermissions) ? 'checked' : '' }}
                                    />
                                    <label class="custom-control-label" for="customCheck{{ $permission->id }}">
                                        {{ ___(ucwords(str_replace('-', ' ', $permission->name))) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>


                <br>
                <div class="form-group">
                    <button form="adminForm" type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
            <div id="validationErrors"></div>
        </div>
    </div>


    <script>
        var autoForm = $("#adminForm");

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
                    window.location.href = '{{ route('admins.edit', $staffUser->staff_user_id) }}';
                    toastr.success('{{ __('messages.updated') }}');
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

<script>
    document.addEventListener("DOMContentLoaded", () => {
    const superAdminToggle = document.getElementById("superAdminToggle");
    const permissionCheckboxes = document.querySelectorAll(".permission-checkbox");

    // Function to update checkboxes based on Super Admin toggle
    const toggleAllPermissions = (isChecked) => {
        permissionCheckboxes.forEach((checkbox) => {
            checkbox.checked = isChecked;
        });
    };

    // Listen to Super Admin toggle changes
    superAdminToggle.addEventListener("change", (e) => {
        toggleAllPermissions(e.target.checked);
    });

    // Uncheck Super Admin if any permission checkbox is unchecked
    permissionCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", () => {
            if (!checkbox.checked) {
                superAdminToggle.checked = false;
            } else {
                // If all checkboxes are checked, enable Super Admin
                const allChecked = Array.from(permissionCheckboxes).every(
                    (box) => box.checked
                );
                superAdminToggle.checked = allChecked;
            }
        });
    });
});

</script>

@endsection
