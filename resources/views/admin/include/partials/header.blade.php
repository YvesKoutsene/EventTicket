<div class="wrap">
    <div class="header-left">
        <a href="">
            <img class="" id="logo_header_mobile" alt="" src="\assets/images/logo/Img.jpg" data-light="\assets/images/logo/Img.jpg" data-dark="\assets/images/logo/Img.jpg" data-width="154px" data-height="52px" data-retina="\assets/images/logo/Img.jpg">
        </a>
        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
        <form class="form-search flex-grow">
            <fieldset class="name">
                <input type="text" placeholder="Rechercher ici..." class="show-search" name="name" tabindex="2" value="" aria-required="true" required="">
            </fieldset>
            <div class="button-submit">
                <button class="" type="submit"><i class="icon-search"></i></button>
            </div>
            <div class="box-content-search" id="box-content-search">
                <ul class="mb-24">
                    <li class="mb-14">
                        <div class="body-title">Top evenement</div>
                    </li>
                    <li class="mb-14">
                        <div class="divider"></div>
                    </li>
                    <li>
                        <ul>
                            <li class="mb-10">
                                <div class="divider"></div>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="">
                    <li class="mb-14">
                        <div class="body-title">Commande</div>
                    </li>
                    <li class="mb-14">
                        <div class="divider"></div>
                    </li>
                    <li>
                        <ul>
                            <li class="mb-10">
                                <div class="divider"></div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </form>
    </div>
    <div class="header-grid">
        <div class="header-item button-dark-light">
            <i class="icon-moon"></i>
        </div>

        <!--
        <div class="popup-wrap noti type-header">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="header-item">
                        <span class="text-tiny">!</span>
                        <i class="icon-bell"></i>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <h6>Notifications</h6>
                    </li>
                    <li>
                        <div class="noti-item w-full wg-user active">
                            <div class="image">
                                <img src="assets/images/avatar/user-11.png" alt="">
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <a href="#" class="body-title">Cameron Williamson</a>
                                    <div class="time">10:13 PM</div>
                                </div>
                                <div class="text-tiny">Hello?</div>
                            </div>
                        </div>
                    </li>
                    <li><a href="#" class="tf-button w-full">Voir tout</a></li>
                </ul>
            </div>
        </div>
        -->

        <!--
        <div class="header-item button-zoom-maximize">
            <div class="">
                <i class="icon-maximize"></i>
            </div>
        </div>
        <div class="popup-wrap apps type-header">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="header-item">
                        <i class="fas icon-layers"></i>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownMenuButton4">
                    <li>
                        <h6 class="tf-button w-full">Actions Rapides</h6>
                    </li>
                    <li>
                        <ul class="list-apps">
                            <li class="item">
                                <i class="icon-calendar"></i>
                                <div class="text-tiny">Calendrier</div>
                            </li>
                            <li class="item">
                                <i class="icon-phone-call"></i>
                                <div class="text-tiny">Contacts</div>
                            </li>
                            <li class="item">
                                <i class="icon-paperclip"></i>
                                <div class="text-tiny">Tâches</div>
                            </li>
                            <li class="item">
                                <i class="icon-edit"></i>
                                <div class="text-tiny">Notes</div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        -->

        <div class="popup-wrap user type-header">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="header-user wg-user custom-profile">
                        <span class="image">
                            <img src="{{ asset(Auth::user()->profile) }}" alt=" ">
                        </span>
                        <span class="flex flex-column">
                            <span class="body-title mb-2">{{ Auth::user()->name }}</span>
                            <span class="text-tiny">Admin</span>
                        </span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownMenuButton3">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="user-item">
                            <div class="icon">
                                <i class="icon-user"></i>
                            </div>
                            <div class="body-title-2">Profil</div>
                        </a>
                    </li>
                    <li>
                        <a href="" class="user-item">
                            <div class="icon">
                                <i class="icon-settings"></i>
                            </div>
                            <div class="body-title-2">Paramètre</div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" id="logout-link" class="user-item">
                            <div class="icon">
                                <i class="icon-log-out"></i>
                            </div>
                            <div class="body-title-2">Se deconnecter</div>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-profile .image {
        width: 35px;
        height: 35px;
        overflow: hidden;
        border-radius: 50%;
    }
    .custom-profile .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('logout-form').submit();
    });
</script>
