@extends('layouts.app')


@section('title', ___('Create Discount'))
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
</style>
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Create Discount') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="discountForm" data-action="{{ route('discounts.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="discount_type">{{ ___('Discount Type') }}</label>
                            <select name="type" class="form-control" id="discount_type">
                                <option selected="" disabled="">{{ ___('Select The Discount Type') }}</option>
                                <option value="percentage">{{ ___('Percentage') }}</option>
                                <option value="fixed">{{ ___('Fixed') }}</option>
                                <option value="buy_one_get_one">{{ ___('Buy One Get One') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <label for="name">{{ ___('Discount Value') }}*</label>
                        <input name="value" type="number" class="form-control" id="name" value="{{ old('value') }}"
                            placeholder="{{ ___('Discount Value') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleInputdate">{{ ___('Start Date') }}</label>
                            <input type="date" name="start_date" class="form-control" id="exampleInputdate"
                                value="{{ old('start_date') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleInputdate">{{ ___('End Date') }}</label>
                            <input type="date" name="end_date" class="form-control" id="exampleInputdate"
                                value="{{ old('end_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">{{ ___('Business Type') }}</label>
                            <select name="business_type_id" class="form-control" id="exampleFormControlSelect1">
                                <option selected="" disabled="">{{ ___('Select Your Category Business Type') }}
                                </option>
                                @foreach ($businesses as $business)
                                    <option value="{{ $business->id }}">
                                        {{ $business->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleFormControlSelect2">{{ ___('Products') }}*</label>
                            <select name="product_ids[]" multiple class="form-control js-example-basic-multiple"
                                id="exampleFormControlSelect2">

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

                    </div>
                </div>

                <br>
                <div class="form-group">
                    <button form="discountForm" type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
            <div id="validationErrors"></div>
        </div>
    </div>

    {{-- Ajax Script --}}
    <script>
        var autoForm = $("#discountForm");

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
                    window.location.href = '{{ route('discounts.create') }}';
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
    {{-- Select 2 --}}
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "{{ ___('Select Product Categories') }}",
                allowClear: true,
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity
            });
        });
    </script>
    {{-- fetch products --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const businessTypeSelect = document.getElementById('exampleFormControlSelect1');
            const productSelect = document.getElementById('exampleFormControlSelect2');

            businessTypeSelect.addEventListener('change', async function() {
                const businessTypeId = this.value;

                // Clear previous options
                productSelect.innerHTML =
                    '<option value="" disabled>{{ ___('Loading products...') }}</option>';
                productSelect.disabled = true;

                try {
                    const response = await fetch(`/products/${businessTypeId}`);
                    const {
                        data,
                        message,
                        status
                    } = await response.json();

                    // Handle API-level errors
                    if (status !== 200) {
                        throw new Error(message || 'Failed to fetch products');
                    }

                    productSelect.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(product => {
                            const option = new Option(
                                product.name, // Display text
                                product.id, // Value
                                false, // Not selected
                                false // Not selected
                            );
                            productSelect.add(option);
                        });

                        productSelect.disabled = false;

                        // Reinitialize Select2
                        $(productSelect).select2({
                            placeholder: "{{ ___('Select products') }}",
                            allowClear: true,
                            theme: 'bootstrap-5',
                            minimumResultsForSearch: Infinity
                        });
                    } else {
                        productSelect.innerHTML =
                            `<option value="" disabled>{{ ___('No products available') }}</option>`;
                    }

                } catch (error) {
                    console.error('Error:', error);
                    productSelect.innerHTML =
                        `<option value="" disabled>{{ ___('Error: ') }}${error.message}</option>`;
                } finally {
                    productSelect.disabled = false;
                }
            });
        });
    </script>
@endsection
