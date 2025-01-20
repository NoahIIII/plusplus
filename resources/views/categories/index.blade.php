@extends('layouts.app')
@section('title', ___('Main Categories'))
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">{{ ___('Main Categories') }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div id="table" class="table-editable">
                        <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid"
                            aria-describedby="user-list-page-info">
                            <thead>
                                <tr>
                                    <th>{{ ___('Name In Arabic') }}</th>
                                    <th>{{ ___('Name In English') }}</th>
                                    <th>{{ ___('Status') }}</th>
                                    <th>{{ ___('Created At') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            @if ($category->children->isNotEmpty())
                                                <a
                                                    href="{{ route('categories.index', ['parent_id' => $category->category_id, 'slug' => request()->segment(3)]) }}">
                                                    {{ $category->getTranslation('name', 'ar') }}
                                                </a>
                                            @else
                                                {{ $category->getTranslation('name', 'ar') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->children->isNotEmpty())
                                                <a
                                                    href="{{ route('categories.index', ['parent_id' => $category->category_id, 'slug' => request()->segment(3)]) }}">
                                                    {{ $category->getTranslation('name', 'en') }}
                                                </a>
                                            @else
                                                {{ $category->getTranslation('name', 'en') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->status)
                                                <span
                                                    class="badge dark-icon-light iq-bg-primary">{{ ___('Active') }}</span>
                                            @else
                                                <span class="badge iq-bg-danger">{{ ___('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->formatted_created_at }}</td>
                                        <td>
                                            <div class="flex align-items-center list-user-action">
                                                @can('manage-categories')
                                                    <!-- In your Blade view -->
                                                    <x-delete-button :route="route('categories.destroy', $category)" title="{{ ___('Delete') }}" />
                                                @endcan
                                                @can('manage-categories')
                                                    <a class="iq-bg-primary ml-2" data-placement="top" title=""
                                                        data-original-title="{{ ___('Edit') }}"
                                                        href="{{ route('categories.edit', $category->category_id) }}">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row justify-content-between mt-3">
                            <!-- Page Info -->
                            <div id="user-list-page-info" class="col-md-6">
                                <span>{{ ___('Showing') }} {{ $categories->firstItem() }} {{ ___('to') }}
                                    {{ $categories->lastItem() }} {{ ___('of') }} {{ $categories->total() }}
                                    {{ ___('entries') }}</span>
                            </div>

                            <!-- Pagination -->
                            <div class="col-md-6">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        {{ $categories->links('pagination::bootstrap-4') }}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
