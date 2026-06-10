@extends('layouts.app')

@section('title', 'Daily Activities')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daily Handover View</h1>
            <p class="text-sm text-slate-600 mt-1">
                All activities, personnel updates, statuses, and timestamps for complete shift handover.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
            <form method="GET" action="{{ route('activities.index') }}" class="flex items-end gap-2">
                <div>
                    <label for="date" class="block text-xs font-medium text-slate-600 mb-1">Select Date</label>
                    <input type="date" id="date" name="date" value="{{ $targetDate }}"
                        class="px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-md hover:bg-slate-700 transition">
                    View Handover
                </button>
            </form>
            <a href="{{ route('activities.create') }}"
                class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition text-center">
                + New Activity
            </a>
        </div>
    </div>

    {{-- Handover Summary Card --}}
    @if (!$activities->isEmpty())
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-lg p-4 mb-6 text-white">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold">{{ $activities->count() }}</p>
                    <p class="text-xs text-slate-300">Total Activities</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-300">
                        {{ $activities->filter(function ($a) {
                                $latest = $a->updates->sortByDesc('created_at')->first();
                                return $latest && $latest->status === 'pending';
                            })->count() }}
                    </p>
                    <p class="text-xs text-slate-300">Pending Handover</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-300">
                        {{ $activities->filter(function ($a) {
                                $latest = $a->updates->sortByDesc('created_at')->first();
                                return $latest && $latest->status === 'done';
                            })->count() }}
                    </p>
                    <p class="text-xs text-slate-300">Completed</p>
                </div>
                <div>
                    <p class="text-2xl font-bold">{{ $activities->sum(fn($a) => $a->updates->count()) }}</p>
                    <p class="text-xs text-slate-300">Total Updates</p>
                </div>
            </div>
        </div>
    @endif

    @if ($activities->isEmpty())
        <div class="bg-white rounded-lg border border-slate-200 p-12 text-center">
            <div class="mb-4">
                <svg class="h-16 w-16 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <p class="text-slate-500">No activities logged for
                {{ \Illuminate\Support\Carbon::parse($targetDate)->format('l, F j, Y') }}.</p>
            <a href="{{ route('activities.create') }}"
                class="inline-block mt-4 text-sm font-medium text-slate-900 hover:underline">
                Log the first activity for handover →
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($activities as $activity)
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition"
                    id="activity-{{ $activity->id }}">
                    {{-- Activity Header --}}
                    <div
                        class="px-6 py-4 border-b border-slate-100 flex items-start justify-between gap-4 bg-gradient-to-r from-white to-slate-50">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h2 class="text-lg font-semibold text-slate-900">{{ $activity->title }}</h2>
                                @php
                                    $latest = $activity->updates->sortByDesc('created_at')->first();
                                    $statusColor =
                                        $latest && $latest->status === 'done'
                                            ? 'bg-emerald-100 text-emerald-800'
                                            : ($latest
                                                ? 'bg-amber-100 text-amber-800'
                                                : 'bg-slate-100 text-slate-600');
                                    $statusText = $latest ? ucfirst($latest->status) : 'Awaiting Update';
                                @endphp
                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600">{{ $activity->description }}</p>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-xs text-slate-400">
                                <span>📋 Created by: <span
                                        class="font-medium text-slate-600">{{ $activity->creator->name ?? 'Unknown' }}</span></span>
                                <span>🕒 {{ $activity->created_at->format('M j, Y g:i A') }}</span>
                                <span>📅 Activity Date:
                                    {{ \Carbon\Carbon::parse($activity->activity_date)->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Updates Timeline (Requirement #4: All updates visible for handover) --}}
                    <div class="px-6 py-4 bg-slate-50">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                📝 Activity Log & Handover Trail ({{ $activity->updates->count() }} updates)
                            </h3>
                            @if ($activity->updates->isNotEmpty())
                                <span class="text-xs text-slate-400">Latest first</span>
                            @endif
                        </div>

                        @if ($activity->updates->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-sm text-slate-400 italic">No updates submitted yet. Be the first to update
                                    this activity.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($activity->updates->sortByDesc('created_at') as $update)
                                    <div class="bg-white rounded-lg p-3 border border-slate-200">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                                <span class="font-semibold text-slate-900 text-sm">
                                                    👤 {{ $update->user->name ?? 'Unknown' }}
                                                </span>
                                                <span class="text-xs text-slate-500">
                                                    {{ $update->user->department ?? 'No department' }}
                                                </span>
                                                <span
                                                    class="px-2 py-0.5 text-xs font-medium rounded-full
                             {{ $update->status === 'done' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                    Status: {{ ucfirst($update->status) }}
                                                </span>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <p class="text-xs text-slate-400">
                                                    🕒 {{ $update->created_at->format('M j, Y g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                        @if ($update->remark)
                                            <p class="text-sm text-slate-700 mt-2">
                                                💬 {{ $update->remark }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Submit Update Form (Requirement #2: Status + Remark) --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-white">
                        <form method="POST" action="{{ route('activities.updates.store', $activity->id) }}"
                            class="flex flex-col gap-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                                    <select name="status" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
                                        <option value="pending">🟡 Pending (Needs Handover)</option>
                                        <option value="done">🟢 Done (Completed)</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Remark / Handover
                                        Note</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="remark" required maxlength="1000"
                                            placeholder="E.g., Fixed SMS sync issue, handed over to John for final verification…"
                                            class="flex-1 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
                                        <button type="submit"
                                            class="px-6 py-2 bg-slate-900 text-white text-sm font-medium rounded-md hover:bg-slate-700 transition whitespace-nowrap">
                                            Submit Update
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">Max 1000 characters. Include handover details,
                                        blockers, or completion notes.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Handover Reminder --}}
    @if (
        !$activities->isEmpty() &&
            $activities->contains(fn($a) => ($a->updates->sortByDesc('created_at')->first()->status ?? 'pending') === 'pending'))
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="text-amber-600 text-xl">⚠️</span>
                <div>
                    <p class="text-sm font-medium text-amber-800">Pending Handover Items</p>
                    <p class="text-xs text-amber-700 mt-1">
                        Some activities have pending status. Ensure all updates are reviewed and handed over to the next
                        shift.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection
