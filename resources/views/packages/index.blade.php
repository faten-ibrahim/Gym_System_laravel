@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Training Packages</h2>
    <br>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">name</th>
                <th scope="col"># Sessions</th>
                <th scope="col">Price</th>
                <th scope="col">Created At</th>
                <th class="col">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($packages as $package)
            <tr>
                <td>{{$package->id}}</td>
                <td>{{$package->name}}</td>
                <td>{{$package->sessionsNumber}}</td>
                <td>{{$package->price}} $</td>
                <td>{{date('Y-m-d', strtotime($package->created_at)) }}</td>

                <td>
                    <a href="{{route('packages.edit',['package' => $package->id])}}" class="btn btn-success"><i class="fa fa-edit"></i><span>Edit</span></a>

                    <form action="{{route('packages.delete',['package' => $package->id])}}" method="Post" style="display:inline; float:left; margin-right:10px;">
                        @csrf
                        @method('DELETE')


                        <button type="submit" onclick="return confirm('Are you Sure !')" class="btn btn-danger"><i class="fa fa-times"></i><span>Delete</span></button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <br>
    <a class="btn btn-info" href="{{route('packages.create')}}"><i class="fa fa-plus"></i><span>Add New Package</span></a>
    <br>
    <br>

</div>

@endsection
