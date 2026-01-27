<p>Klik link berikut untuk melakukan aktivasi akun Larapus:</p>
<a href="{{ $link = url('auth/verify', $token).'?email='.urlencode($user->email) }}"> {{ $link }} </a>