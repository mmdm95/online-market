<?php
$errors = $errors ?? [];
$hasCSRFTokenError = \session()->getFlash('CSRFRouteHasUndefined');

if (!is_null($hasCSRFTokenError)) {
    array_unshift($errors, $hasCSRFTokenError);
}
?>
<?php load_partial('main/message-error', ['errors' => $errors ?? []]); ?>
<?php load_partial('main/message-success', ['success' => $success ?? '']); ?>