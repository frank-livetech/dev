

<h1>Hello {{$users->name}}</h1>
<p> Please Click the password reset button to reset your password </p>
<a href="{{ url('reset_password/'.$users->email.'/'.$code) }}"> Reset Password </a>