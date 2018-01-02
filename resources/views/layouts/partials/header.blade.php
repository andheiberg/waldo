<header>
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-main">
                    <span class="sr-only">{{ trans('navigation.toggle') }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('home') }}" title="{{ trans('brand.name') }}">
                    <img src="/images/waldo_logo.png">
                    <span>{{ trans('brand.name') }}</span>
                </a>
            </div>

            <div class="collapse navbar-collapse" id="nav-main">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/settings">Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
