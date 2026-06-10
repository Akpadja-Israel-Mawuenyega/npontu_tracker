@extends('layouts.app')

@section('title', 'Activity Report')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Activity History Report</h1>
    <p class="text-sm text-slate-600 mt-1">
        Query activities and their updates over a custom date range.
    </p>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6 mb-6">
    <form method="GET" action="{{ route('activities.report') }}"
          class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="from" class="block text-sm font-medium text-slate-700 mb-1">From</label>
            <input id="from" name="from" type="date" required
                   value="{{ request('from', \Illuminate\Support\Carbon::today()->subDays(7)->toDateString()) }}"
                   class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>
        <div>
            <label for="to" class="block text-sm font-medium text-slate-700 mb-1">To</label>
            <input id="to" name="to" type="date" required
                   value="{{ request('to', \Illuminate\Support\Carbon::today()->toDateString()) }}"
                   class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-md hover:bg-slate-700">
                Generate Report
            </button>
        </div>
    </form>
</div>

@isset($activities)
    @if ($activities->isEmpty())
        <div class="bg-white rounded-lg border border-slate-200 p-12 text-center">
            <p class="text-slate-500">No activities found in this range.</p>
        </div>
    @else
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Activity</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Personnel</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Remark</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Updated At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($activities as $activity)
                        @forelse ($activity->updates as $update)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-700">
                                    {{ $activity->activity_date->format('M j, Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                    {{ $activity->title }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-700">
                                    {{ $update->user->name ?? '—' }}
                                    <div class="text-xs text-slate-400">{{ $update->user->department ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                                 {{ $update->status === 'done' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst($update->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-700 max-w-md">
                                    {{ $update->remark }}
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500">
                                    {{ $update->created_at->format('M j, Y g:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-700">
                                    {{ $activity->activity_date->format('M j, Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                    {{ $activity->title }}
                                </td>
                                <td colspan="4" class="px-4 py-3 text-sm text-slate-400 italic">
                                    No updates submitted
                                </td>
                            </tr>
                        @endforelse
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endisset
@endsection
