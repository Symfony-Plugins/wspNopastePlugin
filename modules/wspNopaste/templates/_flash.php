<?php if (isset($flashType)): ?>
	<?php if (is_string($flashType) and $sf_user->hasFlash($flashType)): ?>
		<p class="flash <?php echo $flashType ?>"><?php echo $sf_user->getFlash($flashType) ?></p>
	<?php endif; ?>
	<?php if (is_array($flashType)): ?>
		<?php if (count($flashType) > 1): ?>
			<ul class="flashlist">
			<?php foreach ($flashType as $eachFlashType): ?>
				<?php if ($sf_user->hasFlash($eachFlashType)): ?>
					<li class="flash <?php echo $eachFlashType ?>"><?php echo $sf_user->getFlash($eachFlashType) ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<p class="flash <?php echo $flashType ?>"><?php echo $sf_user->getFlash($flashType) ?></p>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>