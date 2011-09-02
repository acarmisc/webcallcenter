<div id="login_box" class="">
	<form action="<?php echo site_url('home');?>" name="login" id="login" method="post">
	  <p><input type="text" size="25" name="username" id="username" /><br />
	    <label>nome utente</label></p>
		<p><input type="password" size="25" name="password" id="password" /><br />
		<label>password</label></p>
		<p><input type="submit" name="submit" value="entra" /> 
		<?php
		/* Recupero password non implementato
		<a href="#">Hai dimenticato la password?</a></p>
		*/
		?>
	</form>	
	<?php
		if($login_fallito){
			echo '<p>Attenzione: nome utente o password errati!</p>';
		}
	?>			
</div>