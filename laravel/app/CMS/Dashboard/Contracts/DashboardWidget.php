<?php

namespace App\CMS\Dashboard\Contracts;

interface DashboardWidget
{
    /**
     * Уникальный идентификатор.
     */
    public static function key(): string;

    /**
     * Название виджета.
     */
    public static function title(): string;

    /**
     * Может ли отображаться.
     */
    public function visible(): bool;

    /**
     * Данные виджета.
     */
    public function data(): array;
}
