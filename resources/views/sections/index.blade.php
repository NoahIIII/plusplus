@extends('layouts.app')
@section('title',  ___('Sections') )
@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">{{ ___('Sections') }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div id="table" class="table-editable">
                        <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid" aria-describedby="user-list-page-info">
                            <thead>
                                <tr>
                                    <th>{{ ___('Name In Arabic') }}</th>
                                    <th>{{ ___('Name In English') }}</th>
                                    <th>{{ ___('First Color') }}</th>
                                    <th>{{ ___('Second Color') }}</th>
                                    <th>{{ ___('Status') }}</th>
                                    <th>{{ ___('Created At') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sections as $section)
                                <tr>
                                    <td>{{ $section->getTranslation('name', 'ar') }}</td>
                                    <td>{{ $section->getTranslation('name', 'en') }}</td>
                                    <td>
                                        <span style="display:inline-block; width:20px; height:20px; background-color: {{ $section->first_color }};"></span>
                                        {{ $section->first_color }}
                                    </td>
                                    <td>
                                        <span style="display:inline-block; width:20px; height:20px; background-color: {{ $section->second_color }};"></span>
                                        {{ $section->second_color }}
                                    </td>
                                    <td>
                                        @if($section->status)
                                        <span class="badge dark-icon-light iq-bg-primary">{{ ___('Active') }}</span>
                                        @else
                                        <span class="badge iq-bg-danger">{{ ___('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $section->formatted_created_at }}</td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            @can('manage-sections')
                                            <x-delete-button :route="route('sections.destroy', $section)" title="Delete" />
                                            @endcan
                                            @can('manage-sections')
                                                <a class="iq-bg-primary ml-2" data-placement="top" title="" data-original-title="{{ ___('Edit') }}" href="{{ route('sections.edit', $section->section_id) }}">
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
                                <span>{{ ___('Showing') }} {{ $sections->firstItem() }} {{ ___('to') }} {{ $sections->lastItem() }} {{ ___('of') }} {{ $sections->total() }} {{ ___('entries') }}</span>
                            </div>

                            <!-- Pagination -->
                            <div class="col-md-6">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        {{ $sections->links('pagination::bootstrap-4') }}
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
