@php
    $user = auth()->user();
    $canPerformActions = $user->canPerformActions(request()->route()->getName());
    $isViewerRole = $user->user_type === 'مراقب';
    $isSupervisorRole = $user->user_type === 'مشرف';
    $isTeacherRoute = str_contains(request()->route()->getName(), 'teacher.');
@endphp

@if($canPerformActions && (!$isSupervisorRole || $isTeacherRoute))
    {{ $slot }}
@endif