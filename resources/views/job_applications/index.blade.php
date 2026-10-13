<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Applications</title>
    <style>
        :root { color-scheme: light; font-family: Arial, sans-serif; color: #1f2937; background: #f3f4f6; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 2rem 1rem; }
        main { max-width: 960px; margin: 0 auto; }
        .page-heading { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        h1 { margin: 0; font-size: 1.8rem; }
        .add-link { display: inline-block; padding: .7rem 1rem; border-radius: .35rem; background: #2563eb; color: #fff; text-decoration: none; }
        .table-wrap { overflow-x: auto; background: #fff; border: 1px solid #e5e7eb; border-radius: .5rem; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: .9rem 1rem; border-bottom: 1px solid #e5e7eb; }
        th { color: #4b5563; font-size: .85rem; }
        tbody tr:last-child td { border-bottom: 0; }
        .empty-state { color: #6b7280; text-align: center; padding: 2rem 1rem; }
        .pagination { margin-top: 1rem; }
        .success-message { margin: 0 0 1rem; padding: .85rem 1rem; border: 1px solid #86efac; border-radius: .4rem; background: #f0fdf4; color: #166534; }
        .summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: .75rem; margin-bottom: 1.25rem; }
        .summary-card { display: grid; gap: .5rem; padding: 1rem; background: #fff; border: 1px solid #e5e7eb; border-radius: .5rem; }
        .summary-card span { color: #4b5563; font-size: .9rem; }
        .summary-card strong { font-size: 1.6rem; }
    </style>
</head>
<body>
    <main>
        <div class="page-heading">
            <h1>Job Applications</h1>
            <a class="add-link" href="{{ route('applications.create') }}">Add Application</a>
        </div>

        @if (session('success'))
            <p class="success-message" role="status">{{ session('success') }}</p>
        @endif

        <section class="summary-cards" aria-label="Application summary">
            <article class="summary-card" data-stat="total" data-count="{{ $statistics['total'] }}">
                <span>Total applications</span>
                <strong>{{ $statistics['total'] }}</strong>
            </article>
            <article class="summary-card" data-stat="interview" data-count="{{ $statistics['interview'] }}">
                <span>Interviews</span>
                <strong>{{ $statistics['interview'] }}</strong>
            </article>
            <article class="summary-card" data-stat="offer" data-count="{{ $statistics['offer'] }}">
                <span>Offers</span>
                <strong>{{ $statistics['offer'] }}</strong>
            </article>
            <article class="summary-card" data-stat="rejected" data-count="{{ $statistics['rejected'] }}">
                <span>Rejected</span>
                <strong>{{ $statistics['rejected'] }}</strong>
            </article>
        </section>

        <form method="GET" action="{{ route('applications.index') }}" style="display: flex; flex-wrap: wrap; gap: .75rem; align-items: end; margin-bottom: 1rem;">
            <label for="search" style="display: grid; gap: .35rem;">
                Search company or job title
                <input id="search" name="search" type="search" value="{{ $search }}">
            </label>
            <label for="status" style="display: grid; gap: .35rem;">
                Status
                <select id="status" name="status">
                    <option value="" @selected($selectedStatus === '')>All statuses</option>
                    @foreach (['Wishlist', 'Applied', 'Interview', 'Offer', 'Rejected', 'Withdrawn'] as $statusOption)
                        <option value="{{ $statusOption }}" @selected($selectedStatus === $statusOption)>{{ $statusOption }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit">Search</button>
            <a href="{{ route('applications.index') }}">Clear filters</a>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Company</th>
                        <th scope="col">Job Title</th>
                        <th scope="col">Status</th>
                        <th scope="col">Application Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr>
                            <td>{{ $application->company_name }}</td>
                            <td>{{ $application->job_title }}</td>
                            <td>{{ $application->status }}</td>
                            <td>
                                {{ $application->applied_at
                                    ? \Illuminate\Support\Carbon::parse($application->applied_at)->format('M j, Y')
                                    : '—' }}
                            </td>
                            <td>
                                <a href="{{ route('applications.edit', $application) }}">Edit</a>
                                <form method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Are you sure you want to delete this application?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="5">
                                @if ($hasFilters)
                                    No job applications match your search or filters.
                                @else
                                    No job applications yet. They’ll appear here when you add one.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $applications->links() }}
        </div>
    </main>
</body>
</html>
