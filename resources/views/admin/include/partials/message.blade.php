@if (session('success') || session('error') || $errors->any())
<div class="alert hide" id="snackbar">
    @if (session('success'))
    <div class="alert-content success">
        <div class="block-warning type-main">
            <i class="icon-alert-octagon"></i>
        </div>
        <ul class="body-title-2 body-y-2">
            {{ session('success') }}
        </ul>
    </div>
    @endif

    @if (session('error'))
    <div class="alert-content error">
        <div class="block-warning">
            <i class="icon-alert-octagon"></i>
        </div>
        <ul class="body-title-2 body-y-2">
            <li>{{ session('error') }}</li>
        </ul>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert-content error">
        <div class="block-warning">
            <i class="icon-alert-octagon"></i>
        </div>
        <ul class="body-title-2 body-y-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }

    .body-y-2 {
        color: #fff;
    }

    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 250px;
        background-color: #333;
        color: #fff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1000;
        pointer-events: none;
    }

    .alert.show {
        opacity: 1;
    }

    .alert.success {
        background-color: #4CAF50;
    }

    .alert.error {
        background-color: #f44336;
    }

    .alert .icon {
        margin-right: 10px;
    }

    .alert ul {
        padding-left: 20px;
        margin: 5px 0;
    }

    .alert ul li {
        list-style-type: none;
    }

    .alert-content {
        display: flex;
        align-items: center;
        pointer-events: auto;
    }

    .alert-content .msg {
        flex: 1;
    }

    .alert-content .icon {
        font-size: 20px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>

<script>
    $(document).ready(function() {
        $('.alert').addClass('show');

        $('.alert .close-btn').click(function() {
            $('.alert').removeClass('show');
        });

        setTimeout(function() {
            $('.alert').removeClass('show');
            setTimeout(function() {
                $('.alert').remove();
            }, 300);
        }, 8000);
    });
</script>
@endif
