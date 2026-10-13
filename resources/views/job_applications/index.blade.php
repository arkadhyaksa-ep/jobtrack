@extends('layouts.app')

@section('title', 'Job Applications | JobTrack')

@section('content')
    <div class="dashboard-content">
    <div class="page-heading">
        <div class="page-heading__copy">
            <p class="eyebrow"><span class="eyebrow__dot" aria-hidden="true"></span>Application workspace</p>
            <h1>Job Applications</h1>
            <p class="page-description">A clear view of every opportunity, from first thought to final decision.</p>
        </div>
        <a class="button button--primary button--add" href="{{ route('applications.create') }}"><span aria-hidden="true">+</span> Add Application</a>
    </div>

    @if (session('success'))
        <p class="notice" role="status">{{ session('success') }}</p>
    @endif

    <section class="summary-grid" aria-label="Application summary">
        <article class="summary-card summary-card--total" data-stat="total" data-count="{{ $statistics['total'] }}">
            <div class="summary-card__top">
                <span class="summary-card__label">Total applications</span>
                <span class="summary-card__icon summary-card__icon--total" aria-hidden="true">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"></rect><path d="M9 4.5V3h6v1.5M8.5 10h7M8.5 14h7M8.5 18h4"></path></svg>
                </span>
            </div>
            <strong class="summary-card__value">{{ $statistics['total'] }}</strong>
        </article>
        <article class="summary-card summary-card--interview" data-stat="interview" data-count="{{ $statistics['interview'] }}">
            <div class="summary-card__top">
                <span class="summary-card__label">Interviews</span>
                <span class="summary-card__icon summary-card__icon--interview" aria-hidden="true">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 11.5a7.5 7.5 0 0 1-7.5 7.5H7l-3 2v-5.2A7.5 7.5 0 1 1 20 11.5Z"></path><path d="M8.5 11.5h.01M12.5 11.5h.01M16.5 11.5h.01"></path></svg>
                </span>
            </div>
            <strong class="summary-card__value">{{ $statistics['interview'] }}</strong>
        </article>
        <article class="summary-card summary-card--offer" data-stat="offer" data-count="{{ $statistics['offer'] }}">
            <div class="summary-card__top">
                <span class="summary-card__label">Offers</span>
                <span class="summary-card__icon summary-card__icon--offer" aria-hidden="true">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"></path><path d="m9.3 12.2 1.8 1.8 3.8-4"></path></svg>
                </span>
            </div>
            <strong class="summary-card__value">{{ $statistics['offer'] }}</strong>
        </article>
        <article class="summary-card summary-card--rejected" data-stat="rejected" data-count="{{ $statistics['rejected'] }}">
            <div class="summary-card__top">
                <span class="summary-card__label">Rejected</span>
                <span class="summary-card__icon summary-card__icon--rejected" aria-hidden="true">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="8.5"></circle><path d="m9 9 6 6m0-6-6 6"></path></svg>
                </span>
            </div>
            <strong class="summary-card__value">{{ $statistics['rejected'] }}</strong>
        </article>
    </section>

    <section class="filter-card" aria-label="Search and filter applications">
        <form method="GET" action="{{ route('applications.index') }}" class="filter-form">
            <div class="field">
                <label class="field__label" for="search">Search company or job title</label>
                <input id="search" name="search" type="search" value="{{ $search }}" placeholder="e.g. Acme or Product Designer">
            </div>
            <div class="field">
                <label class="field__label" for="status">Application status</label>
                <select id="status" name="status">
                    <option value="" @selected($selectedStatus === '')>All statuses</option>
                    @foreach (['Wishlist', 'Applied', 'Interview', 'Offer', 'Rejected', 'Withdrawn'] as $statusOption)
                        <option value="{{ $statusOption }}" @selected($selectedStatus === $statusOption)>{{ $statusOption }}</option>
                    @endforeach
                </select>
            </div>
            <button class="button button--primary" type="submit">Search</button>
            <a class="filter-reset" href="{{ route('applications.index') }}">Clear filters</a>
        </form>
    </section>

    <section class="table-card" aria-label="Job application results">
        <div class="table-card__heading">
            <div>
                <h2>Application records</h2>
                <p>Review your latest opportunities and next steps.</p>
            </div>
            <span class="result-count">{{ $applications->total() }} {{ \Illuminate\Support\Str::plural('result', $applications->total()) }}</span>
        </div>
        <div class="table-wrap">
            <table class="applications-table">
                <thead>
                    <tr>
                        <th scope="col">Company</th>
                        <th scope="col">Job title</th>
                        <th scope="col">Status</th>
                        <th scope="col">Application date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr>
                            <td><span class="company-name">{{ $application->company_name }}</span></td>
                            <td><span class="job-title">{{ $application->job_title }}</span></td>
                            <td><span class="status-badge status-{{ strtolower($application->status) }}">{{ $application->status }}</span></td>
                            <td>
                                @if ($application->applied_at instanceof \DateTimeInterface)
                                    {{ $application->applied_at->format('M j, Y') }}
                                @elseif (is_string($application->applied_at) && $application->applied_at !== '')
                                    {{ \Illuminate\Support\Carbon::parse($application->applied_at)->format('M j, Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a class="action-link" href="{{ route('applications.edit', $application) }}">Edit</a>
                                    <form class="delete-form" method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Are you sure you want to delete this application?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button button--danger-quiet" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="5">
                                <div class="empty-state__content">
                                    <span class="empty-state__mark" aria-hidden="true">{{ $hasFilters ? '?' : '+' }}</span>
                                    @if ($hasFilters)
                                        <strong>No matching applications</strong>
                                        <span>No job applications match your search or filters.</span>
                                    @else
                                        <strong>Your tracker is ready</strong>
                                        <span>No job applications yet. They&rsquo;ll appear here when you add one.</span>
                                        <a class="button button--secondary" href="{{ route('applications.create') }}">Add your first application</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="pagination-container">
        {{ $applications->links('vendor.pagination.jobtrack') }}
    </div>
    </div>
@endsection
