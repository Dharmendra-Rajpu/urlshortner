@extends('layouts.app')

@section('content')

<div class="card mb-4">
    <div class="card-header-flex">
        <span class="section-title">Generated Short URLs</span>

        @if(in_array(auth()->user()->role, ['Manager', 'Sales']))
            <a href="{{ route('short-urls.create') }}" class="btn btn-brand btn-sm px-3">Generate</a>
        @endif
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Short URL</th>
                    <th>Long URL</th>
                    <th>Hits</th>

                    @if(in_array(auth()->user()->role, ['SuperAdmin', 'Admin', 'Manager']))
                        <th>Created By</th>
                    @endif

                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shortUrls as $url)
                    <tr>
                        <td>
                            {{-- Requirement: short urls should not be publicly resolvable,
                                 so shown as plain text instead of a clickable link --}}
                            <span class="short-url-link">{{ url('/s/' . $url->short_code) }}</span>
                        </td>
                        <td class="long-url-cell">{{ \Illuminate\Support\Str::limit($url->original_url, 40) }}</td>
                        <td class="stat-num">{{ $url->hits }}</td>

                        @if(in_array(auth()->user()->role, ['SuperAdmin', 'Admin', 'Manager']))
                            <td>{{ $url->user->name ?? '-' }}</td>
                        @endif

                        <td>{{ $url->created_at->format("d M y") }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">No URLs generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-row">
        <span>Showing {{ $shortUrls->count() }} of total {{ $totalShortUrls }}</span>
    </div>
</div>

@if(in_array(auth()->user()->role, ['SuperAdmin', 'Admin', 'Manager']))
<div class="card">
    <div class="card-header-flex">
        <span class="section-title">Team Members</span>
        @if(in_array(auth()->user()->role, ['SuperAdmin', 'Admin']))
            <a href="{{ route('team.invite') }}" class="btn btn-brand btn-sm px-3">Invite</a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Total Generated URLs</th>
                    <th>Total URL Hits</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teamMembers as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td><span class="badge-role {{ strtolower($member->role) }}">{{ $member->role }}</span></td>
                        <td class="stat-num">{{ $member->shortUrls()->count() }}</td>
                        <td class="stat-num">{{ $member->shortUrls()->sum('hits') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">No team members yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-row">
        <span>Showing {{ $teamMembers->count() }} of total {{ $totalTeamMembers }}</span>
    </div>
</div>
@endif

@endsection