@can('sms_view')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fal fa-comments-alt"></i> @lang('rl_sms::rl_sms.package_name')</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a class="nav-link" href="{{ url()->route('rl_sms.admin.sms.index') }}"><i class="fal fa-comments-alt"></i> @lang('rl_sms::rl_sms.package_name')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url()->route('rl_sms.admin.senders.index') }}"><i class="fal fa-user-edit"></i> @lang('rl_sms::rl_sms.senders')</a>
        </li>
    </ul>
</li>
@endcan
