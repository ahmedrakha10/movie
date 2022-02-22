@extends('layouts.admin.app')

@section('content')

    <div>
        <h2>{{__('Supervisors')}}</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">{{__('Supervisors')}}</a></li>
        <li class="breadcrumb-item">{{__('Create')}}</li>
    </ul>

    <div class="row">

        <div class="col-md-12">

            <div class="tile shadow">

                <form method="post" action="{{ route('admin.admins.store') }}">
                    @csrf
                    @method('post')

                    @include('admin.partials._errors')

                    {{--name--}}
                    <div class="form-group">
                        <label>{{__('Name')}} <span class="text-danger">*</span></label>
                        <input type="text" name="name" autofocus class="form-control" value="{{ old('name') }}" required>
                    </div>

                    {{--email--}}
                    <div class="form-group">
                        <label>{{__('Email')}} <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    {{--password--}}
                    <div class="form-group">
                        <label>{{__('Password')}}<span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" value="" required>
                    </div>

                    {{--password_confirmation--}}
                    <div class="form-group">
                        <label>{{__('Password confirmation')}} <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" value="" required>
                    </div>

                    {{--role_id--}}
                    <div class="form-group">
                        <label>{{__('Roles')}}<span class="text-danger">*</span></label>
                        <select name="role_id" class="form-control select2" required>
                            <option value="">{{__('Choose role')}}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $role->id == old('role_id') ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>{{__('Create')}}</button>
                    </div>

                </form><!-- end of form -->

            </div><!-- end of tile -->

        </div><!-- end of col -->

    </div><!-- end of row -->

@endsection
