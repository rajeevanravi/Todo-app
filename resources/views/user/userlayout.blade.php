@extends("layouts.default")
@section("content")
<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary" style="width: fit-content; margin: 0 auto; padding: 10px 20px; border-radius: 8px;">
  <div class="">
    <a href="{{route("useraddtodo")}}"><button class="btn btn-outline-success me-2" type="button">Add Todo</button></a>
    <a href="{{route("userviewtodo")}}"><button class="btn btn-outline-success me-2" type="button">View Todo</button></a>
    <button id="logout" class="btn btn-sm btn-outline-danger" type="button">Logout</button>
  </div>
</nav>
@yield("userbody")
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
                        //alert(response.message);
                        Swal.fire(response.message);
                        window.location.href = response.redirect;
                    } else {
                        //alert(response.message);
                        Swal.fire({
                          icon: "error",
                          title: "Oops...",
                          text: response.message,
                        });
                    }

                }
             });

            });
        });
    </script>
    @yield('subscripts')
@endsection
