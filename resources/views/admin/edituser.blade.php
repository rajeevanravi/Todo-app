@extends("admin.adminlayout")
@section("adminbody")
<main class="form-signin w-100 m-auto">
    <form id="edituserform">

        @csrf
        @method('PUT')
        <h1 class="h3 mb-3 fw-normal">Edit user Profile</h1>
        <div class="form-floating">
            <input name="name" type="text" class="form-control" id="name" >
            <label for="floatingInput">Name({{$user->name}})</label>
        </div>
        <div class="form-floating">
            <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
            <label for="floatingInput">Email address({{$user->email}})</label>
        </div>
        <div class="form-floating">
            <input name="role" type="text" class="form-control" id="role" placeholder="name@example.com">
            <label for="floatingInput">Role({{$user->role}})</label>
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="password" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
            <label class="form-check-label" for="checkDefault">Remember me</label>
        </div>
        <button class="btn btn-primary w-100 py-2" id="submit" type="submit">Edit</button>
    </form>

</main>

@endsection

@section('subscripts')
    <script>
        $(document).ready(function () {
            $('#submit').click(function (e) {
                e.preventDefault();
  //              alert('bbb')
                let edituser_formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    role: $('#role').val(),
                    _token: $('input[name="_token"]').val()
                };
//console.log(edituser_formData);

                $.ajax({
                    type: "PUT",
                    dataType: "json",
                    url: "{{ route('users.update', $user->id) }}",
                    data: edituser_formData,
                    success: function (response) {
                        if (response.success) {
                        alert(response.message);
                        window.location.href = response.redirect;
                        } else {
                        alert(response.message);
                        }
                    },

                    error: function () {
                        alert('Something went wrong.');
                    }
                });


            });
        });
    </script>
@endsection
