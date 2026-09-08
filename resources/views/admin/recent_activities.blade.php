@extends('layout.main')

@section('title', 'Recent Activities')

@section('content')

{{ html()->a(route('admin.show_admin_dashboard'))->class("btn btn-sm btn-primary")->text("Go To Dashboard") }}
    <div class="admin-table-card">
        <div class="table-header">
            <h6>Recent Activities</h6>
        </div>

        <div class="admin-table-wrap table-responsive">
            <table class="table admin-table" id="tblProducts">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Description</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentActivities as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-primary fw-bold">{{ $value->activity_description }}</td>
                            <td class="text-success fw-bold">{{ $value->created_at->format('M j, Y, h:i A') }} ({{ $value->created_at->diffForHumans() }})</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No Recent Activities Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $recentActivities->links() }}
        </div>

    @endsection
