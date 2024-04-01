<?php

namespace Modules\Core\App\src\Software;

interface ISoftware
{
    public function all(): array;

    public function getPhpVersion(): string;

    public function getRequiredPhpVersion(): string;

    public function checkPhpVersion(): bool;

    public function getPhpExtensions(): array;

    public function getApacheModules(): array;

    public function isPhpExtensionLoaded($extension): bool;

    public function isApacheModuleEnabled($extension): bool;
}
