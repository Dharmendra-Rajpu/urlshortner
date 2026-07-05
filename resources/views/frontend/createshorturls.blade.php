@extends('layouts.app')

@section('content')

<div class="card mb-4">
    <div class="card-header-flex">
        <span class="section-title">Generate Short URL</span>
    </div>

    <div class="card-body">

        <form action="{{ route('short-urls.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="original_url" class="form-label">Original URL</label>
                <input
                    type="url"
                    name="original_url"
                    id="original_url"
                    class="form-control @error('original_url') is-invalid @enderror"
                    placeholder="https://example.com/your-long-url"
                    value="{{ old('original_url') }}"
                >
                @error('original_url')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-brand px-4">Generate</button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </form>

    </div>
</div>

@endsection