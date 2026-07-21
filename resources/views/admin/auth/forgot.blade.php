@extends('admin.layouts.app')

@section('title', 'Lupa Kata Sandi - Admin FORMAT-R UNESA')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            </div>
            <h1 class="auth-title">Lupa Kata Sandi</h1>
            <p class="auth-subtitle">Masukkan email Anda, kami akan mengirimkan tautan reset kata sandi</p>
        </div>
        
        <form @submit.prevent="handleForgot" class="space-y-0">
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
            
            <button type="submit" class="btn-submit" :disabled="loading">
                <span x-show="!loading">Kirim Tautan Reset</span>
                <span x-show="loading">
                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.3" />
                        <path class="animate-spin" stroke="white" stroke-linecap="round" stroke-width="2" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" stroke-opacity="1" />
                        <path stroke="white" stroke-linecap="round" stroke-width="2" d="M12 2a10 10 0 0110 10" stroke-opacity="1" />
                    </svg>
                    Mengirim...
                </span>
            </button>
            
            <div class="auth-footer">
                <a href="{{ route('admin.login') }}" class="forgot-link">&larr; Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('forgotForm', () => ({
        form: {
            email: ''
        },
        loading: false,
        
        handleForgot() {
            if (this.loading) return;
            
            if (!this.form.email) {
                this.showToast('Email wajib diisi', 'error');
                return;
            }
            
            if (!this.form.email.includes('@')) {
                this.showToast('Format email tidak valid', 'error');
                return;
            }
            
            this.loading = true;
            
            setTimeout(() => {
                this.loading = false;
                this.showToast('Jika email terdaftar, tautan reset akan dikirim', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route('admin.login') }}';
                }, 2000);
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
