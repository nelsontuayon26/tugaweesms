{{-- Modern Skeleton Loader Components --}}
<style>
    /* ===== Base Skeleton ===== */
    .skeleton {
        background: linear-gradient(90deg, 
            #e2e8f0 0%, 
            #f1f5f9 25%, 
            #e2e8f0 50%, 
            #f1f5f9 75%, 
            #e2e8f0 100%);
        background-size: 200% 100%;
        animation: skeletonShimmer 1.6s ease-in-out infinite;
        border-radius: 6px;
    }
    .skeleton-dark {
        background: linear-gradient(90deg, 
            #1e293b 0%, 
            #334155 25%, 
            #1e293b 50%, 
            #334155 75%, 
            #1e293b 100%);
        background-size: 200% 100%;
    }
    @keyframes skeletonShimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* ===== Pulse variant (for circular/avatar shapes) ===== */
    .skeleton-pulse {
        background: #e2e8f0;
        animation: skeletonPulse 1.8s ease-in-out infinite;
    }
    .skeleton-pulse-dark {
        background: #334155;
        animation: skeletonPulse 1.8s ease-in-out infinite;
    }
    @keyframes skeletonPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* ===== Reduced motion ===== */
    @media (prefers-reduced-motion: reduce) {
        .skeleton, .skeleton-dark {
            animation: none;
            background: #e2e8f0;
        }
        .skeleton-dark {
            background: #334155;
        }
        .skeleton-pulse, .skeleton-pulse-dark {
            animation: none;
            opacity: 0.7;
        }
    }
</style>

{{-- Card Skeleton --}}
@props(['type' => 'card', 'count' => 1, 'dark' => false])

@php
$shimmerClass = $dark ? 'skeleton-dark' : 'skeleton';
$pulseClass = $dark ? 'skeleton-pulse-dark' : 'skeleton-pulse';
@endphp

@for ($i = 0; $i < $count; $i++)
    @if ($type === 'card')
        <div class="bg-white rounded-xl border border-slate-100 p-5 animate-pulse">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg {{ $pulseClass }} flex-shrink-0"></div>
                <div class="flex-1 min-w-0 space-y-3">
                    <div class="h-4 w-3/4 {{ $shimmerClass }}"></div>
                    <div class="h-3 w-1/2 {{ $shimmerClass }}"></div>
                    <div class="h-3 w-full {{ $shimmerClass }}"></div>
                    <div class="h-3 w-2/3 {{ $shimmerClass }}"></div>
                </div>
            </div>
        </div>

    @elseif ($type === 'table-row')
        <tr class="animate-pulse">
            <td class="px-4 py-3"><div class="h-4 w-8 {{ $shimmerClass }}"></div></td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full {{ $pulseClass }}"></div>
                    <div class="space-y-1.5">
                        <div class="h-3.5 w-28 {{ $shimmerClass }}"></div>
                        <div class="h-2.5 w-20 {{ $shimmerClass }}"></div>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3"><div class="h-3.5 w-20 {{ $shimmerClass }}"></div></td>
            <td class="px-4 py-3"><div class="h-3.5 w-16 {{ $shimmerClass }}"></div></td>
            <td class="px-4 py-3"><div class="h-6 w-16 rounded-full {{ $shimmerClass }}"></div></td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg {{ $pulseClass }}"></div>
                    <div class="w-7 h-7 rounded-lg {{ $pulseClass }}"></div>
                </div>
            </td>
        </tr>

    @elseif ($type === 'list-item')
        <div class="flex items-center gap-3 py-3 px-4 animate-pulse">
            <div class="w-10 h-10 rounded-full {{ $pulseClass }} flex-shrink-0"></div>
            <div class="flex-1 min-w-0 space-y-2">
                <div class="h-3.5 w-32 {{ $shimmerClass }}"></div>
                <div class="h-2.5 w-48 {{ $shimmerClass }}"></div>
            </div>
            <div class="h-3 w-14 {{ $shimmerClass }}"></div>
        </div>

    @elseif ($type === 'stat-card')
        <div class="bg-white rounded-xl border border-slate-100 p-5 animate-pulse">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg {{ $pulseClass }}"></div>
                <div class="h-5 w-14 rounded-full {{ $shimmerClass }}"></div>
            </div>
            <div class="h-7 w-16 {{ $shimmerClass }} mb-2"></div>
            <div class="h-3 w-24 {{ $shimmerClass }}"></div>
        </div>

    @elseif ($type === 'text-block')
        <div class="space-y-3 animate-pulse">
            <div class="h-4 w-full {{ $shimmerClass }}"></div>
            <div class="h-4 w-5/6 {{ $shimmerClass }}"></div>
            <div class="h-4 w-4/6 {{ $shimmerClass }}"></div>
            <div class="h-4 w-full {{ $shimmerClass }}"></div>
            <div class="h-4 w-3/4 {{ $shimmerClass }}"></div>
        </div>

    @elseif ($type === 'image-card')
        <div class="bg-white rounded-xl border border-slate-100 overflow-hidden animate-pulse">
            <div class="h-40 {{ $shimmerClass }}"></div>
            <div class="p-4 space-y-3">
                <div class="h-4 w-3/4 {{ $shimmerClass }}"></div>
                <div class="h-3 w-full {{ $shimmerClass }}"></div>
                <div class="h-3 w-2/3 {{ $shimmerClass }}"></div>
            </div>
        </div>

    @elseif ($type === 'form')
        <div class="space-y-5 animate-pulse">
            @for ($j = 0; $j < 4; $j++)
                <div>
                    <div class="h-3.5 w-24 {{ $shimmerClass }} mb-2"></div>
                    <div class="h-11 w-full rounded-lg {{ $shimmerClass }}"></div>
                </div>
            @endfor
            <div class="h-12 w-full rounded-lg {{ $shimmerClass }}"></div>
        </div>

    @elseif ($type === 'notification')
        <div class="flex items-start gap-3 py-3 px-4 animate-pulse">
            <div class="w-2 h-2 mt-2 rounded-full {{ $pulseClass }} flex-shrink-0"></div>
            <div class="flex-1 min-w-0 space-y-2">
                <div class="h-3.5 w-3/4 {{ $shimmerClass }}"></div>
                <div class="h-2.5 w-full {{ $shimmerClass }}"></div>
                <div class="h-2 w-16 {{ $shimmerClass }}"></div>
            </div>
        </div>
    @endif
@endfor
