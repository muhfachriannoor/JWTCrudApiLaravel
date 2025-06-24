@extends('layouts.app')
@section('content')
    <div class="container-content">
        <h1>Tambah User Baru</h1>

        {{-- Pesan Sukses/Error (Opsional, tapi bagus untuk konsistensi) --}}
        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Tambah Data User --}}
        <form action="{{ route('users.store') }}" method="POST">
            @csrf {{-- Penting untuk keamanan Laravel --}}

            <div>
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                @error('password') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation">Konfirmasi Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div>
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
                @error('role') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <button type="submit">Simpan User</button>
            <a href="{{ route('users.index') }}" style="margin-left: 10px;">Kembali ke Dashboard</a>
        </form>
    </div>
@endsection