@extends('layouts.app')


@section('title', 'Create Brand')
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Create Brand') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="brandForm" data-action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Brand Name In Arabic') }}*</label>
                        <input name="name_ar" type="text" class="form-control" id="name"
                            placeholder="{{ ___('Brand Name In Arabic') }}">
                    </div>
                    <div class="col">
                        <label for="name">{{ ___('Brand Name In English') }}*</label>
                        <input name="name_en" type="text" class="form-control" id="name"
                            placeholder="{{ ___('Brand Name In English') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="custom-control custom-switch custom-control-inline">
                            <input name="status" value="1" type="checkbox" class="custom-control-input"
                                id="customSwitch2" checked="">
                            <label class="custom-control-label" value="1"
                                for="customSwitch2">{{ ___('Status') }}*</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <div class="custom-file">
                                <input name="image" type="file" class="custom-file-input" id="customFile" />
                                <label class="custom-file-label" for="customFile">{{ ___('Brand Image') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <button form="brandForm" type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
            <div id="validationErrors"></div>
        </div>
    </div>


    <script>
        var autoForm = $("#brandForm");

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
                    window.location.href = '{{ route('brands.create') }}';
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
