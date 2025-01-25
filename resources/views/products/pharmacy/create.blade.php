@extends('layouts.app')


@section('title', ___('Create Product'))
@section('content')
    <style>
        .dark .select2-container--default .select2-selection--multiple,
        .dark .select2-container--default .select2-selection--single {
            background-color: #374151;
            border-color: #4b5563;
            color: #fff;
        }

        .dark .select2-container--default .select2-selection__choice {
            background-color: #1f2937;
            border-color: #4b5563;
            color: #fff;
        }

        .dark .select2-dropdown {
            background-color: #374151;
            border-color: #4b5563;
        }

        .dark .select2-results__option {
            color: #fff;
        }

        .dark .select2-container--default .select2-results__option[aria-selected="true"],
        .dark .select2-container--default .select2-results__option--highlighted {
            background-color: #1f2937;
            color: #fff;
        }

        .dark .select2-search__field {
            color: #fff !important;
            background-color: #374151;
        }

        .dark .select2-container--default .select2-selection__clear {
            color: #fff;
        }
    </style>
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Create Product') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="productForm" data-action="{{ route('pharmacy.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Product Name In Arabic') }}*</label>
                        <input name="name_ar" type="text" class="form-control" id="name" value="{{ old('name_ar') }}"
                            placeholder="{{ ___('Product Name In Arabic') }}">
                    </div>
                    <div class="col">
                        <label for="name">{{ ___('Product Name In English') }}*</label>
                        <input name="name_en" type="text" class="form-control" id="name" value="{{ old('name_en') }}"
                            placeholder="{{ ___('Product Name In English') }}">
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="brands">{{ ___('Brand') }}*</label>
                            <select name="brand_id" class="form-control" id="brand_id">
                                <option selected="" disabled="">{{ ___('Select Your Product Brand') }}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->brand_id }}">
                                        {{ $brand->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleFormControlSelect2">{{ ___('Categories') }}*</label>
                            <select name="categories[]" multiple="multiple" class="form-control js-example-basic-multiple"
                                id="exampleFormControlSelect2">
                                {{-- <option selected="" disabled="">{{ ___('Select Your Product Category') }}</option> --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}">
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
                        <input name="price" type="number" class="form-control" id="name" value="{{ old('price') }}"
                            placeholder="{{ ___('Product Price') }}">
                    </div>
                    <div class="col">
                        <label for="name">{{ ___('Product Quantity') }}*</label>
                        <input name="quantity" type="number" class="form-control" id="name" value="{{ old('quantity') }}"
                            placeholder="{{ ___('Product Quantity') }}">
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">{{ ___('Description In Arabic') }}*</label>
                            <div class="form-group" id="editorAr" style="min-height: 150px;">
                                <!-- Quill toolbar will be injected here -->
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
                                <!-- Quill toolbar will be injected here -->
                            </div>
                            <input type="hidden" name="description_en" id="description_en">
                        </div>
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
                                <input name="images[]" type="file" class="custom-file-input" id="customFile" multiple
                                    accept="image/*" />
                                <label class="custom-file-label" for="customFile">{{ ___('Product Images') }}</label>
                            </div>
                            <div class="image-preview-container mt-3 row" id="imagePreview"></div>
                        </div>
                    </div>
                </div>

                <br>

                <div id="variants-container">
                    <!-- Empty container - variants will be added here -->
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary" type="button" id="add-variant">{{ ___('Add Product Variant') }}</button>
                        <button type="button" id="clear-all" class="btn btn-secondary">{{ ___('Add Product Variant') }}</button>
                    </div>
                </div>

                <!-- Template for new variants (hidden in DOM) -->
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

                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
            <div id="validationErrors"></div>
        </div>
    </div>


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
                    window.location.href = '{{ route('pharmacy.products.create') }}';
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
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <script>
        // Initialize Quill editor for ar description
        const quillAr = new Quill('#editorAr', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
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

        // Update hidden input before form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            document.getElementById('description_ar').value = quillAr.root.innerHTML;
        });
        // Initialize Quill editor for en description
        const quillEn = new Quill('#editorEn', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
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

        // Update hidden input before form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            document.getElementById('description_en').value = quillEn.root.innerHTML;
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "{{ ___('Select Product Categories') }}",
                allowClear: true,
                // minimumResultsForSearch: Infinity
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
                    this.nextElementSibling.textContent = '{{ ___('Product Images') }}';
                }
            });
        });
    </script>

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
@endsection
