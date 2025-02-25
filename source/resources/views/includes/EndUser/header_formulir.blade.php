<div class="sticky-header">
    <header>                       
        <nav class="navbar navbar-b navbar-dark navbar-trans navbar-expand-xl fixed-top nav-padding" id="sidebar-menu"><a class="navbar-brand p-0" href="#"><img class="img-fluid for-light mx-auto d-block" style="width:130px;height:60px" src="{{asset('mofi/assets/images/logo/Logo_AMC_Full.png')}}" alt="looginpage"></a>
        <button class="navbar-toggler navabr_btn-set custom_nav" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
        <div class="navbar-collapse justify-content-center collapse hidenav" id="navbarDefault">
            <ul class="navbar-nav navbar_nav_modify" id="scroll-spy">
            <li class="nav-item"><a class="nav-link" href="{{'https://'.config('app.domains.pendaftaran_mandiri') }}" onclick="document.getElementById('formulir_mcu').scrollIntoView({ behavior: 'smooth' });">Formulir MCU</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('frameworks').scrollIntoView({ behavior: 'smooth' });">Alur Pendaftaran</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('feature').scrollIntoView({ behavior: 'smooth' });">Info MCU Terkini</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ env('APP_MAIN_URL')}}" target="_blank">Website</a></li>
            </ul>
        </div>
        </nav>
    </header>
</div>