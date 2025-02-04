@extends('layouts.app')


@section('title', ___('Create Category'))
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Create Category') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="categoryForm" data-action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Category Names -->
                <div class="row">
                    <div class="col">
                        <label for="name_ar">{{ ___('Category Name In Arabic') }}*</label>
                        <input name="name_ar" type="text" class="form-control" id="name_ar"
                            placeholder="{{ ___('Category Name In Arabic') }}">
                    </div>
                    <div class="col">
                        <label for="name_en">{{ ___('Category Name In English') }}*</label>
                        <input name="name_en" type="text" class="form-control" id="name_en"
                            placeholder="{{ ___('Category Name In English') }}">
                    </div>
                </div>
                <br>

                <!-- Business Type -->
                <div class="row">
                    <div class="col">
                        <div id="businessTypeContainer" class="form-group">
                            <label for="business_type_id">{{ ___('Business Type') }}</label>
                            <select name="business_type_id" class="form-control" id="business_type_id">
                                <option selected="" disabled="">{{ ___('Select Your Brand Business Type') }}</option>
                                @foreach ($businesses as $business)
                                    <option value="{{ $business->id }}">
                                        {{ $business->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Category Level and Parent Category -->
                <div class="row">
                    <div class="col">
                        <label for="level">{{ ___('Category Level') }}</label>
                        <select name="level" id="level" class="form-control">
                            <option value="1">{{ ___('Main Category') }}</option>
                            <option value="2">{{ ___('Subcategory') }}</option>
                            <option value="3">{{ ___('Sub-Subcategory') }}</option>
                        </select>
                    </div>
                    <div style="display: none;" class="col" id="parentCategoryContainer">
                        <label for="parent_id">{{ ___('Parent Category') }}</label>
                        <select name="parent_id" class="form-control" id="parent_id" disabled>


                        </select>
                    </div>
                </div>
                <br>

                <!-- Status -->
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
                                <label class="custom-file-label" for="customFile">{{ ___('Category Image') }}</label>
                            </div>
                            <div class="image-preview-container mt-3 row" id="imagePreview"></div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ ___('Submit') }}</button>
                </div>
            </form>


            <div id="validationErrors"></div>
        </div>
    </div>


    <script>
        var autoForm = $("#categoryForm");

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
                    window.location.href = '{{ route('categories.create') }}';
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
        document.getElementById('level').addEventListener('change', function() {
            const level = parseInt(this.value);
            const parentSelect = document.getElementById('parent_id');
            const parentCategoryContainer = document.getElementById('parentCategoryContainer');
            const businessTypeSelect = document.getElementById('business_type_id');
            const businessTypeContainer = document.getElementById('businessTypeContainer');


            // Hide the Parent Category dropdown for Main Category (level 1)
            if (level === 1) {
                parentCategoryContainer.style.display = 'none';
                parentSelect.disabled = true;
                businessTypeSelect.disabled = false;
                businessTypeContainer.style.display = 'block';
                return;
            }

            // Show Parent Category dropdown for Subcategory (level 2) and Sub-Subcategory (level 3)
            parentCategoryContainer.style.display = 'block';
            parentSelect.disabled = false;
            businessTypeSelect.disabled = true;
            businessTypeContainer.style.display = 'none';

            // get current locale
            if (level > 1) {
                const locale = '{{ app()->getLocale() }}';
                // Fetch categories of the appropriate level for Parent Category
                fetch(`/${locale}/categories/get/by-level?level=${level - 1}`)
                    .then(response => response.json())
                    // console.log('Response:', response);
                    .then(data => {
                        parentSelect.innerHTML =
                            '<option value="">{{ ___('Select Parent Category') }}</option>';
                        data.forEach(category => {
                            parentSelect.innerHTML +=
                                `<option value="${category.id}">${category.name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error fetching parent categories:', error));
            }
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
                    this.nextElementSibling.textContent = '{{ ___('Category Image') }}';
                }
            });
        });
    </script>

@endsection
