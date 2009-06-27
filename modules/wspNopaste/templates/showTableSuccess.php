<?php if (count($pastes)): ?>
<h1><?php echo $nopasteTableHeading ?></h1>
<?php include_partial('wspNopaste/showPastesTable', array('pastes' => $pastes)); ?>
<?php endif; ?>