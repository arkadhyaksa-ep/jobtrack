<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Job Application</title>
    <style>
        :root { color-scheme: light; font-family: Arial, sans-serif; color: #1f2937; background: #f3f4f6; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 2rem 1rem; }
        main { max-width: 720px; margin: 0 auto; }
        h1 { margin: 0 0 1rem; font-size: 1.8rem; }
        .back-link { display: inline-block; margin-bottom: 1rem; color: #2563eb; }
        form { display: grid; gap: 1rem; padding: 1.5rem; background: #fff; border: 1px solid #e5e7eb; border-radius: .5rem; }
        label { display: grid; gap: .4rem; font-weight: 600; }
        input, select, textarea { width: 100%; padding: .65rem .75rem; border: 1px solid #d1d5db; border-radius: .35rem; color: inherit; font: inherit; }
        textarea { min-height: 7rem; resize: vertical; }
        .field-error { color: #b91c1c; font-size: .875rem; font-weight: 400; }
        button { justify-self: start; padding: .7rem 1rem; border: 0; border-radius: .35rem; background: #2563eb; color: #fff; font: inherit; cursor: pointer; }
    </style>
</head>
<body>
    <main>
        <a class="back-link" href="{{ route('applications.index') }}">&larr; Back to applications</a>
        <h1>Add Job Application</h1>

        <form method="POST" action="{{ route('applications.store') }}">
            @csrf

            <label for="company_name">
                Company name
                <input id="company_name" name="company_name" type="text" maxlength="255" value="{{ is_string(old('company_name')) ? old('company_name') : '' }}" required>
                @error('company_name')<span class="field-error">{{ $message }}</span>@enderror
            </label>

            <label for="job_title">
                Job title
                <input id="job_title" name="job_title" type="text" maxlength="255" value="{{ is_string(old('job_title')) ? old('job_title') : '' }}" required>
                @error('job_title')<span class="field-error">{{ $message }}</span>@enderror
            </label>

            <label for="job_url">
                Job URL (optional)
                <input id="job_url" name="job_url" type="url" maxlength="255" value="{{ is_string(old('job_url')) ? old('job_url') : '' }}">
                @error('job_url')<span class="field-error">{{ $message }}</span>@enderror
            </label>

            <label for="status">
                Status
                <select id="status" name="status" required>
                    <option value="Wishlist" @selected(old('status', 'Wishlist') === 'Wishlist')>Wishlist</option>
                    <option value="Applied" @selected(old('status') === 'Applied')>Applied</option>
                    <option value="Interview" @selected(old('status') === 'Interview')>Interview</option>
                    <option value="Offer" @selected(old('status') === 'Offer')>Offer</option>
                    <option value="Rejected" @selected(old('status') === 'Rejected')>Rejected</option>
                    <option value="Withdrawn" @selected(old('status') === 'Withdrawn')>Withdrawn</option>
                </select>
                @error('status')<span class="field-error">{{ $message }}</span>@enderror
            </label>

            <label for="applied_at">
                Application date (optional)
                <input id="applied_at" name="applied_at" type="date" value="{{ is_string(old('applied_at')) ? old('applied_at') : '' }}">
                @error('applied_at')<span class="field-error">{{ $message }}</span>@enderror
            </label>

            <label for="notes">
                Notes (optional)
                <textarea id="notes" name="notes">{{ is_string(old('notes')) ? old('notes') : '' }}</textarea>
                @error('notes')<span class="field-error">{{ $message }}</span>@enderror
            </label>

            <button type="submit">Save application</button>
        </form>
    </main>
</body>
</html>