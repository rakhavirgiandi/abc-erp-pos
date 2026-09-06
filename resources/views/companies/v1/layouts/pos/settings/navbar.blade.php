<div id="sidebar-settings">
    <div class="user-information">
        <span class="mdi mdi-account-circle" style="font-size: 27px"></span>
        <div>
            <div class="name-info">{{ Session::get('_name') }}</div>
            <div class="email-info">{{ Session::get('_email') }}</div>
        </div>
    </div>
    <div class="sidebar-wrapper">
        <ul class="list-unstyled">
            <li>
                <a href="#" class="nav-item-settings"><span class="mdi mdi-receipt" style="font-size: 27px"></span> Receipt</a>
            </li>
        </ul>
    </div>
</div>