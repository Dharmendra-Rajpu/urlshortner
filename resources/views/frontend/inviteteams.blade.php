@extends('layouts.app')

@section('content')

<div class="card mb-4">
    <div class="card-header-flex">
        <span class="section-title">Invite Team Member</span>
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('team.invite.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="member@example.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">-- Select Role --</option>

                    @if(auth()->user()->isSuperAdmin())
                        <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Member" {{ old('role') == 'Member' ? 'selected' : '' }}>Member</option>
                        <option value="Sales" {{ old('role') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    @elseif(auth()->user()->isAdmin())
                        <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Sales" {{ old('role') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    @endif
                </select>
                @error('role')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Sirf SuperAdmin ko dikhega: existing company choose karein ya naya company banayein --}}
            @if(auth()->user()->isSuperAdmin())
                <div class="mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror">
                        <option value="">-- Create New Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    
                </div>

                <div class="mb-3" id="new_company_field">
                    <label for="company_name" class="form-label">New Company Name</label>
                    <input
                        type="text"
                        name="company_name"
                        id="company_name"
                        class="form-control @error('company_name') is-invalid @enderror"
                        placeholder="Company name"
                        value="{{ old('company_name') }}"
                    >
                    @error('company_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-brand px-4">Send Invitation</button>
                <a href="{{ route('team.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </form>

    </div>
</div>

@if(auth()->user()->isSuperAdmin())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const companySelect = document.getElementById('company_id');
        const newCompanyField = document.getElementById('new_company_field');

        function toggleNewCompanyField() {
            if (companySelect.value) {
                newCompanyField.style.display = 'none';
            } else {
                newCompanyField.style.display = 'block';
            }
        }

        toggleNewCompanyField();
        companySelect.addEventListener('change', toggleNewCompanyField);
    });
</script>
@endif

@endsection