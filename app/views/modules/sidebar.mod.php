<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Sidebar Module
///////////////////////////////////////////////////////////////////////////////

?>
        <!-- ===== Left-Sidebar ===== -->
        <aside class="sidebar">
            <div class="scroll-sidebar">
                <div class="user-profile">
                    <div class="dropdown user-pro-body">
                        <div class="profile-image">
                            <img src="/images/users/avatar2.jpg" alt="user-img" class="img-circle">
                            <a href="javascript:void(0);" class="dropdown-toggle u-dropdown text-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="badge badge-danger">
                                    <i class="fa fa-angle-down"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated flipInY">
                                <li><a href="javascript:void(0);"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="javascript:void(0);"><i class="fa fa-inbox"></i> Inbox</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/Account/settings"><i class="fa fa-cog"></i> Account Settings</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="Account/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </div>
                        <p class="profile-text m-t-15 font-16"><a href="javascript:void(0);"><?php echo $data['user']['namefirst']; echo ' ' . $data['user']['namelast'] ?></a></p>
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
                                <li><a href="/FoodItems/">All Foods</a></li>
                                <li><a href="/FoodItems/create">Add Food Item</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="icon-book-open fa-fw"></i> <span class="hide-menu"> Recipes<span class="label label-rounded label-danger pull-right">0</span></span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="javascript:void(0);">View Recipes</a></li>
                                <li><a href="javascript:void(0);">Add Recipe</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="icon-book-open fa-fw"></i> <span class="hide-menu"> Meals<span class="label label-rounded label-danger pull-right">0</span></span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="javascript:void(0);">View Meal Plan</a></li>
                                <li><a href="javascript:void(0);">Add Meal Plan</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="icon-book-open fa-fw"></i> <span class="hide-menu"> Groceries<span class="label label-rounded label-danger pull-right">0</span></span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="javascript:void(0);">View Grocery List</a></li>
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
