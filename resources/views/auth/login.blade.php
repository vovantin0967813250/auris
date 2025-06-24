@extends('layouts.guest')

@section('title', 'Đăng nhập')
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4 border-0" style="min-width: 350px; max-width: 400px; border-radius: 1.5rem; background: rgba(255,255,255,0.97);">
        <div class="text-center mb-4">
            <div class="mb-2">
                <i class="fas fa-user-lock fa-3x text-primary"></i>
            </div>
            <h3 class="fw-bold text-primary mb-0">Đăng nhập quản lý Shop</h3>
            <div class="text-muted small">Vui lòng nhập thông tin để truy cập hệ thống</div>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            </div>
            <button type="submit" class="btn w-100 text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 2rem;">Đăng nhập</button>
        </form>
    </div>
</div>
@endsection 