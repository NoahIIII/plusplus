@extends('layouts.app')


@section('title', ___('Create Brand'))
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
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">{{ ___('Business Type') }}</label>
                            <select name="business_type_id" class="form-control" id="exampleFormControlSelect1">
                                <option selected="" disabled="">{{ ___('Select Your Category Business Type') }}</option>
                                @foreach ($businesses as $business )
                                    <option value="{{ $business->id }}">{{ $business->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                        </div>
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

                    <div class="col">
                        <div class="form-group">
                            <div class="custom-file">
                                <input name="image" type="file" class="custom-file-input" id="customFile" />
                                <label class="custom-file-label" for="customFile">{{ ___('Brand Image') }}</label>
                            </div>
                            <div class="image-preview-container mt-3 row" id="imagePreview"></div>
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File input change handler
        document.getElementById('customFile').addEventListener('change', function(e) {
            // Update label text
            const label = this.nextElementSibling;
            label.textContent = this.files.length + ' files selected';

            // Clear previous previews
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = '';

            // Create image previews
            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'col-md-2 mb-3';
                    div.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top preview-image" alt="Preview">
                        <div class="card-body p-2">
                            <small class="text-muted">${file.name}</small>
                        </div>
                    </div>
                `;
                    previewContainer.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        });

        // Reset label when no files selected
        document.getElementById('customFile').addEventListener('click', function() {
            if (this.files.length === 0) {
                this.nextElementSibling.textContent = '{{ ___('Brand Image') }}';
            }
        });
    });
</script>
@endsection
