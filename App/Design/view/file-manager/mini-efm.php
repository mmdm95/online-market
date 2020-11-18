<?php

load_partial('file-manager/efm-view', [
    'the_options' => $the_options ?? [],
]);
load_partial('file-manager/efm', [
    'the_options' => $the_options ?? [],
]);
