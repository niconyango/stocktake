<body class="skin-black layout-top-nav sidebar-mini">
<div class="wrapper d-flex-layout">
    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo base_url('stocks'); ?>"><b><?php echo $config; ?>&nbsp;<?php
                        if ($config != 1) {
                            echo 'BRANCH';
                        } else {
                        } ?></b></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <?php if ($this->session->userdata('SecurityLevel') == 19 || $this->session->userdata('SecurityLevel') == 5) { ?>
                            <li class="nav-item active"><a class="nav-link" href="<?php echo base_url('dashboard'); ?>"><i
                                            class="far fa-chart-network"></i>&nbsp;<span>Dashboard</span>
                                </a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('fstocks'); ?>"><i
                                            class="fal fa-play-circle"></i><span>&nbsp;Freeze Stocks</span></a></li>
                        <?php } ?>
                        <?php if ($this->session->userdata('SecurityLevel') == 0 || $this->session->userdata('SecurityLevel') == 0) { ?>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown"><i class="fas fa-database"></i>
                                    <span>Database </span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="text-decoration-none" href="<?php echo base_url('users'); ?>"><i class="fas fa-users"></i>&nbsp;
                                            Users</a></li>
                                    <?php if ($this->session->userdata('SecurityLevel') == 19 || $this->session->userdata('SecurityLevel') == 5) { ?>
                                        <li class="divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo base_url('departments'); ?>"><i
                                                        class="fas fa-folder"></i>&nbsp;
                                                Departments</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo base_url('items'); ?>"><i
                                                        class="fas fa-cubes"></i>&nbsp; Items</a></li>
                                        <li class="divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo base_url('suppliers'); ?>"><i
                                                        class="fas fa-truck"></i>&nbsp;
                                                Suppliers</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo base_url('customers'); ?>"><i
                                                        class="fas fa-user-friends"></i>&nbsp;
                                                Customers</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($stocktakestatus == 1) { ?>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link" role="button" id="navbarDropdown" data-bs-toggle="dropdown">
                                    <i class="fal fa-desktop"></i>
                                    <span>Stock Take <span class="caret"></span></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="<?php echo base_url('fsheets'); ?>"><i
                                                    class="fad fa-spinner"></i>&nbsp; Feed
                                            Sheets</a>
                                    </li>
                                    <?php if ($this->session->userdata('SecurityLevel') == 0 || $this->session->userdata('SecurityLevel') == 0) { ?>
                                        <li class="divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo base_url('import_sheets'); ?>"><i class="fas
                                        fa-file-import"></i>&nbsp;
                                                Import Sheets</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($this->session->userdata('SecurityLevel') == 19 || $this->session->userdata('SecurityLevel') == 5) { ?>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown" id="navbarDropdown" role="button">
                                    <i class="fal fa-chart-line"></i>
                                    <span>Transactions <span class="caret"></span></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="<?php echo base_url('syncstocksheets'); ?>"><i
                                                    class="fal fa-sync"></i>&nbsp; Sync
                                            Sheets</a></li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo base_url('stocksposting'); ?>"><i
                                                    class="fal fa-circle"></i>&nbsp; Post
                                            Stocks</a></li>

                                </ul>
                            </li>
                        <?php } ?>
                        <!-- <li><a href="<?php echo base_url(); ?>stocksposting"><i class="nav-icon far fa-circle text-success"></i><span>&nbsp;Post Stocks</span></a></li> -->
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link" data-bs-toggle="dropdown" id="navbarDropdown" role="button">
                                <i class="fal fa-file-chart-line"></i>
                                <span>Reports <span class="caret"></span></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="<?php echo base_url('holdings'); ?>"><i class="fal fa-server"></i>&nbsp;
                                        Stocktake
                                        Report</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo base_url('stocks'); ?>"><i class="fal fa-tags"></i>&nbsp;
                                        Counted SKUs</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo base_url('uncounted'); ?>"><i
                                                class="fal fa-shopping-basket"></i>&nbsp;
                                        Un-counted</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo base_url('binsheets'); ?>"><i
                                                class="fal fa-bags-shopping"></i>&nbsp; Shelf/Bin
                                        Counts</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo base_url('history'); ?>"><i class="fal
                                fa-history"></i>&nbsp;
                                        Historical Stock
                                        Take</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown" style="font-size:large; font-weight: bold; font-family: initial;"><a
                                    class="text-decoration-none" href="<?php echo base_url('profile'); ?>" class="dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                <img src="<?php echo base_url(); ?>assets/theme/img/pixel_weave.png" style="width:30px; height:30px;"
                                     class="user-image rounded-circle" alt="User Image"/>
                                <?php echo $this->session->userdata('Name'); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" data-bs-target="#pass-modal" data-bs-toggle="modal"> <i
                                                class="fad fa-user-circle"></i>&nbsp;
                                        Change
                                        Password</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo base_url('logout'); ?>"><i class="fal fa-power-off"></i>&nbsp;
                                        Sign-out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </header>

    <div class="modal modal-default" id="pass-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
         aria-bs-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-bs-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert" role="alert" id="error" style="display:none"></div>
                    <form role="form" id="pass-form">
                        <input type="hidden" name="actionm" id="actionm" value="0"/>
                        <!-- text input -->
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="pass" id="password" autocomplete="off" class="form-control"
                                   placeholder="Password"/>
                            <span toggle="#Password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <!-- text input -->
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="SecurityConfirm" id="SecurityConfirm" class="form-control"
                                   placeholder="Confirm Password"/>
                            <span toggle="#Password-confirm" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat" data-bs-dismiss="modal"><i class="fas fa-ban"></i>&nbsp;Close
                    </button>
                    <button type="button" class="btn btn-primary btn-flat" id="btn-save"><i class="fas fa-lock"></i>&nbsp;Update</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script type="text/javascript">
        $(document).ready(function () {
            $(".toggle-password").click(function () {

                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

            $("#btn-save").click(function (e) {

                var url = "<?php echo base_url('update_password'); ?>";
                var data = $("#pass-form").serialize();
                // alert(data)
                $.ajax({
                    type: "POST",
                    data: data,
                    url: url,
                    success: function (data) {
                        $("#error").html(data).show().addClass("alert-success");
                        location.reload();
                    }
                });

            })
        })
    </script>