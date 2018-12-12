	<?php $User = $this->request->session()->read("Auth.User"); ?>
	<nav class="navbar navbar-inverse">
		<div class="container">
		  <div class="navbar-header">
		    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
		      <span class="sr-only">Toogle Navigation</span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="<?= $this->Url->build('/') ?>">Futbolista</a>	    
		  </div>
		  <div class="navbar-collapse collapse">
		    <ul class="nav navbar-nav">

					<?php if ($User){ ?>		      

		      <li class="dropdown">
		        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<?= __('Bets'); ?> <span class="caret"></span>
						</a>
		        <ul class="dropdown-menu">

		          <li>
								<a href="<?= $this->Url->build('/Bets/index') ?>">
									<?= __('Mine'); ?>
								</a>
							</li>

		          <li>
								<a href="<?= $this->Url->build('/Bets/top10') ?>">
									<?= __('Top 10'); ?>
								</a>
							</li>

		          <li>
								<a href="<?= $this->Url->build('/Bets/pot') ?>">
									<?= __('Pote'); ?>
								</a>
							</li>

		        </ul>
		      </li>

		      <li class="dropdown">
		        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<?=__('Session');?> <span class="caret"></span>
						</a>
		        <ul class="dropdown-menu">
		          <li>
								<a href="<?= $this->Url->build('/Users/chpass') ?>">
									<?= __('Change Password'); ?>
								</a>
							</li>
		          <li>
								<a href="<?= $this->Url->build('/Users/logout') ?>">
									<?= __('Close'); ?> (<?= $User["username"] ?>)
								</a>
							</li>
		        </ul>
		      </li>
		    
		      <?php } ?>
		    
		    </ul>
		  </div><!--/.nav-collapse -->
		</div>
	</nav>
