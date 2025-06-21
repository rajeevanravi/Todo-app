@extends("layouts.default")
@section("content")
<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary" style="width: fit-content; margin: 0 auto; padding: 10px 20px; border-radius: 8px;">
  <div class="">
    <a href="{{route("useraddtodo")}}"><button class="btn btn-outline-success me-2" type="button">Add Todo</button></a>
    <a href="{{route("userviewtodo")}}"><button class="btn btn-outline-success me-2" type="button">View Todo</button></a>
    <a href="{{route("logout")}}"><button class="btn btn-sm btn-outline-danger" type="button">Logout</button></a>
  </div>
</nav>
@yield("userbody")
@endsection
