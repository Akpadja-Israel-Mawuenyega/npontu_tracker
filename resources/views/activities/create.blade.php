@extends('layouts.app')

@section('title', 'New Activity')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('activities.index') }}" class="text-sm text-slate-600 hover:text-slate-900">
            ← Back to daily view
        </a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Log a New Activity</h1>
        <p class="text-sm text-slate-600 mt-1">
            Create a tracking item for the applications support team.
        </p>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 p-6">
        <form method="POST" action="{{ route('activities.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                    Title
                </label>
                <input id="title" name="title" type="text" required maxlength="255"
                       value="{{ old('title') }}"
                       placeholder="e.g. Daily SMS count vs logs reconciliation"
                       class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
                    Description
                </label>
                <textarea id="description" name="description" required rows="4"
                          placeholder="What needs to be done, expected output, who's responsible…"
                          class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="activity_date" class="block text-sm font-medium text-slate-700 mb-1">
                    Activity Date
                </label>
                <input id="activity_date" name="activity_date" type="date" required
                       value="{{ old('activity_date', \Illuminate\Support\Carbon::today()->toDateString()) }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('activities.index') }}"
                   class="px-4 py-2 border border-slate-300 text-slate-700 text-sm font-medium rounded-md hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-md hover:bg-slate-700">
                    Create Activity
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
