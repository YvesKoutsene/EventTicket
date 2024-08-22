<div class="box-logo">
    <a href="" id="site-logo-inner">
        <div class="logo-container">
            <a href="{{ route('dashboard') }}" class="logo-link">
                <img src="\assets/images/logo/Img.jpg" class="logo-image">
                <span class="logo-text">EventTicket</span>
            </a>
        </div>
    </a>
    <div class="button-show-hide">
        <i class="icon-menu-left"></i>
    </div>
</div>
<div class="center">
    <div class="center-item">
        <div class="center-heading">Menu Principal</div>
        <ul class="menu-list">
            <li class="menu-item has-children {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-grid"></i></div>
                    <div class="text">Dashboard</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('dashboard') ? 'active-section' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <div class="text">Home 01</div>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="center-item">
        <div class="center-heading">Toutes Les Pages</div>

        <ul class="menu-list">
            <li class="menu-item has-children {{ request()->routeIs('event*') ||  request()->routeIs('form-showEvent*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-calendar"></i></div>
                    <div class="text">Evenement</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('event') ? 'active-section' : '' }}">
                        <a href="{{ route('event') }}">
                            <div class="text">Liste Evènement</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!--
            <li class="menu-item has-children {{ request()->routeIs('categorie*') ||  request()->routeIs('form-categorie*') || request()->routeIs('form-updateCatEvent*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-layers"></i></div>
                    <div class="text">Catégorie</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('categorie') ? 'active-section' : '' }}">
                        <a href="{{ route('categorie') }}">
                            <div class="text">Liste Categorie</div>
                        </a>
                    </li>
                    <li class="sub-menu-item {{ request()->routeIs('form-categorie') ? 'active-section' : '' }}">
                        <a href="{{ route('form-categorie') }}">
                            <div class="text">Nouvelle Categorie</div>
                        </a>
                    </li>
                </ul>
            </li> -->
            <!--
            <li class="menu-item has-children {{ request()->routeIs('billet*') || request()->routeIs('form-showBillet*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-tag"></i></div>
                    <div class="text">Billet</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('billet') ? 'active-section' : '' }}">
                        <a href="{{ route('billet') }}">
                            <div class="text">Liste Billet</div>
                        </a>
                    </li>
                </ul>
            </li>
            -->
            <!--
            <li class="menu-item has-children {{ request()->routeIs('type*') ||  request()->routeIs('form-type*') ||  request()->routeIs('form-updateType*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-book"></i></div>
                    <div class="text">Type</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('type') ? 'active-section' : '' }}">
                        <a href="{{ route('type') }}">
                            <div class="text">Liste Type</div>
                        </a>
                    </li>
                    <li class="sub-menu-item {{ request()->routeIs('form-type') ? 'active-section' : '' }}">
                        <a href="{{ route('form-type') }}">
                            <div class="text">Nouvel Type</div>
                        </a>
                    </li>
                </ul>
            </li>
            -->
<!--
            <li class="menu-item has-children {{ request()->routeIs('avis*')  ? 'active' : '' }} ">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-message-square"></i></div>
                    <div class="text">Avis</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('avis') ? 'active-section' : '' }} ">
                        <a href="{{ route('avis') }}">
                            <div class="text">Liste Avis</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item has-children {{ request()->routeIs('commande*')  ? 'active' : '' }} ">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-file"></i></div>
                    <div class="text">Commande</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('commande') ? 'active-section' : '' }}">
                        <a href="{{ route('commande') }}">
                            <div class="text">Liste Commande</div>
                        </a>
                    </li>
                </ul>
            </li>
-->
            <li class="menu-item has-children {{ request()->routeIs('user*') || request()->routeIs('form-user*')? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-item-button">
                    <div class="icon"><i class="icon-user"></i></div>
                    <div class="text">Utilisateur</div>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item {{ request()->routeIs('user') ? 'active-section' : '' }}">
                        <a href="{{ route('user') }}">
                            <div class="text">Liste Utilisateur</div>
                        </a>
                    </li>

                    <!--
                    <li class="sub-menu-item {{ request()->routeIs('form-user') ? 'active-section' : '' }}">
                        <a href="{{ route('form-user') }}">
                            <div class="text">Nouvel Utilisateur</div>
                        </a>
                    </li>
                    -->

                </ul>
            </li>
        </ul>
    </div>
    <div class="center-item">
        <div class="center-heading">Paramètre</div>
        <ul class="menu-list">
            <li class="menu-item {{ request()->routeIs('profile.edit*') ? 'active' : '' }}">
                <a href="{{ route('profile.edit') }}">
                    <div class="icon"><i class="icon-user"></i></div>
                    <div class="text">Mon Compte</div>
                </a>
            </li>
        </ul>
    </div>
</div>

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
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 8px;
    }

    .logo-text {
        font-size: 20px;
        font-weight: bold;
        line-height: 1.5;
    }

    .active-section {
        border-right: 5px solid #007bff;
    }

</style>
