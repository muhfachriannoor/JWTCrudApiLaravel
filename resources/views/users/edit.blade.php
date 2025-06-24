@extends('layouts.app')
@section('content')
    <div class="container-content">
        <h1>Edit User: {{ $user->name }}</h1>

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

        {{-- Form Edit Data User --}}
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Atau @method('PATCH') --}}

            <div>
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
            </div>

            <div>
                <label for="password">Password (kosongkan jika tidak ingin diubah):</label>
                <input type="password" id="password" name="password">
            </div>

            <div>
                <label for="password_confirmation">Konfirmasi Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div>
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
            </div>

            <button type="submit">Perbarui User</button>
            <a href="{{ route('users.index') }}" style="margin-left: 10px;">Kembali</a>
        </form>

        {{-- Bagian Hobi --}}
        <div class="hobi-list mt-5">
            <h2>Hobi {{ $user->name }}</h2>

            @if($user->hobis->isEmpty())
                <p>User ini belum memiliki hobi.</p>
            @else
                @foreach($user->hobis as $hobi)
                    <div class="hobi-item">
                        <span>{{ $hobi->nama_hobi }}</span>
                        <form action="{{ route('hobis.destroy', $hobi->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button delete" onclick="return confirm('Apakah Anda yakin ingin menghapus hobi ini?')">Hapus</button>
                        </form>
                    </div>
                @endforeach
            @endif

            {{-- Form Tambah Hobi Baru untuk User ini --}}
            <div class="add-hobi-form">
                <h3>Tambah Hobi Baru</h3>
                <form action="{{ route('hobis.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div>
                        <label for="nama_hobi">Nama Hobi:</label>
                        <input type="text" id="nama_hobi" name="nama_hobi" required>
                    </div>
                    <button type="submit">Tambah Hobi</button>
                </form>
            </div>
        </div>
    </div>
@endsection