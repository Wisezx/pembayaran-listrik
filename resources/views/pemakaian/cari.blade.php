@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cari Data Pemakaian</h2>
    <form action="{{ route('pemakaian.cari') }}" method="GET">
        <div class="mb-3">
            <label for="NoKontrol" class="form-label">No Kontrol</label>
            <input type="text" class="form-control" id="NoKontrol" name="NoKontrol" required>
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>
@endsection
