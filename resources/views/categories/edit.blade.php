@extends('layouts.app')

@section('title', ___('Edit Category'))
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Edit Category') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            @if ($category->children()->exists())
                <div class="alert text-white bg-primary" role="alert">
                    <div class="iq-alert-icon">
                        <i class="ri-alert-line"></i>
                    </div>
                    <div class="iq-alert-text">
                        {{ ___('This category has children. You cannot change its level until you remove the child categories.') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
            <form id="categoryForm" data-action="{{ route('categories.update', $category) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Category Names -->
                <div class="row">
                    <div class="col">
                        <label for="name_ar">{{ ___('Category Name In Arabic') }}*</label>
                        <input name="name_ar" type="text" class="form-control" id="name_ar"
                            placeholder="{{ ___('Category Name In Arabic') }}"
                            value="{{ $category->getTranslation('name', 'ar') }}">
                    </div>
                    <div class="col">
                        <label for="name_en">{{ ___('Category Name In English') }}*</label>
                        <input name="name_en" type="text" class="form-control" id="name_en"
                            placeholder="{{ ___('Category Name In English') }}"
                            value="{{ $category->getTranslation('name', 'en') }}">
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
                                    <option value="{{ $business->id }}"
                                        {{ $category->business_type_id == $business->id ? 'selected' : '' }}>
                                        {{ $business->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Category Level and Parent Category -->
                <div class="row">
                    @if (!$category->children()->exists())
                        <div class="col">
                            <label for="level">{{ ___('Category Level') }}</label>
                            <select name="level" id="level" class="form-control">
                                <option value="1" {{ $category->level == 1 ? 'selected' : '' }}>
                                    {{ ___('Main Category') }}</option>
                                <option value="2" {{ $category->level == 2 ? 'selected' : '' }}>
                                    {{ ___('Subcategory') }}</option>
                                <option value="3" {{ $category->level == 3 ? 'selected' : '' }}>
                                    {{ ___('Sub-Subcategory') }}</option>
                            </select>
                        </div>
                    @endif
                    <div style="display: {{ $category->level > 1 ? 'block' : 'none' }};" class="col"
                        id="parentCategoryContainer">
                        <label for="parent_id">{{ ___('Parent Category') }}</label>
                        <select name="parent_id" class="form-control" id="parent_id"
                            {{ $category->level == 1 ? 'disabled' : '' }}>
                            <option value="">{{ ___('Select Parent Category') }}</option>
                            @foreach ($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->category_id }}"
                                    {{ $category->parent_id == $parentCategory->category_id ? 'selected' : '' }}>
                                    {{ $parentCategory->getTranslation('name', app()->getLocale()) }}
                                </option>
                            @endforeach
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
                                id="customSwitch2" {{ $category->status ? 'checked' : '' }}>
                            <label class="custom-control-label" value="1"
                                for="customSwitch2">{{ ___('Status') }}*</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <div class="mb-3">
                                <img id="currentImage" src="{{ getImageUrl($category->image) }}" alt="Current category Image" class="img-thumbnail" style="max-width: 150px;">
                            </div>
                            <div class="custom-file">
                                <input name="image" type="file" class="custom-file-input" id="customFile" />
                                <label class="custom-file-label" for="customFile">{{ ___('Category Image') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ ___('Update') }}</button>
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
                    window.location.href = '{{ route('categories.edit', $category->category_id) }}';
                    toastr.success('{{ __('messages.updated') }}');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                    var errorsReturned = xhr.responseJSON.errors;
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

            if (level === 1) {
                parentCategoryContainer.style.display = 'none';
                parentSelect.disabled = true;
                businessTypeSelect.disabled = false;
                businessTypeContainer.style.display = 'block';
                return;
            }

            parentCategoryContainer.style.display = 'block';
            parentSelect.disabled = false;
            businessTypeSelect.disabled = true;
            businessTypeContainer.style.display = 'none';

            if (level > 1) {
                const locale = '{{ app()->getLocale() }}';
                const currentCategoryId = {{ $category->category_id }};
                fetch(`/${locale}/categories/get/by-level?level=${level - 1}`)
                    .then(response => response.json())
                    .then(data => {
                        parentSelect.innerHTML =
                            '<option value="">{{ ___('Select Parent Category') }}</option>';
                        data.forEach(category => {
                            if (category.id !== currentCategoryId) {
                                parentSelect.innerHTML +=
                                    `<option value="${category.id}">${category.name}</option>`;
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching parent categories:', error));
            }
        });
    </script>
@endsection
