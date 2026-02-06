<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            
            @can('student-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
            </li>
            @endcan
            
            @can('payment-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                    <i class="fas fa-money-bill"></i> Payments
                </a>
            </li>
            @endcan
            
            @can('class-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                    <i class="fas fa-chalkboard"></i> Classes
                </a>
            </li>
            @endcan
            
            @can('expense-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                    <i class="fas fa-receipt"></i> Expenses
                </a>
            </li>
            @endcan
            
            @can('account-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                    <i class="fas fa-book"></i> Accounts
                </a>
            </li>
            @endcan
            
            @can('user-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            @endcan
            
            @can('role-list')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <i class="fas fa-user-shield"></i> Roles & Permissions
                </a>
            </li>
            @endcan
            
            @can('report-view')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            @endcan
        </ul>
    </div>
</nav>