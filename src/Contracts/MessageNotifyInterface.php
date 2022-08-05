<?php

declare(strict_types=1);

namespace MessageNotify\Contracts;

interface MessageNotifyInterface
{
    public const INFO = 'info';

    public const ERROR = 'error';

    public const EMERGENCY = 'emergency';

    public const ALERT = 'alert';

    public const CRITICAL = 'critical';

    public const WARNING = 'warning';

    public const NOTICE = 'notice';

    public const DEBUG = 'debug';
}
