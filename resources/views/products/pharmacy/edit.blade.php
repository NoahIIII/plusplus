@extends('layouts.app')
@section('title', ___('Edit Product'))
@section('content')
    <style>
        .dark .select2-container--bootstrap-5 {
            --select2-bg: #221f3a;

            --select2-color: #ffffff;

            --select2-border-color: #3f3a5f;
            --select2-highlight: #4f46e5;
        }

        .dark .select2-selection {
            background: var(--select2-bg) !important;
            border-color: var(--select2-border-color) !important;
            color: var(--select2-color) !important;

        }

        .dark .select2-dropdown {
            background: #24243e !important;
            border-color: var(--select2-border-color) !important;
        }

        .dark .select2-results__option {
            background: #24243e !important;
            color: var(--select2-color) !important;

        }

        .dark .select2-results__option--highlighted {
            background: var(--select2-highlight) !important;
            color: var(--select2-color) !important;

        }

        .dark .select2-search__field {
            color: var(--select2-color) !important;

            background: #221f3a !important;
        }

        .dark .select2-selection--multiple .select2-selection__choice {
            color: var(--select2-color) !important;
        }

        .dark .select2-selection__placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        .dark .select2-container--disabled .select2-selection {
            color: #d1d5db !important;
        }

        .dark .select2-selection__rendered {
            color: #ffffff !important;
        }

        .dark .select2-selection {
            background: var(--select2-bg) !important;
            border-color: var(--select2-border-color) !important;
            color: var(--select2-color) !important;
        }

        .dark .select2-selection__rendered {
            color: #ffffff !important;
        }

        .delete-button-container {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 10;
        }

        .image-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .delete-image {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }

        .card {
            overflow: hidden;
        }


        .delete-image {
            width: 28px;
            height: 28px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }

        .position-absolute.translate-middle {
            transform: translate(50%, -50%) !important;
        }
    </style>
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Edit Product') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="productForm" data-action="{{ route('pharmacy.products.update', $product) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Product Name In Arabic') }}*</label>
                        <input name="name_ar" type="text" class="form-control" id="name"
                            value="{{ $product->getTranslation('name', 'ar') }}"
                            placeholder="{{ ___('Product Name In Arabic') }}">
                    </div>
                    <div class="col">
                        <label for="name">{{ ___('Product Name In English') }}*</label>
                        <input name="name_en" type="text" class="form-control" id="name"
                            value="{{ $product->getTranslation('name', 'en') }}"
                            placeholder="{{ ___('Product Name In English') }}">
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="brands">{{ ___('Brand') }}*</label>
                            <select name="brand_id" class="form-control" id="brand_id">
                                <option value @if(!$product->brand_id) selected="" @endif>{{ ___('Select Your Product Brand') }}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->brand_id }}"
                                        @if ($product->brand_id == $brand->brand_id) selected @endif>
                                        {{ $brand->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleFormControlSelect2">{{ ___('Categories') }}*</label>
                            <select name="categories[]" class="form-control js-example-basic-multiple" id="exampleFormControlSelect2">
                                {{-- <option selected="" disabled="">{{ ___('Select Your Product Category') }}</option> --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}"
                                        @if (in_array($category->category_id, $productCategories)) selected @endif>
                                        {{ $category->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Product Price') }}*</label>
                        <input name="price" type="number" class="form-control" id="name"
                            value="{{ $product->price }}" placeholder="{{ ___('Product Price') }}">
                    </div>
                    <div class="col">
                        <label for="name">{{ ___('Product Quantity') }}*</label>
                        <input name="quantity" type="number" class="form-control" id="name"
                            value="{{ $product->quantity }}" placeholder="{{ ___('Product Quantity') }}">
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">{{ ___('Description In Arabic') }}*</label>
                            <div class="form-group" id="editorAr" style="min-height: 150px;">
                                {!! $product->getTranslation('description', 'ar') !!}
                            </div>
                            <input type="hidden" name="description_ar" id="description_ar">
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">{{ ___('Description In English') }}*</label>
                            <div class="form-group" id="editorEn" style="min-height: 150px;">
                                {!! $product->getTranslation('description', 'en') !!}
                            </div>
                            <input type="hidden" name="description_en" id="description_en">
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col">
                        <div class="custom-control custom-switch custom-control-inline">
                            <input type="hidden" name="status" value="0">
                            <input name="status" value="1" type="checkbox" class="custom-control-input"
                                id="customSwitch2" @if ($product->status) checked="" @endif>
                            <label class="custom-control-label" value="1"
                                for="customSwitch2">{{ ___('Status') }}*</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <div class="custom-file">
                                <input name="images[]" type="file" class="custom-file-input" id="customFile" multiple
                                    accept="image/*" />
                                <label class="custom-file-label" for="customFile">{{ ___('Product Images') }}</label>
                            </div>
                            <div class="image-preview-container mt-3 row" id="imagePreview">
                                @foreach ($product->media as $media)
                                    <div class="col-md-2 mb-3 existing-image" data-media-id="{{ $media->id }}">
                                        <div class="card position-relative">
                                            <div class="delete-button-container">
                                                <button type="button" class="btn btn-primary btn-sm delete-image"
                                                    data-type="existing">
                                                    <i class="ri-delete-bin-line pr-0"></i>
                                                </button>
                                            </div>
                                            <img src="{{ getImageUrl($media->media) }}"
                                                class="card-img-top preview-image rounded">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div id="variants-container">
                    @foreach (old('variants', $product->variants) as $index => $variant)
                        <div class="variant-section mb-4">
                            <button type="button" class="btn btn-primary mb-3 remove-variant">
                                {{ ___('Remove Variant') }}
                            </button>
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>{{ ___('Package Type') }}*</label>
                                        <select name="variants[{{ $index }}][package_type]" class="form-control">
                                            <option value="" disabled>{{ __('Select The Package') }}</option>
                                            @foreach ($packageTypes as $type)
                                                <option value="{{ $type }}"
                                                    {{ $variant['package_type'] == $type ? 'selected' : '' }}>
                                                    {{ __('enums.package_type.' . $type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>{{ ___('Unit Type') }}*</label>
                                        <select name="variants[{{ $index }}][unit_type]" class="form-control">
                                            <option value="" disabled>{{ __('Select The Unit') }}</option>
                                            @foreach ($unitTypes as $type)
                                                <option value="{{ $type }}"
                                                    {{ $variant['unit_type'] == $type ? 'selected' : '' }}>
                                                    {{ __('enums.unit_type.' . $type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col">
                                    <div class="form-group">
                                        <label>{{ __('Unit Quantity') }}</label>
                                        <input name="variants[{{ $index }}][unit_quantity]" type="number"
                                            class="form-control" value="{{ $variant['unit_quantity'] }}"
                                            placeholder="{{ __('100g') }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>{{ ___('Unit Price') }}*</label>
                                        <input name="variants[{{ $index }}][price]" type="number"
                                            class="form-control" value="{{ $variant['price'] }}"
                                            placeholder="{{ __('Unit Price') }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>{{ ___('Quantity In Stock') }}*</label>
                                        <input name="variants[{{ $index }}][stock_quantity]" type="number"
                                            class="form-control" value="{{ $variant['stock_quantity'] }}"
                                            placeholder="{{ __('Quantity In Stock') }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    @endforeach
                </div>

                <template id="variant-template">
                    <div class="variant-section mb-4">
                        <button type="button" class="btn btn-primary mb-3 remove-variant">
                            {{ ___('Remove Variant') }}
                        </button>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ ___('Package Type') }}*</label>
                                    <select name="variants[INDEX][package_type]" class="form-control">
                                        <option value="" selected disabled>{{ __('Select The Package') }}</option>
                                        @foreach ($packageTypes as $type)
                                            <option value="{{ $type }}">{{ __('enums.package_type.' . $type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ ___('Unit Type') }}*</label>
                                    <select name="variants[INDEX][unit_type]" class="form-control">
                                        <option value="" selected disabled>{{ __('Select The Unit') }}</option>
                                        @foreach ($unitTypes as $type)
                                            <option value="{{ $type }}">{{ __('enums.unit_type.' . $type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ __('Unit Quantity') }}</label>
                                    <input name="variants[INDEX][unit_quantity]" type="number" class="form-control"
                                        placeholder="{{ __('100g') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ ___('Unit Price') }}*</label>
                                    <input name="variants[INDEX][price]" type="number" class="form-control"
                                        placeholder="{{ __('Unit Price') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ ___('Quantity In Stock') }}*</label>
                                    <input name="variants[INDEX][stock_quantity]" type="number" class="form-control"
                                        placeholder="{{ __('Quantity In Stock') }}">
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </template>

                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary" type="button"
                            id="add-variant">{{ ___('Add Product Variant') }}</button>
                        <button type="button" id="clear-all"
                            class="btn btn-secondary">{{ ___('Clear All Variants') }}</button>
                    </div>
                </div>

                <br>


                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
            <div id="validationErrors"></div>
        </div>
    </div>
    {{-- Ajax Script --}}
    <script>
        var autoForm = $("#productForm");

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
                    window.location.href =
                        '{{ route('pharmacy.products.edit', $product->product_id) }}';
                    toastr.success('{{ __('messages.added') }}');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                    var errorsReturned = xhr.responseJSON.errors;
                    var errorsMessage = xhr.responseJSON.message;
                    if (errorsMessage != '') {
                        toastr.error(errorsMessage);
                    }
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
    {{-- Select2 Script --}}
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "{{ ___('Select Product Categories') }}",
                allowClear: true,
                theme: 'bootstrap-5',
                // minimumResultsForSearch: Infinity
            });
        });
    </script>
    {{-- quill script --}}
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill editor for  description
        const quillAr = new Quill('#editorAr', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video', 'formula'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }, {
                        'list': 'check'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent
                    [{
                        'direction': 'rtl'
                    }], // text direction

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // custom dropdown
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean']
                ]
            }
        });
        const quillEn = new Quill('#editorEn', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video', 'formula'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }, {
                        'list': 'check'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }],
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean']
                ]
            }
        });

        // Update hidden inputs before form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            document.getElementById('description_ar').value = quillAr.root.innerHTML;
            document.getElementById('description_en').value = quillEn.root.innerHTML;
        });
    });
    </script>
    {{-- image preview script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewContainer = document.getElementById('imagePreview');
            const fileInput = document.getElementById('customFile');
            let newFilesMap = new Map(); // Track new files with unique IDs

            // Handle all image deletions
            previewContainer.addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.delete-image');
                if (!deleteBtn) return;

                const isExisting = deleteBtn.dataset.type === 'existing';
                const imageContainer = deleteBtn.closest(isExisting ? '.existing-image' : '.new-image');

                if (confirm('{{ ___("Are you sure you want to delete this image?") }}')) {
                    if (isExisting) {
                        // Track existing image deletion
                        const mediaId = imageContainer.dataset.mediaId;
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'deleted_media_ids[]';
                        hiddenInput.value = mediaId;
                        document.getElementById('productForm').appendChild(hiddenInput);
                    } else {
                        // Remove new file from tracking
                        const fileId = imageContainer.dataset.fileId;
                        newFilesMap.delete(fileId);
                        updateFileInput();
                    }

                    imageContainer.remove();
                    updateLabel();
                }
            });

            // Handle new file selection
            fileInput.addEventListener('change', function(e) {
                const files = Array.from(this.files);

                // Clear previous new previews
                previewContainer.querySelectorAll('.new-image').forEach(el => el.remove());

                // Add new files to map with unique IDs
                files.forEach(file => {
                    const fileId = Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                    newFilesMap.set(fileId, file);
                    createPreview(file, fileId);
                });

                updateFileInput();
                updateLabel();
            });

            function createPreview(file, fileId) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'col-md-2 mb-3 new-image';
                    div.dataset.fileId = fileId;
                    div.innerHTML = `
                        <div class="card position-relative">
                            <div class="delete-button-container">
                                <button type="button" class="btn btn-primary btn-sm delete-image" data-type="new">
                                    <i class="ri-delete-bin-line pr-0"></i>
                                </button>
                            </div>
                            <img src="${e.target.result}" class="card-img-top preview-image rounded">
                        </div>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                newFilesMap.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }

            function updateLabel() {
                const label = fileInput.nextElementSibling;
                label.textContent = newFilesMap.size > 0
                    ? `${newFilesMap.size} {{ ___('new files selected') }}`
                    : '{{ ___('Product Images') }}';
            }
        });
        </script>
    {{-- variants --}}
    <script>
        function toggleClearButton() {
            const clearBtn = document.getElementById('clear-all');
            const hasVariants = document.getElementById('variants-container').children.length > 0;
            clearBtn.style.display = hasVariants ? 'inline-block' : 'none';
        }
        // Add Variant
        document.getElementById('add-variant').addEventListener('click', function() {
            const container = document.getElementById('variants-container');
            const template = document.getElementById('variant-template').content;
            const index = container.children.length;

            const clone = document.importNode(template, true);
            const html = clone.querySelector('.variant-section').outerHTML
                .replace(/INDEX/g, index);

            container.insertAdjacentHTML('beforeend', html);
            toggleClearButton();
        });

        // Remove Individual Variant (using event delegation)
        document.getElementById('variants-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variant')) {
                e.target.closest('.variant-section').remove();
                reindexVariants();
                toggleClearButton();
            }
        });

        // Clear All Variants
        document.getElementById('clear-all').addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all variants?')) {
                document.getElementById('variants-container').innerHTML = '';
                toggleClearButton();
            }
        });

        // Reindex Variants After Removal
        function reindexVariants() {
            const container = document.getElementById('variants-container');
            container.querySelectorAll('.variant-section').forEach((section, index) => {
                // Update all inputs/selects
                section.querySelectorAll('[name]').forEach(element => {
                    const name = element.getAttribute('name')
                        .replace(/\[\d+\]/g, `[${index}]`);
                    element.setAttribute('name', name);
                });

                // Update all labels/IDs if needed
                section.querySelectorAll('label').forEach(label => {
                    const forAttr = label.getAttribute('for');
                    if (forAttr) label.setAttribute('for', forAttr.replace(/_(\d+)_/g, `_${index}_`));
                });

                section.querySelectorAll('input, select').forEach(input => {
                    const id = input.getAttribute('id');
                    if (id) input.setAttribute('id', id.replace(/_(\d+)_/g, `_${index}_`));
                });
            });
        }
        document.addEventListener('DOMContentLoaded', toggleClearButton);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Run reindexing for initial variants
            reindexVariants();

            // Add click handler for existing remove buttons
            document.querySelectorAll('.remove-variant').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.variant-section').remove();
                    reindexVariants();
                    toggleClearButton();
                });
            });
        });
    </script>
@endsection
