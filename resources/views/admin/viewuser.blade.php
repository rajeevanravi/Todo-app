@extends("admin.adminlayout")
@section("adminbody")
<div class="container mt-5">
    <h2 class="mb-4">User Details</h2>
    <table class="table table-borderless table-striped">

  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">ID</th>
      <th scope="col">Name</th>
      <th scope="col">e-mail</th>
      <th scope="col">Role</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($user as $item )
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $item->id }}</td>
      <td>{{ $item->name }}</td>
      <td>{{ $item->email }}</td>
      <td>{{ $item->role }}</td>
      <td><div style="display: flex; gap: 10px; align-items: center;"><a href="{{ route('users.edit', $item->id) }}"><button class="btn btn-outline-info">Edit</button></a>
        <form action="{{ route('users.destroy', $item->id) }}" method="POST">
            @csrf
            @method('DELETE')
        <button class="btn btn-outline-danger">Delete</button>
        </form></div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
@endsection
