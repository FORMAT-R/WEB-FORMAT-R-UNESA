<x-mail::message>
# Halo {{ $user->name }},

Berikut adalah ringkasan pengingat terkait event di **FORMAT-R UNESA** yang perlu Anda perhatikan hari ini:

@if($notifications->count() > 0)
@foreach($notifications as $notif)
<x-mail::panel>
**{{ $notif['title'] }}**  
{{ $notif['desc'] }}  
*Waktu: {{ $notif['time'] }}*
</x-mail::panel>
@endforeach

<x-mail::button :url="route('admin.dashboard')">
Masuk ke Dashboard
</x-mail::button>
@else
Saat ini tidak ada event yang memerlukan pembaruan status atau akan segera berlangsung.
@endif

Terima kasih atas kerja keras Anda!<br>
Hormat Kami,<br>
**Admin {{ get_setting('siteName', 'FORMAT-R UNESA') }}**
</x-mail::message>
