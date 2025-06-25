@extends("layouts.default")
@section("content")

<nav  id="xx" class="navbar fixed-top navbar-expand-lg bg-body-tertiary" style="width: fit-content; margin: 0 auto; padding: 10px 20px; border-radius: 8px">
  <div class=" "  >
    <a href="{{route("adminaddtodo")}}"><button id="addtodo" class="btn btn-outline-success me-2" type="button">Add Todo</button></a>
    <a href="{{route("adminviewtodo")}}"><button id="viewtodo" class="btn btn-outline-success me-2" type="button">View Todo</button></a>
    <a href="{{route("viewuser")}}"><button id="viewuser" class="btn btn-outline-success me-2" type="button">View User</button></a>
    <a href="{{route("register")}}"><button id="adduser" class="btn btn-outline-success me-2" type="button">Add User</button></a>
    <button id="logout" class="btn btn-sm btn-outline-danger" type="button">Logout</button>
  </div>
</nav>
@yield("adminbody")
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#logout').click(function (e) {
                e.preventDefault();

             $.ajax({
                type: "GET",
                url: "{{ route('logout') }}",
                dataType: "json",
                success: function (response) {

                    if (response.success) {
                        alert(response.message);
                        window.location.href = response.redirect;
                    } else {
                        alert(response.message);
                    }

                }
             });

            });
        });
    </script>
    @yield('subscripts')
@endsection
