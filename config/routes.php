<?php

namespace GT\Site;

/** @var \Base $f3 */
$f3->route('GET /','GT\Site\Controller\Index->index');
$f3->route('POST /checkin','GT\Site\Controller\Checkin->index');

