@extends('layouts.app')

@section('title', 'Add Job Application | JobTrack')

@section('content')
    <div class="page-heading">
        <div class="page-heading__copy">
            <p class="eyebrow">New opportunity</p>
            <h1>Add job application</h1>
            <p class="page-description">Save the details now and keep your next step in view.</p>
        </div>
        <a class="back-link" href="{{ route('applications.index') }}">Back to applications</a>
    </div>

    <section class="form-card" aria-label="New job application details">
        <form class="application-form" method="POST" action="{{ route('applications.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label for="company_name">Company name <span aria-hidden="true">*</span></label>
                    <input id="company_name" name="company_name" type="text" maxlength="255" value="{{ is_string(old('company_name')) ? old('company_name') : '' }}" autocomplete="organization" required @if ($errors->has('company_name')) aria-invalid="true" aria-describedby="company_name-error" @endif>
                    @error('company_name')<span class="field-error" id="company_name-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="job_title">Job title <span aria-hidden="true">*</span></label>
                    <input id="job_title" name="job_title" type="text" maxlength="255" value="{{ is_string(old('job_title')) ? old('job_title') : '' }}" autocomplete="organization-title" required @if ($errors->has('job_title')) aria-invalid="true" aria-describedby="job_title-error" @endif>
                    @error('job_title')<span class="field-error" id="job_title-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field form-field--full">
                    <label for="job_url">Job posting URL <span class="field-hint">Optional</span></label>
                    <input id="job_url" name="job_url" type="url" maxlength="255" value="{{ is_string(old('job_url')) ? old('job_url') : '' }}" placeholder="https://example.com/careers/job-title" @if ($errors->has('job_url')) aria-invalid="true" aria-describedby="job_url-error" @endif>
                    @error('job_url')<span class="field-error" id="job_url-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required @if ($errors->has('status')) aria-invalid="true" aria-describedby="status-error" @endif>
                        <option value="Wishlist" @selected(old('status', 'Wishlist') === 'Wishlist')>Wishlist</option>
                        <option value="Applied" @selected(old('status') === 'Applied')>Applied</option>
                        <option value="Interview" @selected(old('status') === 'Interview')>Interview</option>
                        <option value="Offer" @selected(old('status') === 'Offer')>Offer</option>
                        <option value="Rejected" @selected(old('status') === 'Rejected')>Rejected</option>
                        <option value="Withdrawn" @selected(old('status') === 'Withdrawn')>Withdrawn</option>
                    </select>
                    @error('status')<span class="field-error" id="status-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="applied_at">Application date <span class="field-hint">Optional</span></label>
                    <input id="applied_at" name="applied_at" type="date" value="{{ is_string(old('applied_at')) ? old('applied_at') : '' }}" @if ($errors->has('applied_at')) aria-invalid="true" aria-describedby="applied_at-error" @endif>
                    @error('applied_at')<span class="field-error" id="applied_at-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field form-field--full">
                    <label for="notes">Notes <span class="field-hint">Optional</span></label>
                    <textarea id="notes" name="notes" placeholder="Add a reminder, contact, or follow-up note..." @if ($errors->has('notes')) aria-invalid="true" aria-describedby="notes-error" @endif>{{ is_string(old('notes')) ? old('notes') : '' }}</textarea>
                    @error('notes')<span class="field-error" id="notes-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-actions">
                <button class="button button--primary" type="submit">Save application</button>
                <a class="back-link" href="{{ route('applications.index') }}">Cancel</a>
            </div>
        </form>
    </section>
@endsection
