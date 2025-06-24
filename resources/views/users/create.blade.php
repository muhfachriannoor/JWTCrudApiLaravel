@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h1>Tambah User Baru</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hobis" class="form-label">Hobi:</label>
                <div id="hobi-inputs">
                    <input type="text" class="form-control mb-2" name="hobis[]" placeholder="Nama Hobi">
                    <input type="text" class="form-control mb-2" name="hobis[]" placeholder="Nama Hobi">
                </div>
                <button type="button" class="btn btn-info btn-sm" id="add-hobi">Tambah Kolom Hobi</button>
            </div>
            <button type="submit" class="btn btn-success">Simpan User</button>
        </form>
    </div>
@endsection