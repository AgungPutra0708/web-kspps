<div class="navigation">
    <ul>
        <li class="list {{ Route::is('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}">
                <span class="icon">
                    <i class="fas fa-home"></i>
                </span>
                <span class="text">Home</span>
                <span class="circle"></span>
            </a>
        </li>
        <li class="list {{ Route::is('message') ? 'active' : '' }}">
            <a href="{{ route('message') }}">
                <span class="icon">
                    <i class="fas fa-info"></i>
                </span>
                <span class="text">Message</span>
                <span class="circle"></span>
            </a>
        </li>
        <li class="list {{ Route::is('profile') ? 'active' : '' }}">
            <a href="{{ route('profile') }}">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
                <span class="text">Profile</span>
                <span class="circle"></span>
            </a>
        </li>
        <div class="indicator"></div>
    </ul>
</div>
