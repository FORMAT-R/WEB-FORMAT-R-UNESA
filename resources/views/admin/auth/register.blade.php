@extends('admin.layouts.app')

@section('title', 'Daftar Admin - FORMAT-R UNESA')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 7 10-7-10-7z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h1 class="auth-title">Daftar Admin</h1>
            <p class="auth-subtitle">Buat akun admin baru untuk FORMAT-R UNESA</p>
        </div>
        
        <form @submit.prevent="handleRegister" class="space-y-0">
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h18a7 7 0 00-7-7z" />
                    </svg>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input" 
                        placeholder="Nama Lengkap"
                        x-model="form.name"
                        required
                        autocomplete="name"
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="admin@formatr.unesa.ac.id"
                        x-model="form.email"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 5.943 7.523 1 12 1c4.478 0 8.268 4.943 12 1c4.477 0 8.268 4.943 12 10c-4.477 0-8.268 4.943-12 10" />
                    </svg>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Minimal 8 karakter, huruf besar & kecil, angka"
                        x-model="form.password"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 5.943 7.523 1 12 1c4.478 0 8.268 4.943 12 1c4.477 0 8.268 4.943 12 10c-4.477 0-8.268 4.943-12 10" />
                    </svg>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input" 
                        placeholder="Konfirmasi kata sandi"
                        x-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>
            
            <button type="submit" class="btn-submit" :disabled="loading">
                <span x-show="!loading">Daftar</span>
                <span x-show="loading">
                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.3" />
                        <path class="animate-spin" stroke="white" stroke-linecap="round" stroke-width="2" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" stroke-opacity="1"/>
                        <path stroke="white" stroke-linecap="round" stroke-width="2" d="M12 2a10 10 0 0110 10"/>
                    </svg>
                    Memproses...
                </span>
            </button>
            
            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('admin.login') }}">Masuk</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registerForm', () => ({
        form: {
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        },
        loading: false,
        
        handleRegister() {
            if (this.loading) return;
            
            if (!this.form.name || !this.form.email || !this.form.password || !this.form.password_confirmation) {
                this.showToast('Semua field wajib diisi', 'error');
                return;
            }
            
            if (this.form.password !== this.form.password_confirmation) {
                this.showToast('Konfirmasi kata sandi tidak cocok', 'error');
                return;
            }
            
            if (this.form.password.length < 8) {
                this.showToast('Kata sandi minimal 8 karakter', 'error');
                return;
            }
            
            this.loading = true;
            
            // Simulasi register - replace with actual API call
            setTimeout(() => {
                this.loading = false;
                this.showToast('Akun berhasil dibuat! Silakan login.', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route('admin.login') }}';
                }, 1500);
            }, 1500);
        },
        
        showToast(message, type) {
            const bgColor = type === 'success' ? '#059669' : '#DC2626';
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#059669' : '#DC2626'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 10px;
                box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });
</script>
