@extends('layouts.admin.app')

@section('content')

    <div>
        <h2>{{__('Movies')}}</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.movies.index') }}">{{__('Movies')}}</a></li>
        <li class="breadcrumb-item">{{__('Edit')}}</li>
    </ul>

    <div class="row">

        <div class="col-md-12">

            <div class="tile shadow">

                <form method="post" action="{{ route('admin.movies.update', $movie->id) }}">
                    @csrf
                    @method('put')

                    @include('admin.partials._errors')

                    {{--name--}}
                    <div class="form-group">
                        <label>{{__('movie name')}} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $movie->name) }}"
                               required>
                    </div>

                    <h5>{{__('Permissions')}} <span class="text-danger">*</span></h5>

                    @php
                        $models = ['movies', 'admins'];
                    @endphp

                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{__('Model')}}</th>
                            <th>{{__('Permissions')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($models as $model)
                            <tr>
                                <td>{{__($model)}}</td>
                                <td>
                                    <div class="animated-checkbox mx-2" style="display:inline-block;">
                                        <label class="m-0">
                                            <input type="checkbox" value="" name="" class="all-movies">
                                            <span class="label-text">{{__('All')}}</span>
                                        </label>
                                    </div>

                                    @php
                                        $permissionMaps = ['create', 'read', 'update', 'delete'];
                                    @endphp

                                    @foreach ($permissionMaps as $permissionMap)
                                        <div class="animated-checkbox mx-2" style="display:inline-block;">
                                            <label class="m-0">
                                                <input type="checkbox" value="{{ $permissionMap . '_' . $model }}"
                                                       name="permissions[]"
                                                       {{ $movie->hasPermission( $permissionMap . '_' . $model) ? 'checked' : '' }} class="movie">
                                                <span class="label-text">{{__($permissionMap)}}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table><!-- end of table -->

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> {{__('Save')}}
                        </button>
                    </div>

                </form><!-- end of form -->

            </div><!-- end of tile -->

        </div><!-- end of col -->

    </div><!-- end of row -->

@endsection

