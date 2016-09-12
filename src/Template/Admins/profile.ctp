 <?php
$this->layout = 'false';
$this->layout = 'default';
?>
<div id="page-wrapper" class="athelet-profile">
      <div class="row">
      <div class="col-lg-12 col-md-12">
       <?= $this->Flash->render('flash_message') ?>
 <div class="top-athlete">
       <?php foreach($admin as $row){?>
       <h1> <?php echo $row->first_name." ".$row->last_name;?></h1>  
       <?php } ?>   
       <?= $this->Html->link(__('Edit Profile'), ['controller'=>'Admins','action' => 'edit',  $this->request->session()->read('Auth.User.id')]) ?>
   </div></div>
     <div class="col-lg-12 col-md-12">
       <ul class="nav nav-tabs">
         <li class="active"><a data-toggle="tab" href="#home">Personal Information</a></li>
       </ul>
       <?php foreach($admin as $row){?>
       <div class="tab-content">
         <div id="home" class="tab-pane fade in active">
             <div class="line"><span>First Name:  </span> <span id="profile-content"><?php echo $row->first_name;?></span></div>
             <div class="line"><span>Last Name:  </span> <span id="profile-content"><?php echo $row->last_name;?></span></div>
             <div class="line"><span>Street:  </span> <span id="profile-content"><?php echo $row->address_street;?></span></div>
             <div class="line"><span>City:  </span> <span id="profile-content"><?php echo $row->address_city;?></span></div>
           <!--<div class="line"><span>State:  </span><?php echo $row->state->name;?> </div>-->
             <div class="line"><span>Zip Code:  </span> <span id="profile-content"><?php echo $row->address_zip;?></span></div>
             <div class="line"><span>Phone:  </span> <span id="profile-content"><?php echo $row->phone;?></span></div>
             <div class="line"><span>Email:  </span> <span id="profile-content"><?php echo $row->email;?></span></div>
         </div>
          <?php } ?> 
       </div>
     </div>
   </div>
       
</div>  