<nav class="mb-1 navbar navbar-expand-lg navbar-dark" style="background-color: #002E5D;">
    <a class="navbar-brand" href="#">
        BetsonTasker
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
    aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    </button>
    <div class="collapse navbar-collapse" id="basicExampleNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" id="nightMode">
                    <span class="fa fa-moon text-white"></span>
                </a>
            </li>
            <li class="item">
                <a class="nav-link" href="profile.php?id=<?php echo $login_id; ?>">
                    <span class="fa fa-user-circle text-white"></span>
                </a>
            </li>
            <li class>
                <a class="nav-link text-white" id="btnLogout">
                    <span class="fas fa-power-off"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>