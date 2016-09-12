<?php 
$this->layout = 'false';
$this->layout = 'login_layout';
 ?>
<div class="container-fluid admin-bg">
<div class="leg-form admin-icon">  
    <?= $this->Form->create(null,['id'=>'admin_login','class'=>'form-inline top-arrow']) ?>
	  	<center>
			
    	  	<div>
      			<div class="logo-img">
      				<?php echo $this->Html->image('admin.png', ['alt' => 'CakePHP','draggable'=>'false']); ?>
  				</div>
 				<p>Administrator Login</p>	
      		</div>
          <?= $this->Flash->render('flash_message') ?>
 			<div class="row form-group astrik">
            <div class="input-group-addon email "></div>
        	  	<input type="text" class="form-control email" id="email" name="email" placeholder="Email Address" value="<?php if(isset($_REQUEST['email'])){ echo $_REQUEST['email'];} ?>">
        	</div>
        	
 			<div class="row form-group astrik">
            <div class="input-group-addon pass "></div>
        	  	<input type="password" class="form-control pass" id="password" name="password" placeholder="Password (length 8 to 15)">
              <div class="show-pass"><input id="passwordcheck" type="checkbox" /><span>Show password</span></div>
      </div>
        	
 			<div class="row  form-group">
					<?= $this->Form->button('Log In',['class'=>'btn btn-primary']) ?>

 				 	<div><?php echo $this->Html->link('Forgot Password', ['controller'=>'Admins','action'=>'forgotPassword']);?></div>
        	</div>
    	</center>
  	<?= $this->Form->end() ?>
</div>

</div>
