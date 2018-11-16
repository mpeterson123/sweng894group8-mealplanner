<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// View/Edit Food
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;

// Sub Title
$SUBTITLE = "View {$data['meal']->getRecipe()->getName()} Meal";

require_once( __HEADER__ ); ?>

<body class="mini-sidebar">


    <!-- ===== Main-Wrapper ===== -->
    <div id="wrapper">
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>


        <?php require_once( __NAVBAR__ ); ?>

        <?php require_once( __SIDEBAR__ ); ?>

        <!-- ===== Page-Content ===== -->
        <div class="page-wrapper">

            <!-- ===== Page-Container ===== -->
           <div class="container-fluid">
                <?php $data['session']->renderMessage(); ?>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><?php echo $data['meal']->getRecipe()->getName(); ?></h3>

                            <p class="text-muted m-b-30 font-13"> <?php echo $SUBTITLE; ?>
                            <a href="/Meals/index">&laquo; Return to meals</a>
                            </p>

                            <div class="form-group">
                                <p>
                                    <strong>Meal Date: </strong>
                                    <?php echo $data['meal']->getDate(true); ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <p>
                                    <strong>Recipe: </strong>
                                    <?php echo $data['meal']->getRecipe()->getName(); ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <p>
                                    <strong>Servings: </strong>
                                    <?php echo ($data['meal']->getRecipe()->getServings()*$data['meal']->getScaleFactor()).' (scaled by '.$data['meal']->getScaleFactor().')'; ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <p>
                                    <strong>Source: </strong>
                                    <?php echo $data['meal']->getRecipe()->getSource(); ?>
                                </p>
                            </div>

                            <hr>

                            <p><strong>Ingredients</strong></p>
                            <ul>
                            <?php foreach($data['meal']->getRecipe()->getIngredients() as $ingredient) { ?>
                                <li><?php echo '<strong>'.($ingredient->getQuantity()->getValue()*$data['meal']->getScaleFactor()).' '
                                    .$ingredient->getUnit()->getAbbreviation().'</strong>&nbsp;&nbsp;'
                                    .$ingredient->getFood()->getName(); ?>
                                </li>
                            <?php } ?>
                            </ul> <!-- end ingredientsWrapper -->

                            <hr>

                            <div class="form-group">
                                <p>
                                    <strong>Directions: </strong>
                                </p>
                                <p><?php echo $data['meal']->getRecipe()->getDirections(); ?></p>
                            </div>

                            <div class="form-group">
                                <p>
                                    <strong>Notes: </strong>
                                </p>
                                <p><?php echo $data['meal']->getRecipe()->getNotes(); ?></p>
                            </div>


                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Options</h3>

                            <a href="/Meals/complete/<?php echo $data['meal']->getId()?>"
                                class="btn btn-success btn-block m-t-15" >
                                Complete/Cook Meal
                            </a>

                            <a href="/Meals/edit/<?php echo $data['meal']->getId()?>"
                                class="btn btn-info btn-block m-t-15" >
                                Edit Meal
                            </a>

                            <a href="/Recipes/edit/<?php echo $data['meal']->getRecipe()->getId()?>"
                                class="btn btn-info btn-block m-t-15" >
                                Edit Original Recipe
                            </a>

                            <!-- Button trigger modal -->
                            <button
                                type="button"
                                class="btn btn-danger btn-block m-t-15"
                                data-toggle="modal"
                                data-target="#confirm-delete-modal">Remove Meal
                            </button>
                        </div>
                    </div>
                </div>

                <?php require_once( __SPANEL__ ); ?>

          </div>
            <!-- ===== Page-Container-End ===== -->

            <footer class="footer t-a-c">
                <?php echo __COPYRITE__; ?>
            </footer>

        </div>

        <!-- ===== Page-Content-End ===== -->

    </div>
    <!-- ===== Main-Wrapper-End ===== -->
    <?php require_once( __FOOTER__ ); ?>


</body>
</html>
