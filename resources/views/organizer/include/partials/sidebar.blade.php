<div class="user">
    <div class="avatar-sm float-left mr-2">
        <img src="{{ asset(Auth::user()->profile) }}" alt="No picture" class="avatar-img rounded-circle">
    </div>

    <div class="info">
        <a data-toggle="collapse" href="#Eventticket" aria-expanded="true">
            <span>
                {{ Auth::user()->name }}
                <span class="user-level">Organisateur</span>
                <span class="caret"></span>
            </span>
        </a>
        <div class="clearfix"></div>
        <div class="collapse in" id="Eventticket">
            <ul class="nav">
                <li>
                    <a href="{{ route('myProfile') }}" class="{{ request()->routeIs('userGuide') ? 'active' : '' }}">
                        <span class="link-collapse">Profil</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" id="logout-link">
                        <span class="link-collapse">Déconnexion</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<ul class="nav nav-primary">
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a data-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="{{ request()->routeIs('dashboard') ? 'true' : 'false' }}">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
            <span class="caret"></span>
        </a>
        <div class="collapse {{ request()->routeIs('dashboard') ? 'show' : '' }}" id="dashboard">
            <ul class="nav nav-collapse">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-section' : '' }}">
                        <span class="sub-item">Home 01</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-section">
        <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">Pages</h4>
    </li>
    <li class="nav-item {{ request()->routeIs('myEvent') || request()->routeIs('form-myEvent') || request()->routeIs('form-updateMyEvent')? 'active' : '' }}">
        <a data-toggle="collapse" href="#evenement" aria-expanded="{{ request()->routeIs('myEvent') || request()->routeIs('form-myEvent') ? 'true' : 'false' }}">
            <i class="fas fa-calendar"></i>
            <p>Evènement</p>
            <span class="caret"></span>
        </a>
        <div class="collapse {{ request()->routeIs('myEvent') || request()->routeIs('form-myEvent') ? 'show' : '' }}" id="evenement">
            <ul class="nav nav-collapse">
                <li>
                    <a href="{{ route('myEvent') }}" class="{{ request()->routeIs('myEvent') ? 'active-section' : '' }}">
                        <span class="sub-item">Mes Evénements</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('form-myEvent') }}" class="{{ request()->routeIs('form-myEvent') ? 'active-section' : '' }}">
                        <span class="sub-item">Créer Evènement</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item {{ request()->routeIs('myBillet') || request()->routeIs('form-myBillet') || request()->routeIs('form-updateMyBillet') ? 'active' : '' }}">
        <a data-toggle="collapse" href="#billet" aria-expanded="{{ request()->routeIs('myBillet') || request()->routeIs('form-myBillet') ? 'true' : 'false' }}">
            <i class="fas fa-tag"></i>
            <p>Billet</p>
            <span class="caret"></span>
        </a>
        <div class="collapse {{ request()->routeIs('myBillet') || request()->routeIs('form-myBillet') ? 'show' : '' }}" id="billet">
            <ul class="nav nav-collapse">
                <li>
                    <a href="{{ route('myBillet') }}" class="{{ request()->routeIs('myBillet') ? 'active-section' : '' }}">
                        <span class="sub-item">Mes Billets</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('form-myBillet') }}" class="{{ request()->routeIs('form-myBillet') ? 'active-section' : '' }}">
                        <span class="sub-item">Ajouter Billet</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item {{ request()->routeIs('myEventNotice') ? 'active' : '' }}">
        <a data-toggle="collapse" href="#avis" aria-expanded="{{ request()->routeIs('myEventNotice') ? 'true' : 'false' }}">
            <i class="fas fa-comment"></i>
            <p>Avis</p>
            <span class="caret"></span>
        </a>
        <div class="collapse {{ request()->routeIs('myEventNotice') ? 'show' : '' }}" id="avis">
            <ul class="nav nav-collapse">
                <li>
                    <a href="{{ route('myEventNotice') }}" class="{{ request()->routeIs('myEventNotice') ? 'active-section' : '' }}">
                        <span class="sub-item">Liste Avis</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item {{ request()->routeIs('mySalle') ? 'active' : '' }}">
        <a data-toggle="collapse" href="#commande" aria-expanded="{{ request()->routeIs('mySalle') ? 'true' : 'false' }}">
            <i class="fas fa-list"></i>
            <p>Commande</p>
            <span class="caret"></span>
        </a>
        <div class="collapse {{ request()->routeIs('mySalle') ? 'show' : '' }}" id="commande">
            <ul class="nav nav-collapse">
                <li>
                    <a href="{{ route('mySalle') }}" class="{{ request()->routeIs('mySalle') ? 'active-section' : '' }}">
                        <span class="sub-item">Mes Ventes</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-section">
        <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">Paramètre</h4>
    </li>

    <!--
    <li class="nav-item {{ request()->routeIs('myProfile') || request()->routeIs('myProfiledit') ? 'active' : '' }}">
        <a href="{{ route('myProfile') }}">
            <i class="fas fa-chart-pie"></i>
            <p>Rapport</p>
        </a>
    </li>
    -->

    <li class="nav-item {{ request()->routeIs('myProfile') || request()->routeIs('myProfiledit') ? 'active' : '' }}">
        <a href="{{ route('myProfile') }}">
            <i class="fas fa-user"></i>
            <p>Compte</p>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('userGuide') ? 'active' : '' }}">
        <a href="{{ route('userGuide') }}">
            <i class="fas fa-info"></i>
            <p>Guide</p>
            <span class="badge badge-success">!</span>
        </a>
    </li>
</ul>

<script>
    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('logout-form').submit();
    });
</script>

<style>
    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo-link {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .logo-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 8px;
    }

    .logo-text {
        font-size: 15px;
        font-weight: bold;
        color: ghostwhite;
        line-height: 3.5;
    }

    .active-section{
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
        padding-left: 10px;
    }

</style>



