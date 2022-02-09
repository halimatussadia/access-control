@extends('admin.master')


@section('content')

    <h2>Create new Role</h2>

    @if(session()->has('success'))
        <p class="alert alert-success">
            {{session()->get('success')}}
        </p>
    @endif

    {{-- @if(session()->has('error'))
    <p class="alert alert-danger">
        {{session()->get('error')}}
    </p>
    @endif --}}

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <form action="{{route('role.store')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name </label>
                <input name="name" placeholder="Enter Role Name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >
            </div>

            <div>
                <h4 style="text-align: center">Permissions list</h4>
            </div>

            <div class="mt-5 mt-3" style="margin-left: 100px">
                @foreach($modules as $module)
                    <div class="mb-8">
                        <div>
                            <h6 style="margin-left: 25px">{{$module->name}}</h6>
                        </div>

                        @foreach($module->permissions as $permission)
                            <div style="margin-left: 50px; margin-top: 5px">
                                <input
                                    type="checkbox"
                                    name="permission_ids[]"
                                    value="{{$permission->id}}" multiple
                                />
                                <span style="font-size: 15px;margin-top: 2px">{{$permission->name}}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>


@endsection
