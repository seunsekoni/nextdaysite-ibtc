@extends('_partials.auth')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>
                Add New Student
            </h2>
            <form action="{{ route('user.update', [$user]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" id="firstName" placeholder="John">
                </div>
                <div class="mb-3">
                    <label for="lasttName" class="form-label">Last Name</label>
                    <input type="text" name="lastName" value="{{ $user->lastName }}" class="form-control" id="lasttName" placeholder="Doe">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                    <input type="email" value="{{ $user->email }}" readonly name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" value="{{ $user->phone }}" name="phone" class="form-control" id="phone" placeholder="08077888889">
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile01" >Upload</label>
                    <input type="file" accept="image/*" name="photo" class="form-control" id="inputGroupFile01">
                </div>
                <div class="mb-3 form-check">
                    <input name="isCordinator" {{ $user->isCordinator == 1 ? 'checked' : '' }} value="1" type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label"  for="exampleCheck1">Make a Cordinator</label>
                </div>
                <div class="avatar-wrapper">
                    @if($user->photo)
                        <img style="height:250px; width: 250px" src="{{ asset('storage/' . $user->photo) }}" class="img-fluid rounded mx-auto d-block" alt="...">
                    @else
                        <img src="{{ asset('images/placeholder.svg') }}" class="rounded mx-auto d-block" alt="...">
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                
            </form>
        </div>
    </div>
@endsection