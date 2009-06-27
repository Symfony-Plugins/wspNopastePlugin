<?php /* @var $user sfGuardUser */
if ($user->getUsername())
{
  echo $user->getUsername();
}
else
{
  echo 'anonymous';
}