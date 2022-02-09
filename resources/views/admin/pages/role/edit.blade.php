@extends('admin.master')


@section('content')

    <h1>Create new category</h1>

    @if(session()->has('success'))
        <p class="alert alert-success">
            {{session()->get('success')}}
        </p>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{route('role.update',$role->id)}}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Name </label>
            <input name="name" value="{{$role->name}}" placeholder="Enter Category Name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >
        </div>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>

@endsection
