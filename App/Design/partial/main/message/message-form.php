<?php
$errors = $errors ?? [];
$hasCSRFTokenError = \session()->getFlash('CSRFRouteIsUndefined');

if (!is_null($hasCSRFTokenError)) {
    array_unshift($errors, $hasCSRFTokenError);
}
?>
<?php load_partial('main/message/message-error', ['errors' => $errors ?? []]); ?>
<?php load_partial('main/message/message-warning', ['warning' => $warning ?? '']); ?>
<?php load_partial('main/message/message-success', ['success' => $success ?? '']); ?>
