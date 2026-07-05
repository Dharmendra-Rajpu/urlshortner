@extends('layouts.app')

@section('content')

<div class="card mb-4">
    <div class="card-header-flex">
        <span class="section-title">Team Members</span>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <a href="{{ route('team.invite') }}" class="btn btn-brand btn-sm px-3">Invite</a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success mx-3 mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mx-3 mt-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    @if(auth()->user()->isSuperAdmin())
                        <th>Company</th>
                    @endif
                    <th>Total Generated URLs</th>
                    <th>Total URL Hits</th>
                    <th>Joined On</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teamMembers as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td><span class="badge-role {{ strtolower($member->role) }}">{{ $member->role }}</span></td>
                        @if(auth()->user()->isSuperAdmin())
                            <td>{{ $member->company->name ?? '-' }}</td>
                        @endif
                        <td class="stat-num">{{ $member->shortUrls()->count() }}</td>
                        <td class="stat-num">{{ $member->shortUrls()->sum('hits') }}</td>
                        <td>{{ $member->created_at->format('d M y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isSuperAdmin() ? 7 : 6 }}" class="text-center py-3">
                            No team members yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-row">
        <span>Showing {{ $teamMembers->count() }} of total {{ $teamMembers->count() }}</span>
    </div>
</div>

@endsection