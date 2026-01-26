Klik link berikut untuk melakukan aktivasi akun Larapus:
<a href="{{ $link = url('auth/verify', $token).'?email='.urlencode($user->email) }}"> {{ $lin\
k }} </a>

Mail::send('auth.emails.verification', compact('user', 'token'), function ($m) use ($user) {
    $m->to($user->email, $user->name)->subject('Verifikasi Akun Larapus');
});
