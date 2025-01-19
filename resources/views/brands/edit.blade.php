@extends('layouts.app')


@section('title', ___('Edit Brand'))
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Edit Brand') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="brandForm" data-action="{{ route('brands.update',$brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Brand Name In Arabic') }}*</label>
                        <input name="name_ar" type="text" class="form-control" id="name"
                            value="{{ $brand->getTranslation('name', 'ar') }}"
                            placeholder="{{ ___('Brand Name In Arabic') }}">
                    </div>
                    <div class="col">
                        <label for="name">{{ ___('Brand Name In English') }}*</label>
                        <input name="name_en" type="text" class="form-control" id="name"
                            value="{{ $brand->getTranslation('name', 'en') }}"
                            placeholder="{{ ___('Brand Name In English') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="custom-control custom-switch custom-control-inline">
                            <input name="status" value="1" type="checkbox" class="custom-control-input"
                                id="customSwitch2" @if($brand->status)checked=""@endif>
                            <label class="custom-control-label" value="1"
                                for="customSwitch2">{{ ___('Status') }}*</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <div class="mb-3">
                                <img id="currentImage" src="{{ getImageUrl($brand->image) }}" alt="Current Brand Image" class="img-thumbnail" style="max-width: 150px;">
                            </div>
                            <div class="custom-file">
                                <input name="image" type="file" class="custom-file-input" id="customFile" />
                                <label class="custom-file-label" for="customFile">{{ ___('Brand Image') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">{{ ___('Business Type') }}</label>
                            <select name="business_type_id" class="form-control" id="exampleFormControlSelect1">
                                @foreach ($businesses as $business )
                                    <option @if($brand->business_type_id == $business->id) selected @endif value="{{ $business->id }}">{{ $business->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
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
                    window.location.href = '{{ route('brands.edit',$brand->brand_id) }}';
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
        document.getElementById('customFile').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('currentImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>



@endsection
