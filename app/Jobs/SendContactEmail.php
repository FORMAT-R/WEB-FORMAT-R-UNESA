<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public string $nama,
        public string $email,
        public string $pesan,
        public string $emailTujuan,
    ) {}

    public function handle(): void
    {
        $body = "Nama: {$this->nama}\nEmail: {$this->email}\n\nPesan:\n{$this->pesan}";

        Mail::raw($body, function ($message) {
            $message->to($this->emailTujuan)
                ->subject('Pesan Baru dari Website FORMAT-R UNESA')
                ->replyTo($this->email, $this->nama);
        });
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Gagal mengirim email kontak setelah beberapa kali percobaan: ' . $exception->getMessage(), [
            'nama' => $this->nama,
            'email' => $this->email,
        ]);
    }
}
