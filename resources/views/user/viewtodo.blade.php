@extends("user.userlayout")
@section("userbody")
<div class="container mt-5">
    <h2 class="mb-4">View To-Do</h2>
    <div>
        @foreach($todos as $todo)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $todo->title }}</h5>
                    <p>{{ $todo->message }}</p>
                    <small class="text-muted">
                        Created at: {{ $todo->created_at->format('d M Y, h:i A') }}
                    </small><br>

                    @if(auth()->user()->role === 'user')
                        <small class="text-info">User: {{ $todo->user->name }}({{ $todo->user->role }})</small>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
