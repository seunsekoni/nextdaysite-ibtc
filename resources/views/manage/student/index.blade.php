@extends('_partials.auth')
@section('content')

    <div class="row">
        <div class="col md-12">
            <h2>
                All Users
            </h2>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Cordinator</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->lastName }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->isCordinator }}</td>
                        <td>
                            <div class="btn-group-justified text-center" role="group">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('user.show', [$user]) }}" style="margin-right: 10px;" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                </div>  
                                <div class="btn-group" role="group">
                                    <form action="{{ route('user.destroy', [$user] ) }}" method="post" onsubmit="return confirm('Do you really want to delete this user?');" >
                                        @csrf
                                        @method('delete')
                                        <button type="submit" style="margin-right: 10px;" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                    </form> 
                                </div>  
                            </div>
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center">
                            <h4>No data available</h4>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection