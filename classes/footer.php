<div class="flex-center" style="flex-direction: row;">
	<?php $userPanelLink = (isset($apple)) ? "/inventory/apple/userPanel.php" : "/inventory/userPanel.php"; ?>
	<a href="<?php echo $userPanelLink; ?>">
		<button class="button textcenter center" style="width:110px;">User Panel</button>
	</a>
	<?php if(isset($admin)):?>
	<a href="/inventory/admin/adminPanel.php">
		<button class="button textcenter center" style="width:120px;">Admin Panel</button>
	</a>
	<?php endif; ?>
	<a href="/inventory/logout.php">
		<button class="button textcenter center">Logout</button>
	</a>
</div>

</body>
</html>
