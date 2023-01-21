<?php
namespace PlicniTeplice\Recipes\Api\Core\Settings;

interface ISettings
{
    function get(string $key = '');
}