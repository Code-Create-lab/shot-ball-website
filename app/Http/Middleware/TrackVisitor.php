<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Record the visit after the response is built, so tracking never
     * delays or breaks the page. One row per session, bumped each load.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            $this->record($request);
        }

        return $response;
    }

    protected function shouldTrack(Request $request): bool
    {
        // Only real page views: GET, not AJAX/Livewire, not the admin panel.
        if (! $request->isMethod('GET') || $request->ajax() || $request->wantsJson()) {
            return false;
        }

        // Don't count logged-in admins browsing their own site.
        if (Auth::check()) {
            return false;
        }

        return ! $request->is('admin', 'admin/*', 'livewire/*', 'up');
    }

    protected function record(Request $request): void
    {
        try {
            $sessionId = $request->session()->getId();

            $existing = Visitor::where('session_id', $sessionId)->first();

            if ($existing) {
                $existing->forceFill([
                    'visits'       => DB::raw('visits + 1'),
                    'last_url'     => mb_substr($request->fullUrl(), 0, 512),
                    'last_seen_at' => now(),
                ])->save();

                return;
            }

            Visitor::create([
                'session_id'   => $sessionId,
                'ip_address'   => $request->ip(),
                'user_agent'   => mb_substr((string) $request->userAgent(), 0, 512),
                'last_url'     => mb_substr($request->fullUrl(), 0, 512),
                'visits'       => 1,
                'last_seen_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Never let analytics break a page render.
            report($e);
        }
    }
}
