@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <main class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10">
        <div class="w-full max-w-md">
            <a href="{{ url('/') }}" class="mb-8 flex items-center justify-center gap-3">
                <span class="flex size-11 items-center justify-center rounded-xl bg-slate-950 font-extrabold text-amber-400">MI</span>
                <span class="text-sm font-bold tracking-[0.18em] text-slate-950">MAJAPAHIT <span class="text-amber-500">INFLUENCE</span></span>
            </a>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h1 class="text-2xl font-bold tracking-tight text-slate-950">Selamat datang kembali</h1>
                <p class="mt-2 text-sm text-slate-500">Masuk untuk mengelola aktivitas Majapahit Influence.</p>
                @if (session('success'))
                    <div class="mt-5 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mt-5 rounded-xl bg-rose-50 p-3 text-sm text-rose-700">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
                    @csrf
                    <label class="block text-sm font-medium text-slate-700">Email
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </label>
                    <label class="block text-sm font-medium text-slate-700">Password
                        <input type="password" name="password" required class="mt-2 w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-500"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-indigo-600">Ingat saya</label>
                    <a href="{{ route('password.request') }}" class="block text-right text-sm font-semibold text-indigo-600">Lupa password?</a>
                    <button class="w-full rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Masuk <i class="bi bi-arrow-right ml-1"></i></button>
                </form>
                <p class="mt-6 text-center text-sm text-slate-500">Belum terdaftar sebagai KOL? <a href="{{ route('registration.create') }}" class="font-semibold text-indigo-600">Daftar sekarang</a></p>
            </div>
        </div>
    </main>
@endsection
