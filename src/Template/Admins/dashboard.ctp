<h1 class="page-header post"> Admin Dashboard</h1>
  <div id="page-wrapper" style="min-height: 592px;">
   
    <!-- /.row -->
    <div class="row">
      <div class="col-lg-3 col-md-6 post">
        <div class="panel panel-default panel-white core-box ">
           <div class="panel-body no-padding">
            <div class="partition-green1 padding-20 text-center core-icon"> 
            <?php echo $this->Html->image('doctor1.png', ['alt' => '','draggable' => 'false']);?>
            </div>
            <div class="padding-20 core-content">
              <h3 class="title block no-margin">Doctors</h3>
              <span class="subtitle"> <?php echo $doctors;?></span> </div>
          </div>
          <div class="panel-footer clearfix no-padding"> <a href="#" class="col-xs-4 partition-red" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i></a>
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Search Doctors</h4>
                  </div>
                  <?php  echo $this->Form->create(null, [
                      'url' => ['controller' => 'Doctors', 'action' => 'index']
                  ]);
                  ?>
                  <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="search" placeholder="Search">
                      <button type="submit" class="btn btn-default"><span aria-hidden="true" class="glyphicon glyphicon-search"></span></button>
                    </div>
                  </div>
                  </div>
                </form>
              </div>
            </div>
            <a data-original-title="Add Content" href="<?php echo $this->Url->build('/admin/doctor', true);?>" class="col-xs-4 partition-blue" ><i class="fa fa-eye" aria-hidden="true"></i></a> <a  href="<?php echo $this->Url->build('/admin/doctors/add', true); ?>" class="col-xs-4 partition-green" ><i class="fa fa-plus"></i></a> </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 post">
        <div class="panel panel-default panel-white core-box" data-scroll-reveal="enter top move 50px after 0.2s">
            <div class="panel-body no-padding">
            <div class="partition-green1 padding-20 text-center core-icon">
             <?php echo $this->Html->image('athlete.png', ['alt' => '','draggable' => 'false','class'=>'athlete1']);?>
            </div>
            <div class="padding-20 core-content">
              <h3 class="title block no-margin">Athlete</h3>
              <span class="subtitle"> <?php echo $athletes;?></span> </div>
          </div>
          <div class="panel-footer clearfix no-padding"> <a href="#" class="col-xs-4 partition-red" data-toggle="modal" data-target="#myModalathlete"><i class="fa fa-search" aria-hidden="true"></i></a> 

          <div id="myModalathlete" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search Athletes</h4>
                  </div>
                  <?php  echo $this->Form->create(null, [
                      'url' => ['controller' => 'Athletes', 'action' => 'index']
                  ]);
                  ?>
                  <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="search" placeholder="Search">
                        <button type="submit" class="btn btn-default"><span aria-hidden="true" class="glyphicon glyphicon-search"></span></button>
                    </div>
                  </div>
                  </div>
                </form>
              </div>
            </div>


		  
		  <a href="<?php echo $this->Url->build('/admin/athletes', true); ?>" class="col-xs-4 partition-blue"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="<?php echo $this->Url->build('/admin/athletes/add', true); ?>" class="col-xs-4 partition-green"><i class="fa fa-plus"></i></a> </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 post">
        <div class="panel panel-default panel-white core-box" data-scroll-reveal="enter top move 50px after 0.3s">
          
          <div class="panel-body no-padding">
            <div class="partition-green1 padding-20 text-center core-icon">
            <?php echo $this->Html->image('leguage.png', ['alt' => '','draggable' => 'false','class'=>'league1']);?>

            </div>
            <div class="padding-20 core-content">
              <h3 class="title block no-margin">League</h3>
              <span class="subtitle"> <?php echo $leagues;?></span> </div>
          </div>
          <div class="panel-footer clearfix no-padding"> <a href="#" class="col-xs-4 partition-red" data-toggle="modal" data-target="#myModalleague"><i class="fa fa-search" aria-hidden="true"></i></a>

              <div id="myModalleague" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search League</h4>
                  </div>
                  <?php  echo $this->Form->create(null, [
                      'url' => ['controller' => 'Leagues', 'action' => 'index']
                  ]);
                  ?>
                  <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="search" placeholder="Search">
                    <button type="submit" class="btn btn-default"><span aria-hidden="true" class="glyphicon glyphicon-search"></span></button>
                    </div>
                  </div>
                
                </div>
                </form>
              </div>
            </div>

           <a data-original-title="Add Content" href="<?php echo $this->Url->build('/admin/leagues/', true); ?>" class="col-xs-4 partition-blue" ><i class="fa fa-eye" aria-hidden="true"></i></a> <a  href="<?php echo $this->Url->build('/admin/leagues/add', true); ?>" class="col-xs-4 partition-green" ><i class="fa fa-plus"></i></a> </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 post">
        <div class="panel panel-default panel-white core-box" data-scroll-reveal="enter top move 50px after 0.4s">
              <div class="panel-body no-padding">
            <div class="partition-green1 padding-20 text-center core-icon">
             <?php echo $this->Html->image('subscription.png', ['alt' => '','draggable' => 'false','class'=>'subscription1']);?>
            </div>
            <div class="padding-20 core-content">
              <h3 class="title block no-margin">Fee</h3>
              <span class="subtitle"> <?php echo $subscriptionPackages;?></span> </div>
          </div>
          <div class="panel-footer clearfix no-padding"> <a href="#" class="col-xs-4 partition-red" data-toggle="modal" data-target="#myModalsub"><i class="fa fa-search" aria-hidden="true"></i></a> 

           <div id="myModalsub" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search Subscription Packages</h4>
                  </div>
                  <?php  echo $this->Form->create(null, [
                      'url' => ['controller' => 'SubscriptionPackages', 'action' => 'index']
                  ]);
                  ?>
                  <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="search" placeholder="Search">
                    <button type="submit" class="btn btn-default"><span aria-hidden="true" class="glyphicon glyphicon-search"></span></button>
                    </div>
                  </div>
                 
                </div>
                </form>
              </div>
            </div>

          <a data-original-title="Add Content" href="<?php echo $this->Url->build('/admin/subscriptionpackages', true); ?>" class="col-xs-4 partition-blue" ><i class="fa fa-eye" aria-hidden="true"></i></a> <a  href="<?php echo $this->Url->build('/admin/subscriptionpackages/add', true); ?>" class="col-xs-4 partition-green" ><i class="fa fa-plus"></i></a> </div>
        </div>
      </div>
      <div class="base-line-test">
        <div class="col-lg-4 col-md-4"> 
        <?php echo $this->Html->image('Brain-Science.png', ['alt' => '','draggable' => 'false']);?>
            <div class="base-content">
                <h3 class="title-base">Baseline Test</h3>
            </div>
        </div>
        <div class="col-lg-4 col-md-4"> 
        <?php echo $this->Html->image('event.png', ['alt' => '','draggable' => 'false']);?>
            
            <div class="base-content">
                <h3 class="title-base"> Events</h3>
            </div>
        </div>
        <div class="col-lg-4 col-md-4"> 
        <?php echo $this->Html->image('team.png', ['alt' => '','draggable' => 'false']);?>
            <div class="base-content">
                <h3 class="title-base">Teams</h3>
            </div>
        </div>
      </div>
    </div>
  </div>
