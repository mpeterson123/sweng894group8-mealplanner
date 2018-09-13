<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Login
///////////////////////////////////////////////////////////////////////////////
require_once( __DIR__ . '/../modules/main.mod.php' );

// Sub Title
$SUBTITLE = 'Login';

// Plugins
$PLUGIN_SLIMSCROLL = TRUE;
$PLUGIN_WAVES      = TRUE;
$PLUGIN_SIDEBARMENU= TRUE;
?>
<?php require_once( __HEADER__ ); ?>

<body class="mini-sidebar">
    <!-- ===== Main-Wrapper ===== -->
    <div id="wrapper">
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>

        <!-- ===== Top-Navigation ===== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <a class="navbar-toggle font-20 hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="top-left-part">
                    <a class="logo" href="/dashboard/">
                        <b>
                            <img src="/plugins/images/logo.png" alt="home" />
                        </b>
                        <span>
                            <img src="/plugins/images/logo-text.png" alt="homepage" class="dark-logo" />
                        </span>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li>
                        <a href="javascript:void(0)" class="sidebartoggler font-20 waves-effect waves-light"><i class="icon-arrow-left-circle"></i></a>
                    </li>
                    <li>
                        <form role="search" class="app-search hidden-xs">
                            <i class="icon-magnifier"></i>
                            <input type="text" placeholder="Search..." class="form-control">
                        </form>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle waves-effect waves-light font-20" data-toggle="dropdown" href="javascript:void(0);">
                            <i class="icon-speech"></i>
                            <span class="badge badge-xs badge-danger">6</span>
                        </a>
                        <ul class="dropdown-menu mailbox animated bounceInDown">
                            <li>
                                <div class="drop-title">You have 4 new messages</div>
                            </li>
                            <li>
                                <div class="message-center">
                                    <a href="javascript:void(0);">
                                        <div class="user-img">
                                            <img src="/plugins/images/users/avatar4.jpg" alt="user" class="img-circle">
                                            <span class="profile-status online pull-right"></span>
                                        </div>
                                        <div class="mail-contnet">
                                            <h5>Lawrence Sell</h5>
                                            <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:30 AM</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0);">
                                        <div class="user-img">
                                            <img src="/plugins/images/users/avatar2.jpg" alt="user" class="img-circle">
                                            <span class="profile-status busy pull-right"></span>
                                        </div>
                                        <div class="mail-contnet">
                                            <h5>Camila Perez</h5>
                                            <span class="mail-desc">I've sung a eating song! See you at</span>
                                            <span class="time">9:10 AM</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0);">
                                        <div class="user-img">
                                            <img src="/plugins/images/users/avatar5.jpg" alt="user" class="img-circle"><span class="profile-status away pull-right"></span>
                                        </div>
                                        <div class="mail-contnet">
                                            <h5>Andrew Drake</h5>
                                            <span class="mail-desc">I am a picky eater!</span>
                                            <span class="time">9:08 AM</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0);">
                                        <div class="user-img">
                                            <img src="/plugins/images/users/avatar4.jpg" alt="user" class="img-circle">
                                            <span class="profile-status offline pull-right"></span>
                                        </div>
                                        <div class="mail-contnet">
                                            <h5>Lawrence Sell</h5>
                                            <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:02 AM</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="text-center" href="javascript:void(0);">
                                    <strong>See all notifications</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle waves-effect waves-light font-20" data-toggle="dropdown" href="javascript:void(0);">
                            <i class="icon-calender"></i>
                            <span class="badge badge-xs badge-danger">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-tasks animated slideInUp">
                            <li>
                                <a href="javascript:void(0);">
                                    <div>
                                        <p>
                                            <strong>Task 1</strong>
                                            <span class="pull-right text-muted">40% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                                <span class="sr-only">40% Complete (success)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div>
                                        <p>
                                            <strong>Task 2</strong>
                                            <span class="pull-right text-muted">20% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div>
                                        <p>
                                            <strong>Task 3</strong>
                                            <span class="pull-right text-muted">60% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                <span class="sr-only">60% Complete (warning)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div>
                                        <p>
                                            <strong>Task 4</strong>
                                            <span class="pull-right text-muted">80% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                                <span class="sr-only">80% Complete (danger)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="javascript:void(0);">
                                    <strong>See All Tasks</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="right-side-toggle">
                        <a class="right-side-toggler waves-effect waves-light b-r-0 font-20" href="javascript:void(0)">
                            <i class="icon-settings"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- ===== Top-Navigation-End ===== -->

        <!-- ===== Left-Sidebar ===== -->
        <aside class="sidebar">
            <div class="scroll-sidebar">
                <div class="user-profile">
                    <div class="dropdown user-pro-body">
                        <div class="profile-image">
                            <img src="/plugins/images/users/avatar6.jpg" alt="user-img" class="img-circle">
                            <a href="javascript:void(0);" class="dropdown-toggle u-dropdown text-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="badge badge-danger">
                                    <i class="fa fa-angle-down"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated flipInY">
                                <li><a href="javascript:void(0);"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="javascript:void(0);"><i class="fa fa-inbox"></i> Inbox</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="javascript:void(0);"><i class="fa fa-cog"></i> Account Settings</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href=""><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </div>
                        <p class="profile-text m-t-15 font-16"><a href="javascript:void(0);"> Prof. Sangwan</a></p>
                    </div>
                </div>
                <nav class="sidebar-nav">
                    <ul id="side-menu">
                        <li>
                            <a class="active waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="icon-screen-desktop fa-fw"></i> <span class="hide-menu"> Dashboard <span class="label label-rounded label-info pull-right">1</span></span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="/dashboard/">Modern Version</a> </li>
                                <li> <a href="javascript:void();">Clean Version</a> </li>
                                <li> <a href="javascript:void();">Analytical Version</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="icon-cup fa-fw"></i> <span class="hide-menu"> Food<span class="label label-rounded label-success pull-right">2</span></span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/foods/">View Food</a></li>
                                <li><a href="/foods/add/">Add Food Item</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="icon-book-open fa-fw"></i> <span class="hide-menu"> Recipes<span class="label label-rounded label-danger pull-right">0</span></span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="javascript:void(0);">View Recipes</a></li>
                                <li><a href="javascript:void(0);">Add Recipe</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="p-30">
                    <span class="hide-menu">
                        <a href="https://www.cubictheme.ga/cubic-html/" target="_blank" class="btn btn-default m-t-15">Help / Reference</a>
                    </span>
                </div>
            </div>
        </aside>
        <!-- ===== Left-Sidebar-End ===== -->

        <!-- ===== Page-Content ===== -->
        <div class="page-wrapper">
            <div class="row m-0">
                <div class="col-md-3 col-sm-6 info-box">
                    <div class="media">
                        <div class="media-left">
                            <span class="icoleaf bg-primary text-white"><i class="mdi mdi-checkbox-marked-circle-outline"></i></span>
                        </div>
                        <div class="media-body">
                            <h3 class="info-count text-blue">154</h3>
                            <p class="info-text font-12">Food Items</p>
                            <span class="hr-line"></span>
                            <p class="info-ot font-15">Target<span class="label label-rounded label-success">300</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 info-box">
                    <div class="media">
                        <div class="media-left">
                            <span class="icoleaf bg-primary text-white"><i class="mdi mdi-comment-text-outline"></i></span>
                        </div>
                        <div class="media-body">
                            <h3 class="info-count text-blue">68</h3>
                            <p class="info-text font-12">Recipes</p>
                            <span class="hr-line"></span>
                            <p class="info-ot font-15">Total Used<span class="label label-rounded label-danger">14</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 info-box">
                    <div class="media">
                        <div class="media-left">
                            <span class="icoleaf bg-primary text-white"><i class="mdi mdi-coin"></i></span>
                        </div>
                        <div class="media-body">
                            <h3 class="info-count text-blue">&#36;947</h3>
                            <p class="info-text font-12">Food Cost</p>
                            <span class="hr-line"></span>
                            <p class="info-ot font-15">Savings : <span class="text-blue font-semibold">&#36;578</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 info-box b-r-0">
                    <div class="media">
                        <div class="media-left p-r-5">
                            <div id="earning" class="e" data-percent="12">
                                <div id="pending" class="p" data-percent="55"></div>
                                <div id="booking" class="b" data-percent="50"></div>
                            </div>
                        </div>
                        <div class="media-body">
                            <h2 class="text-blue font-22 m-t-0">Report</h2>
                            <ul class="p-0 m-b-20">
                                <li><i class="fa fa-circle m-r-5 text-primary"></i>12% Wasted</li>
                                <li><i class="fa fa-circle m-r-5 text-primary"></i>55% Target Reached</li>
                                <li><i class="fa fa-circle m-r-5 text-info"></i>50% Perishables</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ===== Page-Container ===== -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="white-box stat-widget">
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <h4 class="box-title">Statistics</h4>
                                </div>
                                <div class="col-md-9 col-sm-9">
                                    <select class="custom-select">
                                        <option selected value="0">Feb 04 - Mar 03</option>
                                        <option value="1">Mar 04 - Apr 03</option>
                                        <option value="2">Apr 04 - May 03</option>
                                        <option value="3">May 04 - Jun 03</option>
                                    </select>
                                    <ul class="list-inline">
                                        <li>
                                            <h6 class="font-15"><i class="fa fa-circle m-r-5 text-success"></i>New Groceries</h6>
                                        </li>
                                        <li>
                                            <h6 class="font-15"><i class="fa fa-circle m-r-5 text-primary"></i>Existing Groceries</h6>
                                        </li>
                                    </ul>
                                </div>
                                <div class="stat chart-pos"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box">
                            <h4 class="box-title">Task Progress</h4>
                            <div class="task-widget t-a-c">
                                <div class="task-chart" id="sparklinedashdb"></div>
                                <div class="task-content font-16 t-a-c">
                                    <div class="col-sm-6 b-r">
                                        Urgent Tasks
                                        <h1 class="text-primary">05 <span class="font-16 text-muted">Tasks</span></h1>
                                    </div>
                                    <div class="col-sm-6">
                                        Normal Tasks
                                        <h1 class="text-primary">03 <span class="font-16 text-muted">Tasks</span></h1>
                                    </div>
                                </div>
                                <div class="task-assign font-16">
                                    Friends
                                    <ul class="list-inline">
                                        <li class="p-l-0">
                                            <img src="/plugins/images/users/avatar1.jpg" alt="user" data-toggle="tooltip" data-placement="top" title="" data-original-title="Steave">
                                        </li>
                                        <li>
                                            <img src="/plugins/images/users/avatar2.jpg" alt="user" data-toggle="tooltip" data-placement="top" title="" data-original-title="Steave">
                                        </li>
                                        <li>
                                            <img src="/plugins/images/users/avatar3.jpg" alt="user" data-toggle="tooltip" data-placement="top" title="" data-original-title="Steave">
                                        </li>
                                        <li class="p-r-0">
                                            <a href="javascript:void(0);" class="btn btn-success font-16">3+</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box bg-primary color-box">
                            <h1 class="text-white font-light">&#36;6547 <span class="font-14">Food Cost</span></h1>
                            <div class="ct-revenue chart-pos"></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="white-box bg-success color-box">
                            <h1 class="text-white font-light m-b-0">5247</h1>
                            <span class="hr-line"></span>
                            <p class="cb-text">current groceries</p>
                            <h6 class="text-white font-semibold">+25% <span class="font-light">Last Week</span></h6>
                            <div class="chart">
                                <div class="ct-visit chart-pos"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="white-box bg-danger color-box">
                            <h1 class="text-white font-light m-b-0">25%</h1>
                            <span class="hr-line"></span>
                            <p class="cb-text">Finished Tasks</p>
                            <h6 class="text-white font-semibold">+15% <span class="font-light">Last Week</span></h6>
                            <div class="chart">
                                <input class="knob" data-min="0" data-max="100" data-bgColor="#f86b4a" data-fgColor="#ffffff" data-displayInput=false data-width="96" data-height="96" data-thickness=".1" value="25" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box user-table">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="box-title">Table Format/User Data</h4>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-inline">
                                        <li>
                                            <a href="javascript:void(0);" class="btn btn-default btn-outline font-16"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="btn btn-default btn-outline font-16"><i class="fa fa-commenting" aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                    <select class="custom-select">
                                        <option selected>Sort by</option>
                                        <option value="1">Name</option>
                                        <option value="2">Location</option>
                                        <option value="3">Type</option>
                                        <option value="4">Role</option>
                                        <option value="5">Action</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c1" type="checkbox">
                                                    <label for="c1"></label>
                                                </div>
                                            </th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Type</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c2" type="checkbox">
                                                    <label for="c2"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Daniel Kristeen</a></td>
                                            <td>Texas, US</td>
                                            <td>Recipes 564</td>
                                            <td><span class="label label-success">Admin</span></td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c3" type="checkbox">
                                                    <label for="c3"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Prof. Sangwan</a></td>
                                            <td>Los Angeles, US</td>
                                            <td>Recipes 451</td>
                                            <td><span class="label label-info">Staff</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c4" type="checkbox">
                                                    <label for="c4"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Jeffery Brown</a></td>
                                            <td>Houston, US</td>
                                            <td>Recipes 978</td>
                                            <td><span class="label label-danger">User</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c5" type="checkbox">
                                                    <label for="c5"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Elliot Dugteren</a></td>
                                            <td>San Antonio, US</td>
                                            <td>Recipes 34</td>
                                            <td><span class="label label-warning">General</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c6" type="checkbox">
                                                    <label for="c6"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Sergio Milardovich</a></td>
                                            <td>Jacksonville, US</td>
                                            <td>Recipes 31</td>
                                            <td><span class="label label-primary">Partial</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination">
                                <li class="disabled"> <a href="#">1</a> </li>
                                <li class="active"> <a href="#">2</a> </li>
                                <li> <a href="#">3</a> </li>
                                <li> <a href="#">4</a> </li>
                                <li> <a href="#">5</a> </li>
                            </ul>
                            <a href="javascript:void(0);" class="btn btn-success pull-right m-t-10 font-20">+</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="white-box">
                            <div class="task-widget2">
                                <div class="task-image">
                                    <img src="/plugins/images/task.jpg" alt="task" class="img-responsive">
                                    <div class="task-image-overlay"></div>
                                    <div class="task-detail">
                                        <h2 class="font-light text-white m-b-0">07 April</h2>
                                        <h4 class="font-normal text-white m-t-5">Your tasks for today</h4>
                                    </div>
                                    <div class="task-add-btn">
                                        <a href="javascript:void(0);" class="btn btn-success">+</a>
                                    </div>
                                </div>
                                <div class="task-total">
                                    <p class="font-16 m-b-0"><strong>5</strong> Tasks for <a href="javascript:void(0);" class="text-link">Jon Doe</a></p>
                                </div>
                                <div class="task-list">
                                    <ul class="list-group">
                                        <li class="list-group-item bl-info">
                                            <div class="checkbox checkbox-success">
                                                <input id="c7" type="checkbox">
                                                <label for="c7">
                                                    <span class="font-16">Create list of ingredients for new recipe.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">05:00 PM</h6>
                                            </div>
                                        </li>
                                        <li class="list-group-item bl-warning">
                                            <div class="checkbox checkbox-success">
                                                <input id="c8" type="checkbox" checked>
                                                <label for="c8">
                                                    <span class="font-16">Send daughter to pickup <strong>50 onions</strong> on 23 May to <a href="javascript:void(0);" class="text-link">Daniel Kristeen</a> for the friend onion fest.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">03:00 PM</h6>
                                            </div>
                                        </li>
                                        <li class="list-group-item bl-danger">
                                            <div class="checkbox checkbox-success">
                                                <input id="c9" type="checkbox">
                                                <label for="c9">
                                                    <span class="font-16">It is a long established fact that a reader will be distracted by the readable.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">04:45 PM</h6>
                                            </div>
                                        </li>
                                        <li class="list-group-item bl-success">
                                            <div class="checkbox checkbox-success">
                                                <input id="c10" type="checkbox">
                                                <label for="c10">
                                                    <span class="font-16">It is a long established fact that a reader will be distracted by the readable.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">05:30 PM</h6>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="task-loadmore">
                                    <a href="javascript:void(0);" class="btn btn-default btn-outline btn-rounded">Load More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="display:none;">
                        <div class="white-box chat-widget">
                            <a href="javascript:void(0);" class="pull-right"><i class="icon-settings"></i></a>
                            <h4 class="box-title">Chat</h4>
                            <ul class="chat-list slimscroll" style="overflow: hidden;" tabindex="5005">
                                <li>
                                    <div class="chat-image"> <img alt="male" src="/plugins/images/users/avatar6.jpg"> </div>
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p><span class="font-semibold">Prof. Sangwan</span> Hey Daniel, This is just a sample chat. </p>
                                        </div>
                                        <span>2 Min ago</span>
                                    </div>
                                </li>
                                <li class="odd">
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p> buddy </p>
                                        </div>
                                        <span>2 Min ago</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="chat-image"> <img alt="male" src="/plugins/images/users/avatar6.jpg"> </div>
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p><span class="font-semibold">Prof. Sangwan</span> Bye now. </p>
                                        </div>
                                        <span>1 Min ago</span>
                                    </div>
                                </li>
                                <li class="odd">
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p> We have been busy all the day to make your website proposal and finally came with the super excited offer. </p>
                                        </div>
                                        <span>5 Sec ago</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="chat-send">
                                <input type="text" class="form-control" placeholder="Write your message">
                                <i class="fa fa-camera"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== Service-Panel ===== -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="icon-close right-side-toggler"></i></span> </div>
                        <div class="r-panel-body">
                            <ul class="hidden-xs">
                                <li><b>Layout Options</b></li>
                                <li>
                                    <div class="checkbox checkbox-danger">
                                        <input id="headcheck" type="checkbox" class="fxhdr">
                                        <label for="headcheck"> Fix Header </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-warning">
                                        <input id="sidecheck" type="checkbox" class="fxsdr">
                                        <label for="sidecheck"> Fix Sidebar </label>
                                    </div>
                                </li>
                            </ul>
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme working">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="yellow" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="black" class="black-theme">6</a></li>
                                <li class="db"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="yellow-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="black-dark" class="black-dark-theme">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/plugins/images/users/avatar1.jpg" alt="user-img" class="img-circle"> <span>Belisarius Smith <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/plugins/images/users/avatar2.jpg" alt="user-img" class="img-circle"> <span>Camila Perez <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/plugins/images/users/avatar3.jpg" alt="user-img" class="img-circle"> <span>Melissa Peterson <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/plugins/images/users/avatar5.jpg" alt="user-img" class="img-circle"> <span>Andrew Drake <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/plugins/images/users/avatar4.jpg" alt="user-img" class="img-circle"> <span>Lawrence Sell <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/plugins/images/users/avatar6.jpg" alt="user-img" class="img-circle"> <span>Prof. Sangwan<small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ===== Service-Panel-End ===== -->

            </div>
            <!-- ===== Page-Container-End ===== -->

            <footer class="footer t-a-c">
                2018 \A9 Penn State - Cohort 19 &amp; 20 - Group 8.            </footer>
        </div>
        <!-- ===== Page-Content-End ===== -->
    </div>
    <!-- ===== Main-Wrapper-End ===== -->

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
